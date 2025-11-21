<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pin Oluştur - Görsel Pinle</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex">
    <!-- Sol Menü -->
    <aside class="w-20 bg-white h-screen py-6 px-2 flex flex-col items-center shadow">
        <!-- Logo -->
        <div class="mb-10">
            <img src="{{ asset('images/pinterest-5-512.png') }}" alt="Logo" class="w-8 h-8">
        </div>

        <!-- Menü -->
        <nav class="flex flex-col items-center space-y-10">
            <!-- Anasayfa -->
            <a href="{{ url('/dashboard') }}" title="Anasayfa" class="hover:text-pink-500 text-gray-700">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 12l9-9 9 9v9a2 2 0 0 1-2 2h-5v-6H10v6H5a2 2 0 0 1-2-2v-9z"/>
                </svg>
            </a>

            <!-- Pin Oluştur (aktif) -->
            <a href="{{ url('/add') }}" title="Pin Oluştur" class="text-pink-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 13H13v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
            </a>

            <!-- Kaydet -->
            <a href="{{ url('/saved') }}" title="Kaydet" class="hover:text-pink-500 text-gray-700">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17 3H7a2 2 0 0 0-2 2v16l7-5 7 5V5a2 2 0 0 0-2-2z"/>
                </svg>
            </a>

            <!-- To Do List -->
            <a href="{{ url('/todo') }}" title="To Do List" class="hover:text-pink-500 text-gray-700">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/>
                </svg>
            </a>
        </nav>
    </aside>

    <!-- Sağ İçerik -->
    <div class="flex-1 flex flex-col">

        <!-- Üst Bar -->
        <header class="flex justify-between items-center bg-white px-6 py-3 shadow">
            <input type="text"
                   placeholder="Ara"
                   class="w-1/2 mx-auto px-4 py-2 bg-pink-50 border border-pink-300 text-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-pink-400 transition"
            >
            <div class="flex items-center gap-4">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <a href="{{ url('/profile') }}" class="block">
                    <img src="https://i.pravatar.cc/150?img=10}" alt="Profil"
                         class="w-10 h-10 rounded-full hover:ring-2 hover:ring-pink-400 transition">
                </a>
            </div>
        </header>

        <!-- Form Alanı -->
        <main class="flex-1 flex justify-center items-center px-6 py-10">
            <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl p-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Pin Oluştur</h1>

                <form id="pinForm" class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    @csrf

                    <!-- Sol Kutu: Görsel -->
                    <div class="border-2 border-dashed border-pink-300 rounded-xl flex flex-col justify-center items-center p-8 hover:bg-pink-50 transition duration-300 relative">
                        <div id="imagePreview" class="hidden w-full h-full absolute inset-0 bg-center bg-cover rounded-xl">
                            <button type="button" onclick="removeImage()" class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <label for="image" class="cursor-pointer text-center" id="uploadLabel">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto w-12 h-12 text-pink-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4m0 0l4-4m-4 4v12" />
                            </svg>
                            <p class="text-pink-500 font-medium">Görsel Yüklemek İçin Tıklayın</p>
                            <input type="file" name="image" id="image" class="hidden" accept="image/*" required>
                        </label>
                    </div>

                    <!-- Sağ Kutu: Başlık, Açıklama, Kategori -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Başlık</label>
                            <input type="text" name="title" placeholder="Harika bir başlık..." required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                            <textarea name="description" rows="4" placeholder="Açıklama yazın..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400">
                                <option value="" disabled selected>Kategori seçin</option>
                                <option value="moda">Moda</option>
                                <option value="dekor">Dekor</option>
                                <option value="yemek">Yemek</option>
                                <option value="diy">DIY</option>
                            </select>
                        </div>

                        <div class="text-right pt-2">
                            <button type="submit"
                                    class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition duration-300 font-medium">
                                Kaydet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.style.backgroundImage = `url(${e.target.result})`;
            preview.classList.remove('hidden');
            document.getElementById('uploadLabel').classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
});

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('uploadLabel').classList.remove('hidden');
}

document.getElementById('pinForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/pins', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (data.success) {
            alert('Pin başarıyla oluşturuldu!');
            // Clear form
            this.reset();
            removeImage();
        } else {
            alert('Bir hata oluştu: ' + (data.message || 'Bilinmeyen hata'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
    }
});
</script>

</body>
</html>
