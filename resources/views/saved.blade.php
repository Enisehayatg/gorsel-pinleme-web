<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kaydedilenler - Görsel Pinle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .masonry-grid {
            columns: 1;
        }
        
        @media (min-width: 640px) {
            .masonry-grid {
                columns: 2;
            }
        }
        
        @media (min-width: 768px) {
            .masonry-grid {
                columns: 3;
            }
        }
        
        @media (min-width: 1024px) {
            .masonry-grid {
                columns: 4;
            }
        }
        
        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex">
    <!-- Sol Menü -->
    <aside class="w-20 bg-white h-screen py-6 px-2 flex flex-col items-center shadow">
        <div class="mb-10">
            <img src="{{ asset('images/pinterest-5-512.png') }}" alt="Logo" class="w-8 h-8">
        </div>
        <nav class="flex flex-col items-center space-y-10">
            <a href="{{ url('/dashboard') }}" title="Anasayfa" class="hover:text-pink-500 text-gray-700">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 12l9-9 9 9v9a2 2 0 0 1-2 2h-5v-6H10v6H5a2 2 0 0 1-2-2v-9z"/>
                </svg>
            </a>
            <a href="{{ url('/add') }}" title="Pin Oluştur" class="hover:text-pink-500 text-gray-700">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 13H13v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
            </a>
            <a href="{{ url('/saved') }}" title="Kaydet" class="text-pink-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17 3H7a2 2 0 0 0-2 2v16l7-5 7 5V5a2 2 0 0 0-2-2z"/>
                </svg>
            </a>
            <a href="{{ url('/todo') }}" title="To Do List" class="hover:text-pink-500 text-gray-700">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/>
                </svg>
            </a>
        </nav>
    </aside>

    <!-- Ana İçerik -->
    <div class="flex-1">
        <!-- Üst Bar -->
        <header class="bg-white px-6 py-3 shadow flex items-center justify-between">
            <input type="text"
                   placeholder="Ara"
                   class="w-1/2 px-4 py-2 bg-pink-50 border border-pink-300 text-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-pink-400 transition">
            
            <div class="flex items-center gap-4">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <a href="{{ url('/profile') }}" class="block">
                    <img src="https://i.pravatar.cc/150?img=10" alt="Profil"
                         class="w-10 h-10 rounded-full hover:ring-2 hover:ring-pink-400 transition">
                </a>
            </div>
        </header>

        <div class="p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Kaydedilen Görseller</h1>

            @php
                $savedImages = session('saved_images', []);
            @endphp

            @if(count($savedImages) > 0)
                <div class="masonry-grid gap-4">
                    @foreach($savedImages as $image)
                        <div class="masonry-item">
                            <div class="relative rounded-lg shadow-md overflow-hidden group transition hover:scale-105 hover:shadow-xl bg-white">
                                <img src="{{ $image['url'] }}" class="w-full object-cover" alt="{{ $image['alt'] }}">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                                    <p class="text-white text-sm font-medium truncate">{{ $image['photographer'] }}</p>
                                    <p class="text-white/80 text-xs">{{ \Carbon\Carbon::parse($image['saved_at'])->diffForHumans() }}</p>
                                </div>
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="deleteImage('{{ $image['url'] }}')" 
                                            class="bg-white text-red-500 hover:bg-red-500 hover:text-white px-3 py-1 rounded-full text-sm shadow-md transition">
                                        Sil
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">Henüz Kaydedilen Görsel Yok</h2>
                    <p class="text-gray-500">Dashboard'dan beğendiğiniz görselleri kaydetmeye başlayın!</p>
                    <a href="{{ url('/dashboard') }}" 
                       class="inline-block mt-4 px-6 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600 transition">
                        Dashboard'a Git
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function deleteImage(imageUrl) {
    if (confirm('Bu görseli silmek istediğinizden emin misiniz?')) {
        // Get current saved images
        let savedImages = @json(session('saved_images', []));
        
        // Remove the image
        savedImages = savedImages.filter(img => img.url !== imageUrl);
        
        // Update session via AJAX
        fetch('/save-image', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                action: 'delete',
                image_url: imageUrl
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to show updated list
                window.location.reload();
            }
        });
    }
}
</script>

</body>
</html>
