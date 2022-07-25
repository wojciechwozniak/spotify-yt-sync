<?php
/**
 * Created by PhpStorm.
 * User: Wojtek
 * Date: 13.11.2019
 * Time: 19:02
 */

namespace App\Http\Controllers;

use SpotifyWebAPI\SpotifyWebAPI as SpotifyWebApi;
use SpotifyWebAPI\Session as SpotifySession;
use Madcoda\Youtube;
class AuthSpotify extends Controller
{
    public $session;

    public $youtube;

    public function __construct()
    {
        $this->session = new SpotifySession(
            env('SPOTIFY_KEY'),
            env('SPOTIFY_CLIENT'),
            env('SPORIFY_REDIRECT')
        );
        $this->youtube = new Youtube(array('key' => env('YOUTUBE_KEY')));

    }

}

