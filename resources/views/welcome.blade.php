<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Görsel Pinle'ye Giriş</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/10.8.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.8.1/firebase-auth-compat.js"></script>

    <script>
        // Firebase yapılandırması
        const firebaseConfig = {
            apiKey: "AIzaSyAV1zo2X6JVzkuqpgavBpNboFnD1IXowP4",
            authDomain: "gorselpinleme.firebaseapp.com",
            projectId: "gorselpinleme",
            storageBucket: "gorselpinleme.firebasestorage.app",
            messagingSenderId: "406563332172",
            appId: "1:406563332172:web:79f3016f77eb9e78b854e9",
            measurementId: "G-KDVQG3DL02"
        };

        // Firebase'i başlat
        firebase.initializeApp(firebaseConfig);
    </script>
</head>
<body class="bg-pink-50 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md text-center">
        <!-- Logo -->
        <img src="{{ asset('images/pinterest-5-512.png') }}" alt="Logo" class="mx-auto w-12 h-12 mb-4">

        <!-- Başlık -->
        <h2 class="text-2xl font-bold mb-1 text-gray-800">Görsel Pinle'ye Hoş Geldiniz</h2>
        <p class="text-gray-600 text-sm mb-6">Denemek için yeni fikirler bulun</p>

        <!-- Hata Mesajları -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4 text-left text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Giriş Formu -->
        <form method="POST" action="{{ route('login.perform') }}" class="space-y-4">
            @csrf
            <!-- E-posta -->
            <input type="email" name="email" placeholder="E-posta" required
                   class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-pink-400">

            <!-- Parola -->
            <input type="password" name="password" placeholder="Parola" required
                   class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-pink-400">

            <!-- Şifreyi unuttun -->
            <div class="text-left text-sm text-gray-600">
                <a href="#" class="hover:underline"></a>
            </div>

            <!-- Giriş Butonu -->
            <button type="submit"
                    class="w-full bg-pink-500 text-white py-2 rounded hover:bg-pink-600 transition font-semibold">
                Oturum Aç
            </button>
        </form>

        <!-- Ayraç -->
        <div class="my-4 text-gray-500 text-sm">VEYA</div>

        <!-- Gmail ile Devam -->
        <button id="googleLogin" class="w-full bg-pink-100 text-pink-600 border border-pink-500 py-2 rounded flex items-center justify-center gap-2 hover:bg-pink-200 transition font-semibold">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
            Gmail ile Devam Et
        </button>

        <!-- Alt bağlantı -->
        <div class="mt-6 text-sm text-gray-700">
            Pinterest'te yeni misiniz?
            <a href="{{ route('register') }}" class="text-pink-600 hover:underline font-medium">Kaydol</a>
        </div>
    </div>

    <script>
        // Google ile giriş
        document.getElementById('googleLogin').addEventListener('click', function(e) {
            e.preventDefault();
            
            const provider = new firebase.auth.GoogleAuthProvider();
            console.log('Google sign-in başlatılıyor...');
            
            firebase.auth().signInWithPopup(provider)
                .then((result) => {
                    console.log('Google sign-in başarılı:', result.user.email);
                    
                    // Backend'e gönderilecek verileri hazırla
                    const data = {
                        firebase_uid: result.user.uid,
                        email: result.user.email,
                        name: result.user.displayName
                    };
                    
                    console.log('Backend\'e gönderilecek veriler:', data);
                    
                    // CSRF token'ı al
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // Backend'e istek at
                    return fetch('{{ route('login.google') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                })
                .then(response => {
                    console.log('Backend yanıt durumu:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Backend yanıtı:', data);
                    
                    if (data.success) {
                        window.location.href = data.redirect || '{{ route('dashboard') }}';
                    } else if (data.action === 'register') {
                        window.location.href = data.redirect || '{{ route('register') }}';
                    } else {
                        throw new Error(data.error || 'Bilinmeyen bir hata oluştu');
                    }
                })
                .catch(error => {
                    console.error('Hata detayı:', error);
                    alert('Google ile giriş yapılırken bir hata oluştu: ' + error.message);
                });
        });
    </script>
</body>
</html>
