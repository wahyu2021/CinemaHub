@extends('layouts.app')

@section('title', 'Reset Password - CinemaHub')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[100px] animate-blob"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-[100px] animate-blob animation-delay-2000"></div>

    <div class="relative z-10 w-full max-w-md glass-card p-10 rounded-2xl shadow-2xl border border-white/10">
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-lock-open text-2xl text-green-400"></i>
            </div>
            <h2 class="text-3xl font-display font-bold text-white mb-2">Reset Password</h2>
            <p class="text-gray-400">Buat password baru untuk akun Anda</p>
        </div>
        
        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="group">
                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Email Address</label>
                <div class="relative">
                    <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email"
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-5 py-4 text-white placeholder-gray-600 focus:border-primary focus:bg-black/50 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all"
                        placeholder="name@example.com">
                    <i class="fas fa-envelope absolute right-5 top-4.5 text-gray-600 group-focus-within:text-primary transition-colors"></i>
                </div>
                @error('email')
                    <span class="text-red-500 text-sm mt-2 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="group">
                <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Password Baru</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-5 py-4 text-white placeholder-gray-600 focus:border-primary focus:bg-black/50 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all"
                        placeholder="Minimal 8 karakter">
                    <i class="fas fa-lock absolute right-5 top-4.5 text-gray-600 group-focus-within:text-primary transition-colors"></i>
                </div>
                @error('password')
                    <span class="text-red-500 text-sm mt-2 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="group">
                <label for="password_confirmation" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Konfirmasi Password</label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-5 py-4 text-white placeholder-gray-600 focus:border-primary focus:bg-black/50 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all"
                        placeholder="Ulangi password baru">
                    <i class="fas fa-lock absolute right-5 top-4.5 text-gray-600 group-focus-within:text-primary transition-colors"></i>
                </div>
            </div>

            <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-red-700 hover:shadow-[0_0_20px_rgba(229,9,20,0.4)] hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2 group">
                <span>Reset Password</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>
    </div>
</div>
@endsection
