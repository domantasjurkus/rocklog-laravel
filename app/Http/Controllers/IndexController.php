<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        
        // $user = $request->session()->get('user', null);
        $fb_user = $request->session()->get('fb_user', null);
        
        // $songs = DB::table('stream')->take(30)->orderBy('created_at', 'desc')->get();
        $songs = DB::select(
            "SELECT song_id, artist, song, stream.created_at
            FROM stream
            JOIN unique_songs ON stream.song_id = unique_songs.id
            ORDER BY created_at DESC
            LIMIT 30"
        );
        
        return view('index', ['songs' => $songs, 'fb_user' => $fb_user]);
    }
    
    public function videoid()
    {
        $key = env("GOOGLE_API_KEY");
        $q = urlencode($_GET["artist"]."+".$_GET["song"]."+song");
        $part = urlencode("id,snippet");
        $args = "part=".$part."&q=".$q."&key=".$key."&maxResults=1";

        # Query YouTube API and return video id
        $json = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/search?".$args), true);
        return $json["items"][0]["id"]["videoId"];
    }
    
}
