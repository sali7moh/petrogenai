<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PetrogenAI - AI Assistant Platform')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-CwC-9LoA.css') }}">
    
    @stack('styles')
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    @yield('content')
    
    <script src="{{ asset('build/assets/app-RowJdFww.js') }}"></script>
    @stack('scripts')
</body>
</html>
