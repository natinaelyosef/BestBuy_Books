<?php

namespace App\Http\Controllers;

use App\Models\IssueReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUserManagementController extends Controller
{
    /**
     * List all customers and store owners.
     */
    public function index(Request $request)
    {
        $query = User::whereIn('account_type', ['customer', 'store_owner']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('account_type', $request->type);
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'banned'     => $query->where('is_banned', true),
                'inactive'   => $query->where('is_active', false),
                'restricted' => $query->where('is_restricted', true),
                'active'     => $query->where('is_banned', false)->where('is_active', true),
                default      => null,
            };
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show a single user's profile and report history.
     */
    public function show(User $user)
    {
        $reports = IssueReport::with(['user'])
            ->where(function ($q) use ($user) {
                $q->where('reported_user_id', $user->id)
                  ->orWhere('user_id', $user->id);
            })
            ->latest()
            ->take(20)
            ->get();

        return view('admin.users.show', compact('user', 'reports'));
    }

    /**
     * Ban a user.
     */
    public function banUser(Request $request, User $user)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $user->update([
            'is_banned'  => true,
            'ban_reason' => $request->reason,
            'banned_at'  => now(),
        ]);

        return redirect()->back()->with('status', "{$user->name} has been banned.");
    }

    /**
     * Unban a user.
     */
    public function unbanUser(User $user)
    {
        $user->update([
            'is_banned'  => false,
            'ban_reason' => null,
            'banned_at'  => null,
        ]);

        return redirect()->back()->with('status', "{$user->name} has been unbanned.");
    }

    /**
     * Issue a warning to a user.
     */
    public function warnUser(User $user)
    {
        $user->increment('warning_count');
        return redirect()->back()->with('status', "Warning issued to {$user->name}. Total warnings: {$user->warning_count}.");
    }

    /**
     * Restrict a user for a given number of days.
     */
    public function restrictUser(Request $request, User $user)
    {
        $request->validate(['days' => 'required|integer|min:1|max:365']);

        $user->update([
            'is_restricted'   => true,
            'restricted_until' => now()->addDays($request->days),
        ]);

        return redirect()->back()->with('status', "{$user->name} has been restricted for {$request->days} day(s).");
    }

    /**
     * Remove restriction from a user.
     */
    public function unrestrictUser(User $user)
    {
        $user->update(['is_restricted' => false, 'restricted_until' => null]);
        return redirect()->back()->with('status', "{$user->name}'s restriction has been lifted.");
    }
}
