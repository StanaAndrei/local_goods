<?php

namespace App\Http\Controllers\Auth;

use App\Enums\BuyerType;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $data['role'] = Role::from((int) $data['role']); // Cast to enum

        if (isset($data['buyer_type']) && $data['buyer_type'] !== '' && $data['buyer_type'] !== null) {
            $data['buyer_type'] = BuyerType::from((int) $data['buyer_type']);
        } else {
            $data['buyer_type'] = null;
        }

        $user = User::create($data);
        Auth::login($user);

        return redirect('/dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();
        if (! $user) {
            return back()->withErrors([
                'email' => 'This email address is not registered.',
            ])->withInput();
        }
        if (! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'The password you entered is incorrect.',
            ])->withInput();
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([// other err
            'email' => 'Login failed!',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
