<html>
<head>
    <title>@yield('title')</title>
    @livewireStyles

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>
<body>
@yield('content')

@livewireScripts
</body>
</html>
