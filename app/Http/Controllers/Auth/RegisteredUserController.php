<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisteredUserController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    } 

    public function register(Request $request)
    {
         $validator = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,owner',
            'location' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'location' => $request->location,
        ]);

        Auth::login($user);
    
        return $this->redirectBasedOnRole($user);
    }
    
    public function showLoginForm()
    {
        if (!Auth::check()) {
            return redirect()->route('welcome');
        }
        return view('auth.login');
    }
    

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return $this->redirectBasedOnRole($user);
        } else {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }
    }

    protected function redirectBasedOnRole(User $user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('dashboard.admin');
            case 'user':
                return redirect()->route('dashboard.user');
            case 'owner':
                return redirect()->route('dashboard.owner');
            default:
                abort(403, 'Unauthorized access');
        }
    }
}
