@extends('layouts.app')

@section('title', __('messages.edit_profile') . ' - CinemaHub')

@section('content')
<div class="min-h-screen py-12 relative overflow-hidden">
    <!-- Ambient Background Effects (Consistent with Profile Show) -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[128px] pointer-events-none translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[128px] pointer-events-none -translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        @if(session('success'))
            <div class="mb-8 animate-fade-in-down">
                <div class="px-6 py-4 rounded-2xl bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-4 backdrop-blur-sm shadow-lg">
                    <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column: Profile Card (Identical to Show Page) -->
            <div class="w-full lg:w-1/3 space-y-6">
                <div class="glass-card rounded-3xl p-8 border border-white/10 relative overflow-hidden group">
                    <!-- Decorative Gradient -->
                    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-primary/20 via-purple-600/10 to-transparent"></div>
                    
                    <div class="relative z-10 flex flex-col items-center text-center mt-4">
                        <div class="relative mb-6">
                            <div class="w-32 h-32 rounded-full p-1 bg-gradient-to-br from-primary to-purple-600 shadow-[0_0_30px_rgba(229,9,20,0.3)]">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=000&color=fff&bold=true"
                                    alt="{{ $user->name }}"
                                    class="w-full h-full rounded-full object-cover border-4 border-black">
                            </div>
                            @if($user->hasVerifiedEmail())
                                <div class="absolute bottom-1 right-1 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center border-4 border-[#111] shadow-lg" title="Verified">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                            @endif
                        </div>
                        
                        <h1 class="text-3xl font-display font-bold text-white mb-1">{{ $user->name }}</h1>
                        <p class="text-gray-400 text-sm mb-6 font-light flex items-center justify-center gap-2">
                            <i class="far fa-envelope"></i> {{ $user->email }}
                        </p>
                        
                        <!-- Stats Grid -->
                        <div class="w-full grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                                <p class="text-2xl font-display font-bold text-white">{{ $user->watchlist->count() }}</p>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('messages.watchlist') }}</p>
                            </div>
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                                <p class="text-2xl font-display font-bold text-white">{{ $user->days_joined }}</p>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('messages.days_active') }}</p>
                            </div>
                        </div>

                        <div class="w-full space-y-3">
                            <!-- Back Button (Active State Visual) -->
                            <a href="{{ route('profile.show') }}" class="group/back relative w-full py-3.5 bg-white/5 rounded-xl overflow-hidden flex items-center justify-center gap-2 text-gray-300 font-medium border border-white/10 transition-all hover:text-white hover:border-primary/50">
                                <div class="absolute top-0 right-0 h-full w-0 bg-primary transition-all duration-500 ease-out group-hover/back:w-full"></div>
                                <span class="relative z-10 flex items-center gap-2">
                                    <i class="fas fa-arrow-left group-hover/back:-translate-x-1 transition-transform duration-300"></i>
                                    {{ __('messages.back_to_dashboard') }}
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Edit Forms (Consistent Container Style) -->
            <div class="w-full lg:w-2/3 space-y-8">
                
                <!-- Edit Profile Section -->
                <div class="glass-card rounded-3xl p-8 border border-white/10 relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-8 border-b border-white/5 pb-6">
                        <div class="w-12 h-12 rounded-2xl bg-primary/20 flex items-center justify-center text-primary shadow-lg shadow-primary/10">
                            <i class="fas fa-user-edit text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-display font-bold text-white">{{ __('messages.edit_profile') }}</h3>
                            <p class="text-sm text-gray-400">{{ __('messages.update_details_desc') }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="group">
                                <label for="name" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">{{ __('messages.full_name') }}</label>
                                <div class="relative">
                                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-600 focus:border-primary focus:bg-black/60 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all">
                                    <i class="fas fa-user absolute right-4 top-4 text-gray-600 group-focus-within:text-primary transition-colors text-sm"></i>
                                </div>
                                @error('name')
                                    <span class="text-red-500 text-xs mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">{{ __('messages.email_address') }}</label>
                                <div class="relative">
                                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-600 focus:border-primary focus:bg-black/60 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all">
                                    <i class="fas fa-envelope absolute right-4 top-4 text-gray-600 group-focus-within:text-primary transition-colors text-sm"></i>
                                </div>
                                @error('email')
                                    <span class="text-red-500 text-xs mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <!-- Primary Save Button with Hover Effect -->
                            <button type="submit" class="group relative overflow-hidden rounded-xl bg-primary px-8 py-3.5 text-white font-bold transition-all hover:shadow-[0_0_20px_rgba(229,9,20,0.4)]">
                                <div class="absolute inset-0 w-0 bg-white/20 transition-all duration-300 ease-out group-hover:w-full"></div>
                                <span class="relative flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    {{ __('messages.save_changes') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Section -->
                <div class="glass-card rounded-3xl p-8 border border-white/10 relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-8 border-b border-white/5 pb-6">
                        <div class="w-12 h-12 rounded-2xl bg-yellow-500/20 flex items-center justify-center text-yellow-500 shadow-lg shadow-yellow-500/10">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-display font-bold text-white">{{ __('messages.reset_password_title') }}</h3>
                            <p class="text-sm text-gray-400">{{ __('messages.security_desc') }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="group">
                            <label for="current_password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">{{ __('messages.current_password') }}</label>
                            <div class="relative">
                                <input id="current_password" type="password" name="current_password" required
                                    class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-600 focus:border-primary focus:bg-black/60 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all">
                                <i class="fas fa-lock absolute right-4 top-4 text-gray-600 group-focus-within:text-primary transition-colors text-sm"></i>
                            </div>
                            @error('current_password')
                                <span class="text-red-500 text-xs mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="group">
                                <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">{{ __('messages.new_password') }}</label>
                                <div class="relative">
                                    <input id="password" type="password" name="password" required
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-600 focus:border-primary focus:bg-black/60 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all">
                                    <i class="fas fa-key absolute right-4 top-4 text-gray-600 group-focus-within:text-primary transition-colors text-sm"></i>
                                </div>
                                @error('password')
                                    <span class="text-red-500 text-xs mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="password_confirmation" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">{{ __('messages.confirm_password') }}</label>
                                <div class="relative">
                                    <input id="password_confirmation" type="password" name="password_confirmation" required
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-600 focus:border-primary focus:bg-black/60 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all">
                                    <i class="fas fa-check-circle absolute right-4 top-4 text-gray-600 group-focus-within:text-primary transition-colors text-sm"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-8 py-3.5 glass text-white font-bold rounded-xl hover:bg-white/10 hover:text-white transition-all flex items-center gap-2 border border-white/5">
                                <i class="fas fa-lock-open"></i>
                                {{ __('messages.reset_password_btn') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection