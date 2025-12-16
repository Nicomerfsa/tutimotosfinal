<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Control Stock</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Añade Alpine.js para animaciones --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Estilos personalizados para animaciones --}}
    <style>
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); }
        }
        
        @keyframes progressBar {
            from { width: 100%; }
            to { width: 0%; }
        }
        
        .alert-slide-in {
            animation: slideInRight 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }
        
        .alert-fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }
    </style>
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

    {{-- CONTENEDOR DE ALERTAS FLOTANTES --}}
    <div class="fixed top-6 right-6 z-50 space-y-4 w-96 max-w-full" 
         x-data="{
             alerts: [],
             addAlert(type, message) {
                 const id = Date.now() + Math.random();
                 this.alerts.push({ id, type, message });
                 setTimeout(() => this.removeAlert(id), 5000);
             },
             removeAlert(id) {
                 const alert = this.$el.querySelector(`[data-alert-id='${id}']`);
                 if (alert) {
                     alert.classList.remove('alert-slide-in');
                     alert.classList.add('alert-fade-out');
                     setTimeout(() => {
                         this.alerts = this.alerts.filter(a => a.id !== id);
                     }, 500);
                 }
             }
         }"
         @alert-success.window="addAlert('success', $event.detail.message)"
         @alert-info.window="addAlert('info', $event.detail.message)"
         @alert-error.window="addAlert('error', $event.detail.message)"
         x-init="
             // Escuchar alertas de Laravel (sesiones flash)
             @if(session('success'))
                 addAlert('success', '{{ session('success') }}');
             @endif
             @if(session('info'))
                 addAlert('info', '{{ session('info') }}');
             @endif
             @if($errors->any())
                 @foreach($errors->all() as $error)
                     addAlert('error', '{{ $error }}');
                 @endforeach
             @endif
         ">
        
        {{-- Plantilla de alerta --}}
        <template x-for="alert in alerts" :key="alert.id">
            <div :data-alert-id="alert.id"
                 class="alert-slide-in rounded-xl shadow-2xl border-l-4 backdrop-blur-sm bg-white/95 p-5 transform transition-all duration-300 hover:scale-[1.02] cursor-pointer"
                 :class="{
                     'border-green-500': alert.type === 'success',
                     'border-blue-500': alert.type === 'info',
                     'border-red-500': alert.type === 'error'
                 }"
                 @click="removeAlert(alert.id)">
                
                <div class="flex items-start gap-3">
                    {{-- Icono --}}
                    <div class="flex-shrink-0 mt-0.5" x-show="alert.type === 'success'">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-shrink-0 mt-0.5" x-show="alert.type === 'info'">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-shrink-0 mt-0.5" x-show="alert.type === 'error'">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    
                    {{-- Contenido --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 mb-1"
                           :class="{
                               'text-green-800': alert.type === 'success',
                               'text-blue-800': alert.type === 'info',
                               'text-red-800': alert.type === 'error'
                           }">
                            <span x-text="alert.type === 'success' ? '¡Éxito!' : alert.type === 'info' ? 'Información' : 'Error'"></span>
                        </p>
                        <p class="text-sm text-gray-600" x-text="alert.message"></p>
                    </div>
                    
                    {{-- Botón cerrar --}}
                    <button @click.stop="removeAlert(alert.id)"
                            class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                {{-- Barra de progreso --}}
                <div class="mt-3 h-1 rounded-full overflow-hidden bg-gray-200">
                    <div class="h-full rounded-full transition-all duration-5000"
                         :class="{
                             'bg-green-500': alert.type === 'success',
                             'bg-blue-500': alert.type === 'info',
                             'bg-red-500': alert.type === 'error'
                         }"
                         x-init="setTimeout(() => $el.style.width = '0%', 100)"></div>
                </div>
            </div>
        </template>
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

    {{-- Script para alertas personalizadas desde JavaScript --}}
    <script>
        // Función para mostrar alertas desde cualquier parte del código JavaScript
        window.showAlert = function(type, message) {
            window.dispatchEvent(new CustomEvent(`alert-${type}`, {
                detail: { message }
            }));
        };

        // Ejemplo de uso desde cualquier archivo JavaScript:
        // showAlert('success', '¡Operación completada con éxito!');
        // showAlert('error', 'Ocurrió un error inesperado');
        // showAlert('info', 'Esta es una notificación informativa');
    </script>

</body>
</html>