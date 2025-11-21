<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(Request $request, string $id = null)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil başarıyla güncellendi'
        ]);
    }

    public function destroy(string $id = null)
    {
        $user = Auth::user();
        
        // Kullanıcının pinlerini sil
        $user->pins()->delete();
        
        // Kullanıcının todo'larını sil
        $user->todos()->delete();
        
        // Kullanıcıyı sil
        $user->delete();
        
        Auth::logout();
        
        return response()->json([
            'success' => true,
            'message' => 'Hesabınız başarıyla silindi'
        ]);
    }
} 