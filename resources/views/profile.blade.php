<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil - Görsel Pinle</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <div class="flex-1 bg-gray-50">
        <!-- Üst Bar -->
        <header class="bg-white px-6 py-3 shadow flex items-center justify-between">
            <input type="text"
                   placeholder="Ara"
                   class="w-1/2 px-4 py-2 bg-pink-50 border border-pink-300 text-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-pink-400 transition">
            
            <div class="flex items-center gap-4">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <img src="https://i.pravatar.cc/150?img=10" alt="Profil" class="w-10 h-10 rounded-full">
            </div>
        </header>

        <!-- Profil İçeriği -->
        <main class="p-6">
            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Profil Başlığı -->
                <div class="relative h-48 bg-gradient-to-r from-pink-400 to-pink-600">
                    <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2">
                        <div class="relative group">
                            <img src="https://i.pravatar.cc/150?img=10" alt="Profil" 
                                 class="w-32 h-32 rounded-full border-4 border-white shadow-lg transition-transform group-hover:scale-105">
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <label for="profile_photo" class="cursor-pointer">
                                    <div class="bg-black bg-opacity-50 rounded-full w-32 h-32 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                </label>
                                <input type="file" id="profile_photo" name="avatar" class="hidden" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profil Bilgileri -->
                <div class="pt-20 px-6 pb-6 text-center">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">{{ auth()->user()->name }}</h1>
                        <p class="text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <button onclick="openEditModal()" 
                            class="px-6 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600 transition">
                        Profili Düzenle
                    </button>
                </div>

                <!-- Profil Düzenleme Modal -->
                <div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
                        <h2 class="text-2xl font-bold mb-6 text-gray-800">Profili Düzenle</h2>
                        
                        <form id="profileEditForm" class="space-y-4">
                            @csrf
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad</label>
                                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Yeni Şifre (Opsiyonel)</label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent">
                            </div>
                            
                            <div class="flex justify-between pt-4">
                                <button type="button" onclick="deleteAccount()"
                                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                    Hesabı Sil
                                </button>
                                <div class="space-x-2">
                                    <button type="button" onclick="closeEditModal()"
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                                        İptal
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                                        Kaydet
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Profil Sekmeler -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex gap-6">
                        <button onclick="switchTab('pins')" 
                                class="px-4 py-2 text-pink-500 border-b-2 border-pink-500">
                            Pinler
                        </button>
                    </nav>
                </div>

                <!-- İçerik Alanı -->
                <div id="profileContent" class="min-h-[300px] px-6 pb-6">
                    <!-- Burası JavaScript ile doldurulacak -->
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function switchTab(tab) {
    const content = document.getElementById('profileContent');
    content.innerHTML = `<div class="text-center py-10 text-gray-500">Yükleniyor...</div>`;
    
    fetch(`/user-pins/{{ auth()->id() }}`)
        .then(response => response.json())
        .then(pins => {
            if (pins.length === 0) {
                content.innerHTML = `
                    <div class="text-center py-10">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-700 mb-2">Henüz Pin Eklenmemiş</h2>
                        <p class="text-gray-500 mb-4">Yeni pin ekleyerek koleksiyonunuzu oluşturmaya başlayın!</p>
                        <a href="{{ url('/add') }}" 
                           class="inline-block px-6 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600 transition">
                            Pin Oluştur
                        </a>
                    </div>`;
            } else {
                content.innerHTML = `
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        ${pins.map(pin => `
                            <div class="relative group" data-pin-id="${pin.id}">
                                <img src="${pin.image_url}" 
                                     alt="${pin.title}"
                                     class="w-full h-64 object-cover rounded-lg shadow-md group-hover:opacity-75 transition">
                                <!-- Silme Butonu -->
                                <button onclick="deletePin(${pin.id}, event)" 
                                        class="absolute top-2 left-2 bg-red-500 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 to-transparent rounded-b-lg">
                                    <h3 class="text-white font-medium truncate">${pin.title}</h3>
                                    <p class="text-white/80 text-sm">${pin.category}</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="text-center py-10 text-red-500">
                    Pinler yüklenirken bir hata oluştu. Lütfen sayfayı yenileyin.
                </div>`;
        });
}

function deletePin(pinId, event) {
    event.preventDefault();
    
    if (confirm('Bu pini silmek istediğinizden emin misiniz?')) {
        fetch(`/pins/${pinId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pinElement = document.querySelector(`[data-pin-id="${pinId}"]`);
                if (pinElement) {
                    pinElement.remove();
                    
                    const remainingPins = document.querySelectorAll('[data-pin-id]');
                    if (remainingPins.length === 0) {
                        document.getElementById('profileContent').innerHTML = `
                            <div class="text-center py-10">
                                <div class="text-gray-400 mb-4">
                                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-700 mb-2">Henüz Pin Eklenmemiş</h2>
                                <p class="text-gray-500 mb-4">Yeni pin ekleyerek koleksiyonunuzu oluşturmaya başlayın!</p>
                                <a href="{{ url('/add') }}" 
                                   class="inline-block px-6 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600 transition">
                                    Pin Oluştur
                                </a>
                            </div>`;
                    }
                }
            } else {
                alert('Pin silinirken bir hata oluştu: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Pin silinirken bir hata oluştu. Lütfen tekrar deneyin.');
        });
    }
}

function openEditModal() {
    document.getElementById('editProfileModal').classList.remove('hidden');
    document.getElementById('editProfileModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editProfileModal').classList.add('hidden');
    document.getElementById('editProfileModal').classList.remove('flex');
}

// Profil düzenleme formunu gönder
document.getElementById('profileEditForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/profile', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Profil başarıyla güncellendi', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification(data.message || 'Bir hata oluştu', 'error');
        }
    })
    .catch(error => {
        showNotification('Bir hata oluştu', 'error');
    });
});

function deleteAccount() {
    if (confirm('Hesabınızı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')) {
        fetch('/profile', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Hesabınız başarıyla silindi', 'success');
                setTimeout(() => window.location.href = '/login', 1000);
            } else {
                showNotification(data.message || 'Bir hata oluştu', 'error');
            }
        })
        .catch(error => {
            showNotification('Bir hata oluştu', 'error');
        });
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } transition-opacity duration-300`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Sayfa yüklendiğinde pinleri göster
document.addEventListener('DOMContentLoaded', function() {
    switchTab('pins');
});
</script>

</body>
</html> 