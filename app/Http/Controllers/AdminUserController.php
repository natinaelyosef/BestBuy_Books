<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::whereIn('account_type', ['sub_admin', 'super_admin'])
            ->orderBy('account_type')
            ->orderBy('name')
            ->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => 'required|in:sub_admin,super_admin',
        ]);

        User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'account_type' => $data['account_type'],
            'is_active'    => true,
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('status', 'Admin account created.');
    }

    public function edit(User $admin)
    {
        // Only super_admin can edit admins
        if (auth()->user()->account_type !== 'super_admin') {
            abort(403);
        }

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if (auth()->user()->account_type !== 'super_admin') {
            abort(403);
        }

        // Prevent demoting self
        if ($admin->id === auth()->id() && $request->account_type !== 'super_admin') {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }

        $rules = [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $admin->id,
            'account_type' => 'required|in:sub_admin,super_admin',
            'is_active'    => 'boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $data = $request->validate($rules);

        $updateData = [
            'name'         => $data['name'],
            'email'        => $data['email'],
            'account_type' => $data['account_type'],
            'is_active'    => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $admin->update($updateData);

        return redirect()
            ->route('admin.admins.index')
            ->with('status', 'Admin account updated.');
    }

    public function destroy(User $admin)
    {
        if (auth()->user()->account_type !== 'super_admin') {
            abort(403);
        }

        if ($admin->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $name = $admin->name;
        $admin->delete();

        return redirect()
            ->route('admin.admins.index')
            ->with('status', "{$name}'s account has been deleted.");
    }

    public function toggleActive(User $admin)
    {
        if (auth()->user()->account_type !== 'super_admin') {
            abort(403);
        }

        if ($admin->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $admin->update(['is_active' => !$admin->is_active]);
        $msg = $admin->is_active ? "activated" : "deactivated";

        return redirect()->back()->with('status', "{$admin->name}'s account has been {$msg}.");
    }
}