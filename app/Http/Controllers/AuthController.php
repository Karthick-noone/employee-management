<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
// use Illuminate\Support\Facades\Auth;
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

        if ($account && Hash::check($credentials['password'], $account->password)) {
            session(['user' => $account]);
            session()->flash('success', 'Login successful');
            return redirect()->route($account->role === 'admin' ? 'admin.dashboard' : 'user.dashboard');
        }

        return redirect('/')->withErrors(['Invalid credentials']);
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/')->with('success', 'You have successfully logged out.');
    }
    
}
