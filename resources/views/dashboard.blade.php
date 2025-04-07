<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-green-600">🎉 Giriş Başarılı, Hoş Geldin {{ auth()->user()->name }}!</h1>
        <p class="mt-4 text-gray-600">Artık dashboard sayfasındasın.</p>
    </div>
</body>
</html>
