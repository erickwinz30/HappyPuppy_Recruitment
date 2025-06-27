<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function index()
  {
    return view('authentication.login');
  }

  public function authenticate(Request $request)
  {
    try {
      $credentials = $request->validate([
        'email' => 'required|email|max:255',
        'password' => 'required|min:5|max:255',
      ]);

      if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect('/');
      }
    } catch (\Exception $e) {
      Log::error('Login attempt failed: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Login failed. Please try again.');
    }
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
  }
}
