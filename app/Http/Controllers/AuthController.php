<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $account = Account::where('email', $credentials['email'])->first();

        // Check if account exists and if the password is correct
        if ($account && Hash::check($credentials['password'], $account->password)) {
            // Store user in session
            session(['user' => $account]);
            session()->flash('success', 'Login successful');
            // Redirect based on user role
            return redirect()->route($account->role === 'admin' ? 'admin.dashboard' : 'user.dashboard');
        }

        // If credentials are invalid, flash error message and stay on the login page
        session()->flash('error', 'Invalid email or password');
        return back(); // This will not redirect but stay on the same page
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/');
    }
}
