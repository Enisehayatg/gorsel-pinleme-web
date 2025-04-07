<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white shadow-md rounded p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-red-600">Kayıt Ol</h2>

        <form method="POST" action="{{ route('register.store') }}">

            @csrf

            <div class="mb-4">
                <label class="block text-gray-700">Ad Soyad</label>
                <input type="text" name="name" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-red-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">E-posta</label>
                <input type="email" name="email" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-red-500">
            </div>
            <div class="mb-4">
    <label class="block text-gray-700">Telefon</label>
    <input type="text" name="phone" class="w-full border px-3 py-2 rounded">
</div>


            <div class="mb-6">
                <label class="block text-gray-700">Şifre</label>
                <input type="password" name="password" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-red-500">
            </div>

            <button type="submit" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">Kayıt Ol</button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-red-500 hover:underline">Zaten hesabın var mı? Giriş Yap</a>
        </div>
    </div>
</body>
</html>
