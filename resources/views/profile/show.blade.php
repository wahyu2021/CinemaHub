@extends('layouts.app')

@section('title', __('messages.profile_title') . ' - CinemaHub')

@section('content')
<div class="max-w-4xl mx-auto py-12">
    @if(session('success'))
        <div class="mb-6 px-6 py-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-card rounded-2xl overflow-hidden border border-white/10">
        <div class="relative h-48 bg-gradient-to-r from-primary/30 via-purple-500/20 to-blue-600/30">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-30"></div>
        </div>

        <div class="relative px-8 pb-8">
            <div class="flex flex-col md:flex-row md:items-end gap-6 -mt-16">
                <div class="relative">
                    <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-primary to-purple-600 p-1 shadow-2xl">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=000&color=fff&bold=true"
                            alt="{{ $user->name }}"
                            class="w-full h-full rounded-xl object-cover">
                    </div>
                    @if($user->hasVerifiedEmail())
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center border-4 border-[#0a0a0a]">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    <h1 class="text-3xl font-display font-bold text-white">{{ $user->name }}</h1>
                    <p class="text-gray-400 flex items-center gap-2 mt-1">
                        <i class="fas fa-envelope text-sm"></i>
                        {{ $user->email }}
                        @if($user->hasVerifiedEmail())
                            <span class="text-xs text-green-400 bg-green-500/20 px-2 py-0.5 rounded-full">{{ __('messages.verified') }}</span>
                        @else
                            <span class="text-xs text-yellow-400 bg-yellow-500/20 px-2 py-0.5 rounded-full">{{ __('messages.unverified') }}</span>
                        @endif
                    </p>
                </div>

                <a href="{{ route('profile.edit') }}" class="px-6 py-3 glass rounded-xl hover:bg-white/10 transition-all flex items-center gap-2 text-white font-medium">
                    <i class="fas fa-edit"></i>
                    {{ __('messages.edit_profile') }}
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-10">
                <div class="glass p-6 rounded-xl border border-white/5 text-center">
                    <div class="text-3xl font-display font-bold text-primary">{{ $watchlistCount }}</div>
                    <div class="text-gray-400 text-sm mt-1">{{ __('messages.watchlist') }}</div>
                </div>
                <div class="glass p-6 rounded-xl border border-white/5 text-center">
                    <div class="text-3xl font-display font-bold text-white">
                        {{ $user->days_joined }}
                    </div>
                    <div class="text-gray-400 text-sm mt-1">{{ __('messages.days_joined') }}</div>
                </div>
                <div class="glass p-6 rounded-xl border border-white/5 text-center">
                    <div class="text-3xl font-display font-bold text-green-400">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="text-gray-400 text-sm mt-1">
                        {{ $user->hasVerifiedEmail() ? __('messages.verified') : __('messages.unverified') }}
                    </div>
                </div>
                <div class="glass p-6 rounded-xl border border-white/5 text-center">
                    <div class="text-2xl font-display font-bold text-white">
                        {{ optional($user->created_at)->format('M Y') ?? 'N/A' }}
                    </div>
                    <div class="text-gray-400 text-sm mt-1">{{ __('messages.member_since') }}</div>
                </div>
            </div>

            @if(!$user->hasVerifiedEmail())
                <div class="mt-8 p-6 rounded-xl bg-yellow-500/10 border border-yellow-500/30">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-yellow-500/20 flex items-center justify-center text-yellow-400">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-white">{{ __('messages.email_verification_required') }}</h3>
                            <p class="text-gray-400 text-sm mt-1">{{ __('messages.email_verification_notice') }}</p>
                            <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-yellow-500 text-black font-bold rounded-lg hover:bg-yellow-400 transition-colors text-sm">
                                    {{ __('messages.resend_verification') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('watchlist.index') }}" class="px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-red-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-bookmark"></i>
                    {{ __('messages.view_watchlist') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-6 py-3 glass text-gray-400 hover:text-white rounded-xl transition-colors flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i>
                        {{ __('messages.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
