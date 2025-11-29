@extends('layouts.app')

@section('title', 'Edit Profil - CinemaHub')

@section('content')
<div class="max-w-2xl mx-auto py-12">
    <div class="mb-8">
        <a href="{{ route('profile.show') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Profil
        </a>
    </div>

    <div class="glass-card rounded-2xl p-8 border border-white/10">
        <h1 class="text-3xl font-display font-bold text-white mb-8">Edit Profil</h1>

        @if(session('success'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 flex items-center gap-3">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6 mb-12">
            @csrf
            @method('PUT')

            <div class="group">
                <label for="name" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
                <div class="relative">
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-5 py-4 text-white placeholder-gray-600 focus:border-primary focus:bg-black/50 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all">
                    <i class="fas fa-user absolute right-5 top-4.5 text-gray-600 group-focus-within:text-primary transition-colors"></i>
                </div>
                @error('name')
                    <span class="text-red-500 text-sm mt-2 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="group">
                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Email Address</label>
                <div class="relative">
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-5 py-4 text-white placeholder-gray-600 focus:border-primary focus:bg-black/50 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all">
                    <i class="fas fa-envelope absolute right-5 top-4.5 text-gray-600 group-focus-within:text-primary transition-colors"></i>
                </div>
                <p class="text-gray-500 text-xs mt-2 ml-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Mengubah email akan memerlukan verifikasi ulang.
                </p>
                @error('email')
                    <span class="text-red-500 text-sm mt-2 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-red-700 hover:shadow-[0_0_20px_rgba(229,9,20,0.4)] transition-all flex items-center justify-center gap-2">
                <i class="fas fa-save"></i>
                Simpan Perubahan
            </button>
        </form>

        <div class="border-t border-white/10 pt-8">
            <h2 class="text-xl font-display font-bold text-white mb-6">Ubah Password</h2>

            <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="group">
                    <label for="current_password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Password Saat Ini</label>
                    <div class="relative">
                        <input id="current_password" type="password" name="current_password" required
                            class="w-full bg-black/30 border border-white/10 rounded-xl px-5 py-4 text-white placeholder-gray-600 focus:border-primary focus:bg-black/50 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all"
                            placeholder="Password lama">
                        <i class="fas fa-lock absolute right-5 top-4.5 text-gray-600 group-focus-within:text-primary transition-colors"></i>
                    </div>
                    @error('current_password')
                        <span class="text-red-500 text-sm mt-2 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="group">
                    <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Password Baru</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            class="w-full bg-black/30 border border-white/10 rounded-xl px-5 py-4 text-white placeholder-gray-600 focus:border-primary focus:bg-black/50 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all"
                            placeholder="Minimal 8 karakter">
                        <i class="fas fa-lock absolute right-5 top-4.5 text-gray-600 group-focus-within:text-primary transition-colors"></i>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm mt-2 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="group">
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full bg-black/30 border border-white/10 rounded-xl px-5 py-4 text-white placeholder-gray-600 focus:border-primary focus:bg-black/50 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all"
                            placeholder="Ulangi password baru">
                        <i class="fas fa-lock absolute right-5 top-4.5 text-gray-600 group-focus-within:text-primary transition-colors"></i>
                    </div>
                </div>

                <button type="submit" class="w-full glass text-white font-bold py-4 rounded-xl hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-key"></i>
                    Ubah Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
