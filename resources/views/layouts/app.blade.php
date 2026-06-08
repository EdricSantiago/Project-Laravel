<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bank Untar')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'hanken': ['"Hanken Grotesk"', 'sans-serif'],
                    },
                    colors: {
                        'bank-red': '#8B1A1A',
                        'bank-red-light': '#C62828',
                        'bank-bg': '#f5f0ee',
                        'bank-card': '#ffffff',
                        'bank-border': '#e8e0dc',
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Hanken Grotesk', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-bank-bg min-h-screen">
    @yield('content')
    @stack('scripts')
</body>
</html>
