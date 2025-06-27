<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
  public function index()
  {
    return view('authentication.registration');
  }

  public function store(Request $request)
  {
    try {
      // Validasi input
      $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone_number' => 'required|string|max:15|unique:users,phone_number',
        'password' => 'required',
      ]);

      $validatedData['password'] = Hash::make($validatedData['password']);

      $convertedPhoneNumber = $this->formatPhoneNumber($validatedData['phone_number']);
      $validatedData['phone_number'] = $convertedPhoneNumber;
      Log::info('Converted Phone Number: ' . $convertedPhoneNumber);

      // Buat user baru
      $newUser = User::create($validatedData);
      // $user->save();

      // Redirect atau tampilkan pesan sukses
      return redirect('/login')->with('success', 'Registration successful. Please log in.');
    } catch (\Exception $e) {
      // Tangani error jika terjadi
      Log::error('Registration Error: ' . $e->getMessage());
      return redirect('/registration')->with('error', 'Registrasi Error: ' . $e->getMessage());
    }
  }

  private function formatPhoneNumber($phone)
  {
    $phone = preg_replace('/\D/', '', $phone); // Hanya angka
    if (substr($phone, 0, 1) === '0') {
      return '+62' . substr($phone, 1);
    }
    if (substr($phone, 0, 2) === '62') {
      return '+' . $phone;
    }
    if (substr($phone, 0, 3) === '+62') {
      return $phone;
    }
    return $phone;
  }
}
