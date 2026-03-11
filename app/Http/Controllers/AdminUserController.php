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
        $admins = User::query()
            ->whereIn('account_type', ['sub_admin', 'super_admin'])
            ->orderBy('account_type')
            ->orderBy('name')
            ->get();

        return view('admin.admins.index', [
            'admins' => $admins,
        ]);
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => 'required|in:sub_admin,super_admin',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'account_type' => $data['account_type'],
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('status', 'Admin account created.');
    }
}
