@extends('layout.base')

@section('title', 'Login')

@section('content')
<div class="min-h-screen bg-[rgb(101,101,107)] flex items-center justify-center px-4 py-12">
    
    <div class="max-w-md w-full bg-white rounded-xl shadow-2xl overflow-hidden">
        

        <div class="bg-gray-900 p-6 text-center">
            <h2 class="text-2xl font-bold text-white">Bienvenido a Tutinventario</h2>
            <p class="text-gray-400 mt-2">Ingresa tus credenciales para acceder</p>
        </div>

        <div class="p-8">
            <form method="POST" action="/login">
                @csrf
                
                {{-- Campo Email --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email:</label>
                    <div class="relative">
                        {{-- Icono de usuario/mail (SVG) --}}
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent outline-none transition-all"
                            placeholder="admin@tutimotos.com">
                    </div>
                    {{-- Error de Email justo debajo del input --}}
                    @if($errors->has('email'))
                        <p class="text-red-500 text-xs mt-1 font-semibold">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>

                {{-- Campo Contraseña --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña:</label>
                    <div class="relative">
                        {{-- Icono de candado (SVG) --}}
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" name="password" required 
                            class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent outline-none transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                {{-- Botón de Ingresar --}}
                <button type="submit" class="w-full bg-gray-900 text-white font-bold py-3 px-4 rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Ingresar
                </button>
            </form>

            {{-- Credenciales de prueba estilizadas --}}
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800">
                <div class="flex items-center gap-2 mb-2 font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Credenciales de prueba:
                </div>
                <div class="font-mono bg-white/50 p-2 rounded border border-blue-100">
                    <p>Email: <span class="select-all font-bold">admin@tutimotos.com</span></p>
                    <p>Pass: <span class="select-all font-bold">admin123</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection