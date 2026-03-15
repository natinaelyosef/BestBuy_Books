<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserManagementController extends Controller
{
    /**
     * Display a listing of all users (customers and store owners).
     */
    public function index(Request $request)
    {
        $query = User::whereIn('account_type', ['customer', 'store_owner']);
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Type filter
        if ($request->filled('type')) {
            $query->where('account_type', $request->type);
        }
        
        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)
                          ->where('is_banned', false)
                          ->where(function ($q) {
                              $q->where('is_restricted', false)
                                ->orWhereNull('is_restricted');
                          });
                    break;
                case 'banned':
                    $query->where('is_banned', true);
                    break;
                case 'restricted':
                    $query->where('is_restricted', true)
                          ->where('restricted_until', '>', now());
                    break;
            }
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Only allow viewing customers and store owners
        if (!in_array($user->account_type, ['customer', 'store_owner'])) {
            abort(404);
        }
        
        // Get recent reports involving this user
        $reports = \App\Models\IssueReport::where('user_id', $user->id)
            ->orWhere('reported_user_id', $user->id)
            ->with(['user', 'reportedUser'])
            ->latest()
            ->limit(10)
            ->get();
        
        return view('admin.users.show', compact('user', 'reports'));
    }
    
    /**
     * Ban a user.
     */
    public function banUser(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $user->update([
            'is_banned' => true,
            'ban_reason' => $request->reason,
            'banned_at' => now(),
            'is_restricted' => false, // Remove restriction if exists
            'restricted_until' => null,
        ]);
        
        return redirect()->back()->with('status', "{$user->name} has been banned. Reason: {$request->reason}");
    }
    
    /**
     * Unban a user.
     */
    public function unbanUser(User $user)
    {
        $user->update([
            'is_banned' => false,
            'ban_reason' => null,
            'banned_at' => null,
        ]);
        
        return redirect()->back()->with('status', "{$user->name} has been unbanned.");
    }
    
    /**
     * Issue a warning to a user.
     */
    public function warnUser(User $user)
    {
        $user->increment('warning_count');
        
        return redirect()->back()->with('status', "Warning issued to {$user->name}. Total warnings: {$user->warning_count}");
    }
    
    /**
     * Restrict a user for a specified number of days.
     */
    public function restrictUser(Request $request, User $user)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);
        
        // Convert to integer explicitly
        $days = (int) $request->days;
        
        $user->update([
            'is_restricted' => true,
            'restricted_until' => now()->addDays($days),
        ]);
        
        return redirect()->back()->with('status', "{$user->name} has been restricted for {$days} day(s).");
    }
    
    /**
     * Remove restriction from a user.
     */
    public function unrestrictUser(User $user)
    {
        $user->update([
            'is_restricted' => false,
            'restricted_until' => null
        ]);
        
        return redirect()->back()->with('status', "{$user->name}'s restriction has been lifted.");
    }
}