<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
    
    $songs = DB::table('songs')->take(30)->orderBy('created_at', 'desc')->get();
        
    return view('index', ['songs' => $songs]);
});

# Route for getting video ID from YouTube Data API
Route::get('/videoid', function() {

    $key = env("GOOGLE_API_KEY");
    $q = urlencode($_GET["artist"]."+".$_GET["song"]);
    $part = urlencode("id,snippet");
    $args = "part=".$part."&q=".$q."&key=".$key."&maxResults=1";

    # Query YouTube API and return video id
    $json = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/search?".$args), true);
    return $json["items"][0]["id"]["videoId"];
});

