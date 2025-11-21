<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = 'jeJCgeHoNppRXxAVPHNCrwUkN5KdN82LwhA8rI8MqhSzB8H1840YqZ90';
    }

    public function index()
    {
        $category = request()->query('category');
        $query = request()->query('q');

        // Arama varsa, kategoriden önce gelir
       if ($query) {
    $photos = $this->getPhotos($query, 30);
    return view('dashboard', [
        'photos' => $photos,
        'query' => $query,
        'popularPhotos' => [],
        'trendingPhotos' => [],
        'naturePhotos' => [],
    ]);
}

        if ($category) {
            $photos = $this->getPhotos($category, 30);
            return view('dashboard', [
                'photos' => $photos,
                'category' => $category
            ]);
        }

        // Ana sayfa (keşfet) görünümü
        $popularPhotos = $this->getPopularPhotos();
        $trendingPhotos = $this->getPhotos('trendy');
        $naturePhotos = $this->getPhotos('nature');

        return view('dashboard', [
            'popularPhotos' => $popularPhotos,
            'trendingPhotos' => $trendingPhotos, 
            'naturePhotos' => $naturePhotos
        ]);
    }

    private function getPopularPhotos($perPage = 15)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get('https://api.pexels.com/v1/curated', [
                'per_page' => $perPage,
                'page' => rand(1, 10)
            ]);

            return $response->successful()
                ? $response->json()['photos']
                : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getPhotos($query, $perPage = 15)
    {
        try {
            $categoryMap = [
                'moda' => 'fashion',
                'yemek' => 'food',
                'seyahat' => 'travel',
                'ev-dekoru' => 'home decor',
                'hayvanlar' => 'animals',
                'sanat' => 'art'
            ];

            $searchQuery = $categoryMap[$query] ?? $query;

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get('https://api.pexels.com/v1/search', [
                'query' => $searchQuery,
                'per_page' => $perPage,
                'page' => rand(1, 20)
            ]);

            return $response->successful()
                ? $response->json()['photos']
                : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function saveImage(Request $request)
    {
        try {
            if ($request->has('action') && $request->action === 'delete') {
                $imageUrl = $request->image_url;
                $savedImages = session()->get('saved_images', []);
                $savedImages = array_filter($savedImages, fn($img) => $img['url'] !== $imageUrl);
                session()->put('saved_images', array_values($savedImages));

                return response()->json([
                    'success' => true,
                    'message' => 'Görsel başarıyla silindi'
                ]);
            }

            $validated = $request->validate([
                'image_url' => 'required|url',
                'photographer' => 'required|string',
                'alt' => 'required|string'
            ]);

            $savedImages = session()->get('saved_images', []);
            $exists = collect($savedImages)->contains(fn($img) => $img['url'] === $validated['image_url']);

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu görsel zaten kaydedilmiş'
                ]);
            }

            $savedImages[] = [
                'url' => $validated['image_url'],
                'photographer' => $validated['photographer'],
                'alt' => $validated['alt'],
                'saved_at' => now()->toDateTimeString()
            ];

            session()->put('saved_images', $savedImages);

            return response()->json([
                'success' => true,
                'message' => 'Görsel başarıyla kaydedildi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Görsel kaydedilirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}   