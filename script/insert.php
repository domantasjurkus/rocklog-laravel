<?php
/**
 *
 * Save the current song in `stream` and `unique_songs`
 * Or only `stream` if the song has already been played.
 *
 */

require "simple_html_dom.php";
require "db_connect.php";

function insert() {
    global $db;

    # Fetch current artist and song with Each Word Capitalised
    $html = file_get_html("http://www.rock.lt/in/iframe.php");
    $e = $html->find("strong", 0);
    $string = strip_tags($e);
    $array  = explode("-", $string);
    $artist = ucwords(trim(strtolower($array[0])), " ");
    $song   = ucwords(trim(strtolower($array[1])), " ");
    
    # Check if artist/song are empty strings
    if ($artist == "" || $song == "") {
        echo "Blank info - exiting";
        exit();
    }

    echo "Artist: ".$artist."\n";
    echo "Song: ".$song."\n";

    # Check the last song that was inserted
    $query_str = "
        SELECT unique_songs.artist,unique_songs.song
        FROM stream JOIN unique_songs ON stream.song_id = unique_songs.id
        ORDER BY stream.created_at DESC
        LIMIT 1
    ";

    if (!$query = $db->query($query_str)) echo "Query failed \n";
    $array = $query->fetch_assoc();
    if (!$array) echo "No last song\n";

    $last_artist = $array["artist"];
    $last_song = $array["song"];

    # Is the last song equal to the current one?
    if ($last_artist == $artist && $last_song == $song) {
        echo "Song has not changed. \n";
        exit();
    }
    
    # Check if this song has already been played    
    $query_str = "SELECT id FROM unique_songs WHERE `artist`='$artist' AND `song`='$song'";
    if (!$query = $db->query($query_str)) echo "Query failed \n";
    $array = $query->fetch_assoc();
    
    # If this is the first time this song is played
    if (!$query->num_rows) {
        
        # Insert the song into `unique_songs`
        $query = $db->prepare("INSERT INTO unique_songs(artist, song) VALUES (?, ?)");
        $query->bind_param('ss', $artist, $song);
        $query->execute();
        $query->close();
        
        # Fetch new id
        $query_str = "SELECT id FROM unique_songs WHERE `artist`='$artist' AND `song`='$song'";
        if (!$query = $db->query($query_str)) echo "Query failed \n";
        $id = $query->fetch_assoc()["id"];

    # Else, use the existing song id in `unique_songs`
    } else {
        
        $id = $array["id"];
    }
    
    # Insert the id into `stream`
    $db->query("INSERT INTO stream (song_id) VALUES ($id)");
    
}

insert();
