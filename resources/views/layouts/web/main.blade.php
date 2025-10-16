<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') - UndiIn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Temukan properti impian Anda dengan mudah." name="description" />
    <meta content="UndiIn" name="author" />

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    <nav class="bg-white/80 backdrop-blur-lg sticky top-0 z-50 shadow-sm border-b border-slate-200/50">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center py-4">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-house-chimney-window text-blue-600"></i>
                    UndiIn
                </a>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('buyer.home') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors">Home</a>
                    <a href="{{ route('buyer.properties.index') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors">Daftar Properti</a>
                    <a href="{{ route('buyer.orders.index') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors">Orders Saya</a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="#" class="px-5 py-2.5 font-semibold transition-all duration-300 transform hover:scale-105">
                        <i class="fa-solid fa-user-circle mr-2"></i>
                        {{ auth()->user()->name }}
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer id="kontak" class="bg-slate-900 text-slate-300">
        <div class="container mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <h3 class="text-xl font-bold text-white mb-4">UndiIn</h3>
                    <p class="max-w-md">Platform terpercaya untuk menemukan, membeli, dan menjual properti impian Anda di seluruh Indonesia.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Navigasi</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Beranda</a></li>
                        <li><a href="#featured" class="hover:text-white">Properti</a></li>
                        <li><a href="#keunggulan" class="hover:text-white">Tentang Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Hubungi Kami</h4>
                    <p>Email: contact@undi.in</p>
                    <p>Telepon: (021) 1234 5678</p>
                </div>
            </div>
            <div class="border-t border-slate-700 mt-8 pt-8 text-center">
                <p>&copy; 2025 UndiIn | All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>