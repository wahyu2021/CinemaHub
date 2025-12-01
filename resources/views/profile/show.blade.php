@extends('layouts.app')

@section('title', __('messages.profile_title') . ' - CinemaHub')

@section('content')
<div class="min-h-screen py-12 relative overflow-hidden">
    <!-- Ambient Background Effects -->
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
            <!-- Left Column: Profile Card -->
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
                                <div class="absolute bottom-1 right-1 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center border-4 border-[#111] shadow-lg" title="{{ __('messages.verified') }}">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                            @endif
                        </div>
                        
                        <h1 class="text-3xl font-display font-bold text-white mb-1">{{ $user->name }}</h1>
                        <p class="text-gray-400 text-sm mb-6 font-light flex items-center justify-center gap-2">
                            <i class="far fa-envelope"></i> {{ $user->email }}
                        </p>
                        
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
                            <a href="{{ route('profile.edit') }}" class="group/edit relative w-full py-3.5 glass rounded-xl overflow-hidden flex items-center justify-center gap-2 text-white font-medium border border-white/10 transition-all hover:border-primary/50">
                                <div class="absolute inset-0 w-0 bg-primary transition-all duration-500 ease-out group-hover/edit:w-full"></div>
                                <span class="relative z-10 flex items-center gap-2">
                                    <i class="fas fa-cog group-hover/edit:rotate-90 transition-transform duration-500"></i>
                                    {{ __('messages.edit_profile') }}
                                </span>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full py-3.5 rounded-xl text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all flex items-center justify-center gap-2 font-medium">
                                    <i class="fas fa-sign-out-alt"></i>
                                    {{ __('messages.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Dashboard & Stats -->
            <div class="w-full lg:w-2/3 space-y-8">
                <!-- Welcome Banner -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary/80 to-purple-900/80 p-8 md:p-10 border border-white/10 shadow-2xl">
                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-2">
                                {{ __('messages.welcome_back') }}, {{ explode(' ', $user->name)[0] }}!
                            </h2>
                            <p class="text-white/80 text-lg max-w-md">
                                {{ __('messages.welcome_subtitle') }}
                            </p>
                        </div>
                        <a href="{{ route('movies.trending') }}" class="px-6 py-3 bg-white text-primary font-bold rounded-xl hover:bg-gray-100 transition-colors shadow-lg flex items-center gap-2 whitespace-nowrap">
                            <i class="fas fa-compass"></i>
                            {{ __('messages.explore_now') }}
                        </a>
                    </div>
                </div>

                <!-- Verification Warning -->
                @if(!$user->hasVerifiedEmail())
                    <div class="rounded-2xl bg-yellow-500/10 border border-yellow-500/20 p-6 flex items-start gap-5">
                        <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center text-yellow-400 flex-shrink-0">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-white text-lg mb-1">{{ __('messages.email_verification_required') }}</h3>
                            <p class="text-gray-400 text-sm mb-4 leading-relaxed">{{ __('messages.email_verification_notice') }}</p>
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 bg-yellow-500 hover:bg-yellow-400 text-black font-bold rounded-lg transition-all text-sm shadow-lg shadow-yellow-500/20">
                                    {{ __('messages.resend_verification') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Recent Watchlist -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-display font-bold text-white flex items-center gap-3">
                            <span class="w-1 h-6 bg-primary rounded-full"></span>
                            {{ __('messages.recent_watchlist') }}
                        </h3>
                        <a href="{{ route('watchlist.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors flex items-center gap-1 group">
                            {{ __('messages.view_all') }} <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    @if($user->watchlist->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($user->watchlist->sortByDesc('created_at')->take(4) as $item)
                                <a href="{{ route('movies.show', $item->movie_id) }}" class="group relative aspect-[2/3] rounded-xl overflow-hidden bg-gray-900 border border-white/5 hover:border-primary/50 transition-all shadow-lg hover:shadow-primary/20">
                                    @if($item->poster_path)
                                        <img src="https://image.tmdb.org/t/p/w300{{ $item->poster_path }}" 
                                            alt="{{ $item->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-800">
                                            <i class="fas fa-film text-gray-600 text-3xl"></i>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                                    <div class="absolute bottom-0 left-0 w-full p-4 translate-y-2 group-hover:translate-y-0 transition-transform">
                                        <p class="text-white font-bold text-sm line-clamp-1">{{ $item->title }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $item->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-8 text-center">
                            <div class="w-16 h-16 mx-auto rounded-full bg-white/5 flex items-center justify-center mb-4">
                                <i class="fas fa-bookmark text-gray-500 text-2xl"></i>
                            </div>
                            <h4 class="text-white font-bold mb-2">{{ __('messages.empty_watchlist_title') }}</h4>
                            <p class="text-gray-400 text-sm mb-6">{{ __('messages.empty_watchlist_desc') }}</p>
                            <a href="{{ route('movies.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-colors text-sm font-medium">
                                <i class="fas fa-search"></i> {{ __('messages.browse_movies') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
