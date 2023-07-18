<html>
<head>
    <title>@yield('title')</title>
    @livewireStyles

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    @vite('resources/css/app.css')
</head>
<body>
@yield('content')

@livewireScripts
</body>
</html>
