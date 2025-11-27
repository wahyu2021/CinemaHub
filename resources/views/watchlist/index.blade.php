@extends('layouts.app')

@section('title', 'Watchlist Saya - CinemaHub')

@section('content')
<div class="container mx-auto px-4 pt-20 pb-12">
    <h1 class="text-3xl font-bold text-white mb-8 border-l-4 border-primary pl-4">Watchlist Saya</h1>

    @if($watchlist->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
            <i class="fas fa-film text-6xl mb-4"></i>
            <p class="text-xl">Belum ada film di Watchlist Anda.</p>
            <a href="{{ route('movies.index') }}" class="mt-4 text-primary hover:underline">Cari film sekarang</a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($watchlist as $item)
                <div class="movie-card relative group bg-darker rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <a href="{{ route('movies.show', $item->movie_id) }}">
                        <img src="{{ 'https://image.tmdb.org/t/p/w500' . $item->poster_path }}" 
                             alt="{{ $item->title }}" 
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 p-4">
                                <h3 class="text-white font-bold text-lg leading-tight truncate">{{ $item->title }}</h3>
                            </div>
                        </div>
                    </a>
                    
                    <form action="{{ route('watchlist.destroy', $item->movie_id) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-black bg-opacity-75 hover:bg-red-600 text-white rounded-full p-2 transition-colors" title="Hapus dari Watchlist">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
