<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;

class SpotifyController extends Controller
{
    private $session;
    private $api;
    
    public function __construct()
    {
          $clientId = env('SPOTIFY_CLIENT_ID');
        $clientSecret = env('SPOTIFY_CLIENT_SECRET');
        $redirectUri = env('SPOTIFY_REDIRECT_URI', 'http://localhost:8000/dashboard/spotify/callback');
        $this->api = new SpotifyWebAPI();
        if (empty($clientId) || empty($clientSecret)) {
            throw new \Exception('Spotify API credentials are not set in .env file');
        }
        
        $this->session = new Session(
            $clientId,
            $clientSecret,
            $redirectUri
        );
        
        $this->api = new SpotifyWebAPI();
    }
    
    public function index()
    {
        // Проверяем, есть ли у пользователя токен
        $user = Auth::user();
        $tracks = [];
        
        if ($user->spotify_access_token) {
            $this->api->setAccessToken($user->spotify_access_token);
            
            // Получаем плейлист с ограничением в 5 треков
            $playlist = $this->api->getPlaylistTracks('10v2J4jzdO6NqXC2PovlbY', [
                'limit' => 5,
                'offset' => 0
            ]);
            
            $tracks = $playlist->items;
        }
        
        return view('dashboard', compact('tracks'));
    }
    
    public function connect()
    {
        $options = [
            'scope' => [
                'playlist-read-private',
                'playlist-read-collaborative',
            ],
        ];
        
        return redirect($this->session->getAuthorizeUrl($options));
    }
    
    public function callback(Request $request)
    {
        $this->session->requestAccessToken($request->code);
        $accessToken = $this->session->getAccessToken();
        
        // Сохраняем токен пользователя
        $user = Auth::user();
        $user->spotify_access_token = $accessToken;
        $user->save();
        
        return redirect()->route('dashboard.spotify')->with('status', 'Spotify успешно подключен!');
    }
}