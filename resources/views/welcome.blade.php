<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fantasy Sandbox</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded w-1/2">
        <p class="font-semibold text-xl mb-4">Привет</p>
        <p class="mb-4"><a href="/stats" class="text-blue-500 underline">Здесь</a> можно перед фэнтези сезоном 24/25 на <a href="https://www.sports.ru/fantasy/football/" class="text-blue-500 underline" target="_blank">Sports.ru</a> посмотреть статистику за прошлый сезон 23/24.</p>
    </div>
    </body>
</html>
