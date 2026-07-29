<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karsis - 2026</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('main/img/logo-smk.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</head>

<body class="h-full bg-slate-50 overflow-hidden" x-data="{ mobileMenuOpen: false, showModal: false, showAlert: false }">

    <div class="flex h-dvh flex-col overflow-hidden">

        @include('layouts.navbar')

        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>

        {{-- <div class="flex-shrink-0">
            @include('layouts.footer')
        </div> --}}

    </div>
    <script>
        function refreshIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        document.addEventListener("DOMContentLoaded", refreshIcons);
    </script>
</body>

</html>
