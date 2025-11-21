<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Görsel Pinle'ye Kayıt Ol</title>
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
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Görsel Pinle'ye Hoş Geldiniz</h2>
        <p class="text-gray-600 text-sm mb-6">Denemek için yeni fikirler bulun</p>

        <!-- Hata Mesajları -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4 text-left text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form id="registerForm" method="POST" action="{{ route('register.store') }}" class="space-y-4 text-left">
            @csrf

            <!-- Ad Soyad -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">Ad Soyad</label>
                <input type="text" name="name" id="name" placeholder="Ad Soyad" required
                       class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring focus:border-pink-400">
            </div>

            <!-- E-posta -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">E-posta</label>
                <input type="email" name="email" id="email" placeholder="E-posta" required
                       class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring focus:border-pink-400">
            </div>

            <!-- Parola -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">Parola</label>
                <input type="password" name="password" id="password" placeholder="Bir parola oluşturun" required
                       class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring focus:border-pink-400">
            </div>

            <!-- Doğum Tarihi -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">Doğum Tarihi</label>
                <input type="date" name="birth_date" id="birth_date" required
                       class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring focus:border-pink-400">
            </div>

            <!-- Devam Et -->
            <button type="submit" id="submitBtn"
                    class="w-full bg-pink-500 text-white py-2 rounded-lg hover:bg-pink-600 transition-colors font-semibold">
                Devam Et
            </button>
        </form>

        <!-- VEYA -->
        <div class="my-4 text-sm text-gray-500 font-semibold text-center">VEYA</div>

        <!-- Gmail ile Devam Et -->
        <a href="#" id="googleRegister"
           class="flex items-center justify-center w-full border border-pink-500 bg-pink-100 text-pink-600 font-medium py-2 rounded-lg hover:bg-pink-200 transition-colors">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5 mr-2">
            Gmail ile Devam Et
        </a>

        <!-- Giriş Linki -->
        <p class="text-sm mt-6 text-gray-600 text-center">
            Zaten hesabın var mı?
            <a href="{{ route('login') }}" class="text-pink-600 hover:underline">Giriş Yap</a>
        </p>
    </div>

    <script>
        // Form gönderimi öncesi Firebase ile kayıt
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Firebase'de kullanıcı oluştur
            firebase.auth().createUserWithEmailAndPassword(email, password)
                .then((userCredential) => {
                    // Firebase'de başarılı kayıt
                    const user = userCredential.user;
                    
                    // Firebase UID'yi form verilerine ekle
                    const firebaseUidInput = document.createElement('input');
                    firebaseUidInput.type = 'hidden';
                    firebaseUidInput.name = 'firebase_uid';
                    firebaseUidInput.value = user.uid;
                    this.appendChild(firebaseUidInput);
                    
                    // Formu gönder
                    this.submit();
                })
                .catch((error) => {
                    console.error("Kayıt hatası:", error);
                    alert("Kayıt sırasında bir hata oluştu: " + error.message);
                });
        });

        // Google ile kayıt
        document.getElementById('googleRegister').addEventListener('click', function(e) {
            e.preventDefault();
            
            const provider = new firebase.auth.GoogleAuthProvider();
            firebase.auth().signInWithPopup(provider)
                .then((result) => {
                    const user = result.user;
                    const idToken = result.credential.idToken;
                    
                    // Form gönderimi için gerekli bilgileri hazırla
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('register.store') }}';
                    form.style.display = 'none';
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    
                    const nameInput = document.createElement('input');
                    nameInput.type = 'hidden';
                    nameInput.name = 'name';
                    nameInput.value = user.displayName;
                    
                    const emailInput = document.createElement('input');
                    emailInput.type = 'hidden';
                    emailInput.name = 'email';
                    emailInput.value = user.email;
                    
                    const uidInput = document.createElement('input');
                    uidInput.type = 'hidden';
                    uidInput.name = 'firebase_uid';
                    uidInput.value = user.uid;
                    
                    // Rastgele şifre oluştur (kullanıcı Google ile giriş yapacak)
                    const passwordInput = document.createElement('input');
                    passwordInput.type = 'hidden';
                    passwordInput.name = 'password';
                    passwordInput.value = Math.random().toString(36).slice(-10);
                    
                    // Bugünün tarihini kullan
                    const birthDateInput = document.createElement('input');
                    birthDateInput.type = 'hidden';
                    birthDateInput.name = 'birth_date';
                    birthDateInput.value = new Date().toISOString().split('T')[0];
                    
                    form.appendChild(csrfInput);
                    form.appendChild(nameInput);
                    form.appendChild(emailInput);
                    form.appendChild(passwordInput);
                    form.appendChild(birthDateInput);
                    form.appendChild(uidInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                })
                .catch((error) => {
                    console.error("Google kayıt hatası:", error);
                    alert("Google ile kayıt sırasında bir hata oluştu");
                });
        });
    </script>
</body>
</html>
