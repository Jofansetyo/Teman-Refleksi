<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Teman Refleksi')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <symbol id="boat" viewBox="0 0 120 120">
            <path d="M60 8V82H18Z" fill="#fff"/><path d="M66 22V82H100Z" fill="#CFE9F6"/><path d="M12 90H108L96 108H24Z" fill="#F3D58B"/>
        </symbol>
    </svg>
    <main>@yield('content')</main>
</body>
</html>
