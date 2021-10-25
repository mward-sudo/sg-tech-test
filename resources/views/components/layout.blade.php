<!doctype html>
<html>
<head>
    <title>{{ $title }} SG Technical Test</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="{{ asset('js/app.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    {{ $head ?? '' }}
</head>

<body>
<div class="container mx-auto">
    {{ $slot }}
</div>
</body>
</html>
