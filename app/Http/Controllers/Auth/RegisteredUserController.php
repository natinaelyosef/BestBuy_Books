<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route; // Add this if you use Route::has() or similar
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => 'required|in:customer,store_owner',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_type' => $request->account_type,
        ]);

        event(new Registered($user));
        Auth::login($user);

        $redirectTo = $this->redirectPathForAccountType($user->account_type);
        return redirect()->to($redirectTo);
    }

    private function redirectPathForAccountType(?string $accountType): string
    {
        if ($accountType === 'store_owner') {
            return route('store.dashboard', absolute: false);
        }
        
        if ($accountType === 'customer') {
            return route('customer.dashboard', absolute: false);
        }
        
        // FIXED: Redirect admins to admin dashboard
        if (in_array($accountType, ['sub_admin', 'super_admin'], true)) {
            return route('admin.dashboard', absolute: false);
        }
        
        return route('dashboard', absolute: false);
    }
}