@extends('layouts.app')

@section('title', 'Daftar - CinemaHub')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center relative" style="background-image: url('https://assets.nflxext.com/ffe/siteui/vlv3/f841d4c7-10e1-40af-bcae-07a3f8dc141a/f6d7434e-d6de-4185-a6d4-c77a2d08737b/US-en-20220502-popsignuptwoweeks-perspective_alpha_website_medium.jpg');">
    <div class="absolute inset-0 bg-black opacity-60"></div>
    
    <div class="relative z-10 w-full max-w-md bg-black bg-opacity-75 p-8 rounded-lg shadow-xl">
        <h2 class="text-3xl font-bold text-white mb-8">Daftar</h2>
        
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="sr-only">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                    class="w-full px-4 py-3 rounded bg-gray-700 text-white border border-transparent focus:border-gray-500 focus:bg-gray-600 focus:outline-none transition"
                    placeholder="Nama Lengkap">
                @error('name')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="email" class="sr-only">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                    class="w-full px-4 py-3 rounded bg-gray-700 text-white border border-transparent focus:border-gray-500 focus:bg-gray-600 focus:outline-none transition"
                    placeholder="Email">
                @error('email')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password" class="sr-only">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded bg-gray-700 text-white border border-transparent focus:border-gray-500 focus:bg-gray-600 focus:outline-none transition"
                    placeholder="Sandi">
                @error('password')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password-confirm" class="sr-only">Konfirmasi Password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded bg-gray-700 text-white border border-transparent focus:border-gray-500 focus:bg-gray-600 focus:outline-none transition"
                    placeholder="Konfirmasi Sandi">
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-red-700 text-white font-bold py-3 rounded transition duration-200">
                Daftar
            </button>
        </form>

        <div class="mt-16 text-gray-400">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-white hover:underline ml-1">Masuk sekarang</a>.
        </div>
    </div>
</div>
@endsection
