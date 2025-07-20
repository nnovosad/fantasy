<html>
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-FGPR0QF50T"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-FGPR0QF50T');
    </script>

    <title>@yield('title')</title>
    @livewireStyles

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    @vite('resources/css/app.css')
</head>
<body>

<header style="background-color: #f8fafc; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <div style="max-width: 1200px; margin: 0 0 0 60px; display: flex; align-items: center; justify-content: space-between;">
        <nav>
            <a href="{{ url('/') }}" style="margin-right: 1rem; text-decoration: none; color: #4a5568;">Home</a>
            <a href="{{ url('/stats') }}" style="margin-right: 1rem; text-decoration: none; color: #4a5568;">Statistics</a>
            <a href="{{ url('/assistant-new-season') }}" style="text-decoration: none; color: #4a5568;">Assistant</a>
        </nav>
    </div>
</header>

@yield('content')

@livewireScripts
</body>
</html>
