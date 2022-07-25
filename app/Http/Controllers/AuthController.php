<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class AuthController extends AuthSpotify
{


    public function login()
    {
        $options = [
            'scope' => [
                'user-read-email',
                'playlist-modify-public',
            ],
        ];
        header('Location: ' . $this->session->getAuthorizeUrl($options));
        die();
    }
}
