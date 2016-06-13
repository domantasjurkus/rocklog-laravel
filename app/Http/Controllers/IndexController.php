<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use App\UserSongs;

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

    /**
     * 
     * Main Index view
     * 
     */
    public function index(Request $request)
    {
        
        $user = $request->session()->get('user', null);
        $fb_user = $request->session()->get('fb_user', null);
        
        # Fetch songs the old way from the `songs` table
        # $songs = DB::table('songs')->take(30)->orderBy('created_at', 'desc')->get();
        
        # Fetch songs the new way by joining `unique_songs` and `stream`
        $songs = DB::select(
            "SELECT song_id, artist, song, stream.created_at
            FROM stream
            JOIN unique_songs ON stream.song_id = unique_songs.id
            ORDER BY created_at DESC
            LIMIT 30"
        );
        
        # Get user ID or null if not connected
        $user_id = ($user) ? $user["id"] : 0;
        
        # Fetch the IDs of all saved songs
        $saved_songs = DB::select(
            "SELECT song_id
            FROM user_songs
            WHERE user_id=$user_id"
        );
        
        # Convert the ugly stdObject array into a regular array
        $star_ids = [];
        
        foreach ($saved_songs as $saved_song) {
            array_push($star_ids, $saved_song->song_id);
        }
        
        # Mark saved songs with a star
        foreach ($songs as $song) {
            in_array($song->song_id, $star_ids) ? $song->saved = true : $song->saved = false;
        }
        
        return view('index', [
            'fb_user' => $fb_user,
            'songs' => $songs,
            'link' => 'saved',
            'link_text' => 'Išsaugotos dainos'
        ]);
    }
    
    /** 
     * 
     * Get a YouTube video ID from song name + artist
     * 
     */
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
    
    /**
     * 
     * Save a song
     * 
     */
    public function save_song(Request $request, $song_id) {

        # Check if user is connected
        $user = $request->session()->get('user', null);
        if (!$user) return new Response("Connect with Facebook to save songs!", 403);
        $user_id = $user["id"];
        
        $song = UserSongs::where('user_id', $user_id)->where("song_id", $song_id)->first();
        
        # If song has already been inserted
        if ($song) {
            $song->delete();
            return "Song Removed";
        }
        
        # Save song
        $user_song = new UserSongs();
        $user_song->user_id = $user_id;
        $user_song->song_id = $song_id;
        $user_song->save();        
        
        return "Song Saved";
        
    }
    
    /** 
     * 
     * Retrieve saved songs
     * 
     */
    public function saved(Request $request) {

        $user = $request->session()->get('user', null);
        $fb_user = $request->session()->get('fb_user', null);
        if (!$user) return "Err, no user picked up";
        if (!$fb_user) return "Err, no FB user picked up";

        $id = $user["id"];
        
        $songs = DB::select(
            "SELECT song_id, artist, song, created_at
            FROM user_songs
            JOIN unique_songs ON user_songs.song_id = unique_songs.id
            WHERE user_songs.user_id = $id
            ORDER BY created_at DESC"
        );
        
        foreach ($songs as $song) { $song->saved = true; }
        
        return view('index', [
            'fb_user' => $fb_user,
            'songs' => $songs,
            'link' => '/',
            'link_text' => 'Visos dainos'
        ]);
        
    }
    
}
