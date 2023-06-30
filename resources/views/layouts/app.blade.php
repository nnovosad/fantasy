<html>
<head>
    <title>@yield('title')</title>
    @livewireStyles

    @vite('resources/css/app.css')
</head>
<body>
@yield('content')

@livewireScripts
</body>
</html>
