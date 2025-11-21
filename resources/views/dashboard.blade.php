<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard - Görsel Pinle</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .pin:hover .pin-actions {
      opacity: 1;
      transform: translateY(0);
    }
    
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

    .hover-buttons {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      display: flex;
      gap: 1rem;
      opacity: 0;
      transition: all 0.3s ease;
    }

    .hover-button {
      width: 3rem;
      height: 3rem;
      border-radius: 50%;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s ease;
      border: none;
      outline: none;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .hover-button:hover {
      transform: scale(1.1);
      box-shadow: 0 3px 7px rgba(0,0,0,0.3);
      background-color: rgb(236, 72, 153);
      color: white;
    }

    .pin:hover .hover-buttons {
      opacity: 1;
    }

    .image-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.3);
      opacity: 0;
      transition: all 0.3s ease;
    }

    .pin:hover .image-overlay {
      opacity: 1;
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
      <!-- Anasayfa -->
      <a href="{{ url('/dashboard') }}" title="Anasayfa" class="hover:text-pink-500 text-gray-700">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M3 12l9-9 9 9v9a2 2 0 0 1-2 2h-5v-6H10v6H5a2 2 0 0 1-2-2v-9z"/>
        </svg>
      </a>

      <!-- Pin Oluştur (kalın) -->
      <a href="{{ url('/add') }}" title="Pin Oluştur" class="hover:text-pink-500 text-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
        </svg>
      </a>

      <!-- Kaydet -->
      <a href="{{ url('/saved') }}" title="Kaydet" class="hover:text-pink-500 text-gray-700">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17 3H7a2 2 0 0 0-2 2v16l7-5 7 5V5a2 2 0 0 0-2-2z"/>
        </svg>
      </a>

      <!-- To Do List (kalın) -->
      <a href="{{ url('/todo') }}" title="To Do List" class="hover:text-pink-500 text-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
      </a>
    </nav>
  </aside>

  <!-- Ana İçerik -->
  <div class="flex-1 flex flex-col">
    <!-- Üst Bar -->
    <header class="flex flex-col bg-white px-6 py-4 shadow relative">
      <header class="bg-white px-6 py-4 shadow relative z-50">
        <div class="w-full max-w-2xl mx-auto flex justify-between items-center">
          <div class="relative flex-1">
            <form action="{{ url('/dashboard') }}" method="GET" class="relative flex-1">
  <input
    type="text"
    name="q"
    id="searchInput"
    value="{{ request('q') }}"
    placeholder="Kategorilerde ara veya tıkla..."
    class="w-full px-4 py-2 bg-pink-50 border border-pink-300 text-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-pink-400 transition"
    autocomplete="off"
  >
</form>

            <!-- Kategori Kutusu -->
            <div id="kategoriKutusu" class="hidden absolute left-0 right-0 mt-2 bg-white border border-pink-200 rounded-lg p-4 shadow-lg transition-all transform origin-top">
              <div class="flex flex-wrap justify-start gap-3">
                <a href="{{ url('/dashboard?category=moda') }}" 
                   class="category-item bg-white text-pink-600 px-4 py-2 rounded-full text-sm cursor-pointer hover:bg-pink-100 transition {{ request()->query('category') == 'moda' ? 'bg-pink-500 text-white' : '' }}"
                   data-category="moda">Moda</a>
                <a href="{{ url('/dashboard?category=yemek') }}" 
                   class="category-item bg-white text-pink-600 px-4 py-2 rounded-full text-sm cursor-pointer hover:bg-pink-100 transition {{ request()->query('category') == 'yemek' ? 'bg-pink-500 text-white' : '' }}"
                   data-category="yemek">Yemek</a>
                <a href="{{ url('/dashboard?category=seyahat') }}" 
                   class="category-item bg-white text-pink-600 px-4 py-2 rounded-full text-sm cursor-pointer hover:bg-pink-100 transition {{ request()->query('category') == 'seyahat' ? 'bg-pink-500 text-white' : '' }}"
                   data-category="seyahat">Seyahat</a>
                <a href="{{ url('/dashboard?category=ev-dekoru') }}" 
                   class="category-item bg-white text-pink-600 px-4 py-2 rounded-full text-sm cursor-pointer hover:bg-pink-100 transition {{ request()->query('category') == 'ev-dekoru' ? 'bg-pink-500 text-white' : '' }}"
                   data-category="ev-dekoru">Ev Dekoru</a>
                <a href="{{ url('/dashboard?category=hayvanlar') }}" 
                   class="category-item bg-white text-pink-600 px-4 py-2 rounded-full text-sm cursor-pointer hover:bg-pink-100 transition {{ request()->query('category') == 'hayvanlar' ? 'bg-pink-500 text-white' : '' }}"
                   data-category="hayvanlar">Hayvanlar</a>
                <a href="{{ url('/dashboard?category=sanat') }}" 
                   class="category-item bg-white text-pink-600 px-4 py-2 rounded-full text-sm cursor-pointer hover:bg-pink-100 transition {{ request()->query('category') == 'sanat' ? 'bg-pink-500 text-white' : '' }}"
                   data-category="sanat">Sanat</a>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-4 ml-4">
            <span class="text-gray-600">{{ auth()->user()->name }}</span>
            <a href="{{ url('/profile') }}" class="block">
              <img src="https://i.pravatar.cc/150?img=10" alt="Profil"
                   class="w-10 h-10 rounded-full hover:ring-2 hover:ring-pink-400 transition">
            </a>
          </div>
        </div>
      </header>
    </header>

    <!-- Grid Görseller -->
    <main class="p-6">
      @if(isset($category))
        <h2 class="text-xl font-semibold mb-4 text-gray-700 capitalize">{{ $category }} Görselleri</h2>
        <div class="masonry-grid gap-4">
          @forelse($photos as $photo)
          <div class="masonry-item">
            <div class="relative rounded-lg shadow-md overflow-hidden group pin transition hover:shadow-xl bg-white">
              <img src="{{ $photo['src']['medium'] }}" class="w-full object-cover transition duration-300" alt="{{ $photo['alt'] }}">
              <div class="image-overlay"></div>
              <!-- Hover Butonları -->
              <div class="hover-buttons">
                @if(isset($photo['is_user_pin']) && $photo['is_user_pin'])
                  <button onclick="deletePin({{ $photo['pin_id'] }})" class="hover-button" title="Sil">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                @else
                  <button onclick="likeImage(this)" class="hover-button" title="Beğen">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                  <button onclick="saveImage(this, '{{ $photo['src']['medium'] }}', '{{ $photo['photographer'] }}', '{{ $photo['alt'] }}')" 
                          class="hover-button" title="Kaydet">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                  </button>
                  <button onclick="addToTodo('{{ $photo['src']['medium'] }}', '{{ $photo['photographer'] }}', '{{ $photo['alt'] }}')" 
                          class="hover-button" title="Todo'ya Ekle">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </button>
                @endif
              </div>
              <!-- Fotoğraf Bilgileri -->
              <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                <p class="text-white text-sm font-medium truncate">{{ $photo['photographer'] }}</p>
              </div>
            </div>
          </div>
          @empty
          <div class="col-span-full text-center py-10">
            <p class="text-gray-500">Bu kategoride görsel bulunamadı</p>
          </div>
          @endforelse
        </div>
        @elseif(isset($query))
    <h2 class="text-xl font-semibold mb-4 text-gray-700">"{{ $query }}" için sonuçlar</h2>
    <div class="masonry-grid gap-4">
        @forelse($photos as $photo)
        <div class="masonry-item">
            <div class="relative rounded-lg shadow-md overflow-hidden group pin transition hover:shadow-xl bg-white">
                <img src="{{ $photo['src']['medium'] }}" class="w-full object-cover transition duration-300" alt="{{ $photo['alt'] ?? '' }}">
                <div class="image-overlay"></div>
                <div class="hover-buttons">
                    <button onclick="likeImage(this)" class="hover-button" title="Beğen">
                        ❤️
                    </button>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                    <p class="text-white text-sm font-medium truncate">{{ $photo['photographer'] }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10">
            <p class="text-gray-500">Sonuç bulunamadı</p>
        </div>
        @endforelse
    </div>
      @else
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Popüler Görseller</h2>
        <div class="masonry-grid gap-4">
          @forelse($popularPhotos as $photo)
          <div class="masonry-item">
            <div class="relative rounded-lg shadow-md overflow-hidden group pin transition hover:shadow-xl bg-white">
              <img src="{{ $photo['src']['medium'] }}" class="w-full object-cover transition duration-300" alt="{{ $photo['alt'] }}">
              <div class="image-overlay"></div>
              <!-- Hover Butonları -->
              <div class="hover-buttons">
                @if(isset($photo['is_user_pin']) && $photo['is_user_pin'])
                  <button onclick="deletePin({{ $photo['pin_id'] }})" class="hover-button" title="Sil">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                @else
                  <button onclick="likeImage(this)" class="hover-button" title="Beğen">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                  <button onclick="saveImage(this, '{{ $photo['src']['medium'] }}', '{{ $photo['photographer'] }}', '{{ $photo['alt'] }}')" 
                          class="hover-button" title="Kaydet">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                  </button>
                  <button onclick="addToTodo('{{ $photo['src']['medium'] }}', '{{ $photo['photographer'] }}', '{{ $photo['alt'] }}')" 
                          class="hover-button" title="Todo'ya Ekle">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </button>
                @endif
              </div>
              <!-- Fotoğraf Bilgileri -->
              <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                <p class="text-white text-sm font-medium truncate">{{ $photo['photographer'] }}</p>
              </div>
            </div>
          </div>
          @empty
          <div class="col-span-full text-center py-10">
            <p class="text-gray-500">Görsel bulunamadı</p>
          </div>
          @endforelse
        </div>

        <h2 class="text-xl font-semibold mb-4 mt-8 text-gray-700">Trend Görseller</h2>
        <div class="masonry-grid gap-4">
          @forelse($trendingPhotos as $photo)
          <div class="masonry-item">
            <div class="relative rounded-lg shadow-md overflow-hidden group pin transition hover:shadow-xl bg-white">
              <img src="{{ $photo['src']['medium'] }}" class="w-full object-cover transition duration-300" alt="{{ $photo['alt'] }}">
              <div class="image-overlay"></div>
              <!-- Hover Butonları -->
              <div class="hover-buttons">
                <button onclick="likeImage(this)" class="hover-button" title="Beğen">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                </button>
                <button onclick="saveImage(this, '{{ $photo['src']['medium'] }}', '{{ $photo['photographer'] }}', '{{ $photo['alt'] }}')" 
                        class="hover-button" title="Kaydet">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                  </svg>
                </button>
                <button onclick="addToTodo('{{ $photo['src']['medium'] }}', '{{ $photo['photographer'] }}', '{{ $photo['alt'] }}')" 
                        class="hover-button" title="Todo'ya Ekle">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
              </div>
              <!-- Fotoğraf Bilgileri -->
              <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                <p class="text-white text-sm font-medium truncate">{{ $photo['photographer'] }}</p>
              </div>
            </div>
          </div>
          @empty
          <div class="col-span-full text-center py-10">
            <p class="text-gray-500">Görsel bulunamadı</p>
          </div>
          @endforelse
        </div>

        <h2 class="text-xl font-semibold mb-4 mt-8 text-gray-700">Doğa Görselleri</h2>
        <div class="masonry-grid gap-4">
          @forelse($naturePhotos as $photo)
          <div class="masonry-item">
            <div class="relative rounded-lg shadow-md overflow-hidden group pin transition hover:shadow-xl bg-white">
              <img src="{{ $photo['src']['medium'] }}" class="w-full object-cover transition duration-300" alt="{{ $photo['alt'] }}">
              <div class="image-overlay"></div>
              <!-- Hover Butonları -->
              <div class="hover-buttons">
                <button onclick="likeImage(this)" class="hover-button" title="Beğen">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                </button>
                <button onclick="saveImage(this, '{{ $photo['src']['medium'] }}', '{{ $photo['photographer'] }}', '{{ $photo['alt'] }}')" 
                        class="hover-button" title="Kaydet">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                  </svg>
                </button>
                <button onclick="addToTodo('{{ $photo['src']['medium'] }}', '{{ $photo['photographer'] }}', '{{ $photo['alt'] }}')" 
                        class="hover-button" title="Todo'ya Ekle">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
              </div>
              <!-- Fotoğraf Bilgileri -->
              <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                <p class="text-white text-sm font-medium truncate">{{ $photo['photographer'] }}</p>
              </div>
            </div>
          </div>
          @empty
          <div class="col-span-full text-center py-10">
            <p class="text-gray-500">Görsel bulunamadı</p>
          </div>
          @endforelse
        </div>
      @endif
    </main>
  </div>
</div>

<script>
function likeImage(button) {
    button.classList.toggle('bg-pink-500');
    button.classList.toggle('text-white');
}

function saveImage(button, imageUrl, photographer, alt) {
    // Disable button while saving
    button.disabled = true;
    button.innerHTML = '⏳ Kaydediliyor...';

    fetch('/save-image', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            image_url: imageUrl,
            photographer: photographer,
            alt: alt
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.innerHTML = '✅ Kaydedildi';
            button.classList.add('bg-green-500', 'text-white');
            button.classList.remove('hover:bg-pink-500');
            
            // Show success message
            showNotification(data.message, 'success');
        } else {
            button.innerHTML = '❌ ' + (data.message || 'Hata');
            button.classList.add('bg-red-500', 'text-white');
            button.classList.remove('hover:bg-pink-500');
            
            // Show error message
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        button.innerHTML = '❌ Hata';
        button.classList.add('bg-red-500', 'text-white');
        button.classList.remove('hover:bg-pink-500');
        
        // Show error message
        showNotification('Bir hata oluştu', 'error');
    })
    .finally(() => {
        // Re-enable button after 2 seconds
        setTimeout(() => {
            button.disabled = false;
            if (!button.classList.contains('bg-green-500')) {
                button.innerHTML = '📌 Kaydet';
                button.classList.remove('bg-red-500', 'text-white');
                button.classList.add('bg-white', 'text-gray-700', 'hover:bg-pink-500');
            }
        }, 2000);
    });
}

function addToTodo(imageUrl, photographer, alt) {
    const imageData = {
        url: imageUrl,
        photographer: photographer,
        alt: alt
    };
    
    // Görev ekleme sayfasına yönlendir
    window.location.href = '/todo/add-with-image?' + new URLSearchParams({
        image_url: imageUrl,
        photographer: photographer,
        alt: alt
    }).toString();
}

function deletePin(pinId) {
    if (!confirm('Bu pini silmek istediğinizden emin misiniz?')) return;

    fetch(/pins/${pinId}, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to refresh the pins
            window.location.reload();
        } else {
            alert('Pin silinirken bir hata oluştu: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Pin silinirken bir hata oluştu. Lütfen tekrar deneyin.');
    });
}

// Add notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } transition-opacity duration-300;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Update the category menu behavior
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryBox = document.getElementById('kategoriKutusu');
    const categoryItems = document.querySelectorAll('.category-item');
    let isMouseOverCategoryBox = false;

    // Arama çubuğuna tıklandığında kategori kutusunu göster
    searchInput.addEventListener('focus', () => {
        categoryBox.classList.remove('hidden');
    });

    // Arama işlevi
    searchInput.addEventListener('input', (e) => {
        const searchText = e.target.value.toLowerCase();
        
        categoryItems.forEach(item => {
            const categoryText = item.textContent.toLowerCase();
            if (categoryText.includes(searchText)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });

        // Eğer arama çubuğuna yazılan metin bir kategori ile tam eşleşirse, o kategoriye yönlendir
        categoryItems.forEach(item => {
            if (item.textContent.toLowerCase() === searchText) {
                window.location.href = item.href;
            }
        });
    });

    // Enter tuşuna basıldığında
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            const searchText = e.target.value.toLowerCase();
            // İlk eşleşen kategoriyi bul ve yönlendir
            const matchingCategory = Array.from(categoryItems).find(item => 
                item.textContent.toLowerCase().includes(searchText)
            );
            if (matchingCategory) {
                window.location.href = matchingCategory.href;
            }
        }
    });

    // Kategori kutusu hover yönetimi
    categoryBox.addEventListener('mouseenter', () => {
        isMouseOverCategoryBox = true;
    });

    categoryBox.addEventListener('mouseleave', () => {
        isMouseOverCategoryBox = false;
        if (!searchInput.matches(':focus')) {
            categoryBox.classList.add('hidden');
        }
    });

    // Sayfa herhangi bir yerine tıklandığında kategori kutusunu kapat
    document.addEventListener('click', (e) => {
        if (!categoryBox.contains(e.target) && e.target !== searchInput && !isMouseOverCategoryBox) {
            categoryBox.classList.add('hidden');
        }
    });

    // Kategorilere tıklandığında animasyon efekti
    categoryItems.forEach(item => {
        item.addEventListener('click', function(e) {
            this.classList.add('scale-95', 'bg-pink-500', 'text-white');
            setTimeout(() => {
                this.classList.remove('scale-95');
            }, 150);
        });
    });
});
</script>

</body>
</html>   