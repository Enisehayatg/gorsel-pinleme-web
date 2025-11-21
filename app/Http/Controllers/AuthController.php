<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\GenericUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'birth_date' => 'required|date',
            'firebase_uid' => 'nullable|string',
        ]);

        // Check if user with this email already exists
        $existingUser = User::where('email', $validated['email'])->first();
        if ($existingUser) {
            // If the user registered with Firebase, update the Firebase UID
            if (!empty($validated['firebase_uid']) && empty($existingUser->firebase_uid)) {
                $existingUser->firebase_uid = $validated['firebase_uid'];
                $existingUser->save();
                Auth::login($existingUser);
                return redirect()->route('dashboard');
            }

            return redirect()->back()->withErrors([
                'email' => 'Bu e-posta adresi zaten kullanılıyor.',
            ])->withInput();
        }

        // Create user in the local database
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'birth_date' => $validated['birth_date'],
            'firebase_uid' => $validated['firebase_uid'] ?? null,
        ]);

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Verilen bilgiler kayıtlarımızla eşleşmiyor.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function loginWithGoogle(Request $request)
    {
        \Log::info('Google login isteği alındı', $request->all());
        
        try {
            // JSON verilerini doğrula
            $validated = $request->validate([
                'firebase_uid' => 'required|string',
                'email' => 'required|email',
                'name' => 'nullable|string'
            ]);

            \Log::info('Doğrulama başarılı', $validated);

            // Kullanıcıyı Firebase UID veya email ile bul
            $user = User::where('firebase_uid', $validated['firebase_uid'])
                       ->orWhere('email', $validated['email'])
                       ->first();

            \Log::info('Kullanıcı arama sonucu:', ['found' => (bool)$user]);

            if ($user) {
                // Kullanıcı varsa, Firebase UID'sini güncelle (eğer gerekiyorsa)
                if (empty($user->firebase_uid)) {
                    $user->firebase_uid = $validated['firebase_uid'];
                    $user->save();
                    \Log::info('Kullanıcı Firebase UID güncellendi');
                }
                
                Auth::login($user);
                \Log::info('Kullanıcı girişi başarılı', ['user_id' => $user->id]);

                return response()->json([
                    'success' => true,
                    'redirect' => route('dashboard')
                ]);
            }

            \Log::info('Kullanıcı bulunamadı, kayıt sayfasına yönlendiriliyor');
            
            // Kullanıcı yoksa kayıt sayfasına yönlendir
            return response()->json([
                'success' => false,
                'action' => 'register',
                'message' => 'Kullanıcı bulunamadı. Lütfen önce kayıt olun.',
                'redirect' => route('register')
            ], 404);

        } catch (\Exception $e) {
            \Log::error('Google login hatası: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Giriş işlemi sırasında bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
