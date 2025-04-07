<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white shadow-md rounded p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-red-600">Giriş Yap</h2>

        <form method="POST" action="{{ route('login.perform') }}">
    @csrf
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">E-posta</label>
                <input type="email" name="email" class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-red-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700">Şifre</label>
                <input type="password" name="password" class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-red-500">
            </div>

            <button type="submit" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">Giriş Yap</button>

        </form>
@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <div class="mt-4 text-center">
            <a href="{{route('register')}}" class="text-sm text-red-500 hover:underline">Hesabın yok mu? Kayıt Ol</a>
        </div>
    </div>
</body>
</html>
