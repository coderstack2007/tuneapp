<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

  
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Spotify Плейлист') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (!auth()->user()->spotify_access_token)
                        <div class="mb-6">
                            <p class="text-gray-600 mb-4">Подключите Spotify для просмотра плейлиста</p>
                            <a href="{{ route('dashboard.spotify.connect') }}" 
                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Подключить Spotify
                            </a>
                        </div>
                    @else
                        <!-- Spotify Embed для плейлиста -->
                        <div class="mb-8">
                            <iframe 
                                title="Spotify Embed: Recommendation Playlist"
                                src="https://open.spotify.com/embed/playlist/10v2J4jzdO6NqXC2PovlbY?utm_source=generator&theme=0"
                                width="100%"
                                height="380"
                                frameborder="0"
                                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                                loading="lazy">
                            </iframe>
                        </div>

                        <!-- Отображение 5 треков -->
                        @if(isset($tracks) && count($tracks) > 0)
                            <h3 class="text-lg font-semibold mb-4">Топ 5 треков из плейлиста:</h3>
                            <div class="space-y-4">
                                @foreach($tracks as $item)
                                    <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                                        @if(isset($item->track->album->images[0]))
                                            <img src="{{ $item->track->album->images[0]->url }}" 
                                                 alt="{{ $item->track->album->name }}"
                                                 class="w-12 h-12 rounded">
                                        @endif
                                        <div class="flex-1">
                                            <p class="font-medium">{{ $item->track->name }}</p>
                                            <p class="text-sm text-gray-600">
                                                {{ implode(', ', array_column($item->track->artists, 'name')) }}
                                            </p>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ gmdate("i:s", $item->track->duration_ms / 1000) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600">Не удалось загрузить треки. Попробуйте переподключить Spotify.</p>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
