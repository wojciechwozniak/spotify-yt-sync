<?php
/**
 * Created by PhpStorm.
 * User: Wojtek
 * Date: 13.11.2019
 * Time: 19:16
 */

namespace App\Http\Controllers;

use Madcoda\Youtube;

class YouTubeAuth extends Controller
{
    public $youtube;

    public function __construct()
    {
        $this->youtube = new Youtube(['key' => env('YOUTUBE_KEY')]);
    }
}
