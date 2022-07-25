<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Madcoda\Youtube;
use Mockery\Exception;
use SpotifyWebAPI\SpotifyWebAPI;

class PublicController extends AuthSpotify
{
    public $res;

    public function index(Request $request)
    {
        if (isset($_GET['code'])) {
            $this->session->requestAccessToken($_GET['code']);

            $accessToken = $this->session->getAccessToken();
            $refreshToken = $this->session->getRefreshToken();

            $request->session()->put('spotify.accessToken', $accessToken);
            $request->session()->put('spotify.refreshToken', $refreshToken);

            return redirect(route('dashboard'));
        } else {
            $api = new SpotifyWebAPI();
            $api->setAccessToken(session('spotify.accessToken'));
            $user = User::updateOrCreate([
                'name' => $api->me()->display_name,
                'email' => $api->me()->email,
            ], [
                'access_token' => session('spotify.accessToken'),
                'refresh_token' => session('spotify.refreshToken')
            ]);
            $request->session()->put('user_id', $user->id);
            return view('dashboard');
        }
    }

    public function createYouTubePlaylist(Request $request)
    {
        $api = $this->spotifyApiObject($request);
        $this->getAllItemsFromYoutube($api, $request->youtubePlaylistId);
        $toView = $this->res;
        return json_encode($toView);
    }

    public function getAllItemsFromYoutube($api, $id, $pageToken = null)
    {
        $data = $this->youtube->getPlaylistItemsByPlaylistIdAdvanced(['playlistId' => $this->cleanYoutubeUrl($id), 'part' => 'snippet', 'maxResults' => 50, 'pageToken' => $pageToken], true);
        foreach ($data['results'] as $k => $t) {
            if (strpos($t->snippet->title, '(') || strpos($t->snippet->title, '[')) {
                $end = (strpos($t->snippet->title, '(')) ?: strpos($t->snippet->title, '[');
                $title = substr($t->snippet->title, 0, $end);
            } else {
                $title = $t->snippet->title;
            }

            Log::info($title);
            Log::info($this->clearTitleForSpotifySearch($title));

            $song = $api->search($this->clearTitleForSpotifySearch($title), 'track', ['limit' => 1]);
//            $song = $api->search($title, 'track', ['limit' => 1]);
            if (isset($song->tracks->items[0])) {
                $this->res[] = $this->prepareDataYoutube($song, $t->snippet->title, 200);
            } else {
                $title = $this->clearTitleForSpotifySearch($title);
                $song = $api->search($title, 'track', ['limit' => 1]);
                if (isset($song->tracks->items[0])) {
                    $this->res[] = $this->prepareDataYoutube($song, $t->snippet->title, 200);
                } else {
                    $this->res[] = $this->prepareDataYoutube($song, $t->snippet->title, 404);
                }
            }
        }
        if (isset($data['info']['nextPageToken'])) {
            if ($data['info']['nextPageToken'] != NULL) {
                $pageToken = $data['info']['nextPageToken'];
                $this->getAllItemsFromYoutube($api, $id, $pageToken);
            }
        }
        return true;
    }

    public function prepareDataYoutube($song, $title, $status)
    {
        $image = ($status != 404) ? $song->tracks->items[0]->album->images[count($song->tracks->items[0]->album->images) - 1]->url : '/img/404.png';
        $toView['title_yt'] = $title;
        $toView['title_spotify'] = ($status != 404) ? $this->prepareSpotifyTitle($song) : 'Song not found, edit title!';
        $toView['uri'] = ($status != 404) ? $song->tracks->items[0]->uri : 404;
        $toView['image'] = $image;
        return $toView;
    }

    public function prepareSpotifyTitle($song, $type = 1)
    {
        $artist = [];
        $title = '';
        if ($type == 1) {
            if (is_array($song->tracks->items[0]->artists)) {
                foreach ($song->tracks->items[0]->artists as $key => $a) {
                    $artist[] = $a->name;
                }
            } else {
                dd($song->tracks);
            }
            $title = $song->tracks->items[0]->name;
        } else {
            foreach ($song->artists as $key => $a) {
                $artist[] = $a->name;
            }
            $title = $song->name;
        }


        $artist = implode(' ', $artist);

        return implode(' - ', [$artist, $title]);

    }

    public function clearTitleForSpotifySearch($title)
    {
        mb_internal_encoding("UTF-8");
        $toRemoveAll = [' ft.', ' feat', ' feat.', ' featured', ' fit.', '[', '('];
        $toRemoveArtist = [' and ', ' &', ' x '];
        $explodedTitle = explode(' - ', $title);
        if (!isset($explodedTitle[0]) || !isset($explodedTitle[1])) {
            foreach (array_merge($toRemoveArtist, $toRemoveAll) as $t) {
                if (strpos($title, $t)) {
                    $result = str_replace(array_merge($toRemoveAll, $toRemoveArtist), ' ', $title);
                } else {
                    $result = $title;
                }
            }
            $result = $this->clean(trim($result));

            return $result;
        }

        $song_artist = strtolower(iconv('utf-8', 'ascii//TRANSLIT//IGNORE', $explodedTitle[0]));
        $song_title = strtolower(iconv('utf-8', 'ascii//TRANSLIT//IGNORE', $explodedTitle[1]));
        foreach ($toRemoveAll as $t) {
            if (strpos($song_title, $t)) {
                $to_artist = mb_substr($song_title, strpos($song_title, $t), strlen($song_title));
                $song_title = mb_substr($song_title, 0, strpos($song_title, $t));
                $song_artist .= ' ' . trim(str_replace(array_merge($toRemoveAll, $toRemoveArtist), ' ', $to_artist));
            }
        }
        $song_title = str_replace($toRemoveAll, ' ', $song_title);
        foreach (array_merge($toRemoveArtist, $toRemoveAll) as $t) {
            if (strpos($song_artist, $t)) {
                $song_artist = str_replace(array_merge($toRemoveAll, $toRemoveArtist), ' ', $song_artist);
            }
        }
        $result = $this->clean(trim($song_artist)) . ' - ' . $this->clean(trim($song_title));
        return $result;
    }

    public function clean($string)
    {
        Log::debug('clean :' . $string);
        $string = strtr($string, array('.' => '', ',' => '', '&' => '', "'" => '')); // Removes special chars.
        $str = mb_convert_encoding($string, 'UTF-8');
        Log::warning('cleaned :' . $str);
        return $str;
    }

    public function createSpotifyPlaylist(Request $request)
    {
        $api = $this->spotifyApiObject($request);
        $playlist = $api->createPlaylist(['name' => $request->name]);
        $api->addPlaylistTracks($playlist->id, $request->data);


        return view('dashboard');
    }

    public function searchSimilarSongs(Request $request)
    {
        $api = $this->spotifyApiObject($request);
        $songs = $api->search($request->value, 'track', ['limit' => 3]);
        $result = [];
        foreach ($songs->tracks->items as $key => $song) {
            if (isset($song)) {
                $result[$key]['title'] = $this->prepareSpotifyTitle($song, 2);
                $result[$key]['uri'] = $song->uri;
                $result[$key]['image'] = $song->album->images[count($song->album->images) - 1]->url;
            }
        }

        return json_encode($result);
    }

    public function lengthOfPlaylist(Request $request)
    {
        $id = $this->cleanYoutubeUrl($request->youtubePlaylistId);
        if ($id === 400) {
            return 'error';
        }
        $result = $this->youtube->getPlaylistItemsByPlaylistIdAdvanced(['playlistId' => $id, 'part' => 'id', 'maxResults' => 50], true);
        return $result['info']['totalResults'];
    }

    public function cleanYoutubeUrl($url)
    {
        if (!strpos($url, 'list=')) {
            return 400;
        } else {
            $id = explode('list=', $url);
            if (is_array($id)) {
                if (strpos($id[1], '&')) {
                    $id = substr($id[1], 0, strpos($id[1], '&'));
                } else {
                    $id = $id[1];
                }
                return $id;
            } else {
                return 400;
            }
        }

    }

    public function spotifyApiObject(Request $request)
    {
        $user = User::find(session('user_id'));
        $api = new SpotifyWebAPI();
        try {
            $api->setAccessToken($user->access_token);
        } catch (\Exception $e) {
            $this->session->refreshAccessToken($user->refresh_token);

            $accessToken = $this->session->getAccessToken();

            $api->setAccessToken($accessToken);

            $request->session()->put('spotify.refreshToken', $this->session->getAccessToken());
            $request->session()->put('spotify.accessToken', $this->session->getRefreshToken());
        }
        return $api;
    }
}
