<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Control Stock</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    {{-- NAVEGACIÓN: Solo se muestra si el usuario está logueado --}}
    @if(Auth::check())
    <nav class="bg-gray-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="font-bold text-xl text-[rgb(32,95,221)]">Control de Stock</span>
                    
                    <div class="hidden md:flex ml-10 space-x-4">
                        <a href="/dashboard" class="hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        <a href="/productos" class="hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">Productos</a>
                        <a href="/stock" class="hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">Stock</a>
                        <a href="/movimientos" class="hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">Movimientos</a>
                        <a href="/clientes" class="hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">Clientes</a>
                        <a href="/cotizaciones" class="hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">Cotizaciones</a>
                        <a href="/facturas" class="hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">Facturas</a>
                    </div>
                </div>

                <div class="flex items-center">
                    <a href="/logout" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Cerrar Sesión
                    </a>
                    <form id="logout-form" action="/logout" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endif

    {{-- MENSAJES DE ALERTA (Success, Info, Errors) --}}
    <div class="max-w-7xl mx-auto px-4 mt-4">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-sm" role="alert">
                <p class="font-bold">¡Éxito!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 shadow-sm" role="alert">
                <p class="font-bold">Información</p>
                <p>{{ session('info') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow-sm" role="alert">
                <p class="font-bold">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc ml-5 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- CONTENIDO PRINCIPAL --}}
    {{-- Si NO hay usuario logueado (pantalla login), el contenido ocupa todo el ancho sin márgenes extra --}}
    @if(Auth::check())
        <div class="max-w-7xl mx-auto px-4 py-6">
            @yield('content')
        </div>
    @else
        {{-- Para el login, dejamos que login.blade.php controle su propio layout --}}
        @yield('content')
    @endif

</body>
</html>