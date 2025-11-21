<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PinController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string',
                'image' => 'required|image|max:2048'
            ]);

            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lütfen bir görsel yükleyin.'
                ], 422);
            }

            // Store the image in the public disk under pins directory
            $imagePath = $request->file('image')->store('pins', 'public');

            $pin = Pin::create([
                'user_id' => Auth::id(),
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'category' => $validatedData['category'],
                'image_path' => $imagePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pin başarıyla oluşturuldu'
            ]);

        } catch (\Exception $e) {
            \Log::error('Pin oluşturma hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUserPins($userId)
    {
        try {
            $pins = Pin::where('user_id', $userId)
                      ->orderBy('created_at', 'desc')
                      ->get();

            // Add the full URL for each image
            foreach ($pins as $pin) {
                $pin->image_url = asset('storage/' . $pin->image_path);
            }

            return response()->json($pins);
        } catch (\Exception $e) {
            \Log::error('Pin listesi hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Pinler yüklenirken bir hata oluştu.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pin = Pin::where('user_id', Auth::id())->findOrFail($id);
            
            // Görseli storage'dan sil
            if ($pin->image_path) {
                Storage::disk('public')->delete($pin->image_path);
            }
            
            // Pin'i veritabanından sil
            $pin->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Pin başarıyla silindi'
            ]);
        } catch (\Exception $e) {
            \Log::error('Pin silme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Pin silinirken bir hata oluştu.'
            ], 500);
        }
    }
}
