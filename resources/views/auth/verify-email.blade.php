@extends('layouts.app')

@section('title', 'Verifikasi Email - CinemaHub')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[100px] animate-blob"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-[100px] animate-blob animation-delay-2000"></div>

    <div class="relative z-10 w-full max-w-lg glass-card p-10 rounded-2xl shadow-2xl border border-white/10 text-center">
        <div class="w-20 h-20 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-8">
            <i class="fas fa-envelope text-4xl text-primary"></i>
        </div>

        <h2 class="text-3xl font-display font-bold text-white mb-4">{{ __('messages.verify_email_title') }}</h2>
        
        <p class="text-gray-400 mb-8 leading-relaxed">
            {{ __('messages.verify_email_desc', ['email' => Auth::user()->email]) }}
        </p>

        @if (session('message'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 text-sm flex items-center gap-3">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-red-700 hover:shadow-[0_0_20px_rgba(229,9,20,0.4)] transition-all flex items-center justify-center gap-2 group">
                    <i class="fas fa-paper-plane"></i>
                    <span>{{ __('messages.resend_verification') }}</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-gray-400 hover:text-white py-3 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>{{ __('messages.logout_btn') }}</span>
                </button>
            </form>
        </div>

        <div class="mt-8 pt-6 border-t border-white/10">
            <p class="text-gray-500 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                {{ __('messages.check_spam') }}
            </p>
        </div>
    </div>
</div>
@endsection
