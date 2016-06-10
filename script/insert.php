<?php
/**
 *
 * Save the current song in `stream` and `unique_songs`
 * Or only `stream if the song has already been played.`
 *
 */

require "simple_html_dom.php";
require "db_connect.php";

function insert() {
    global $db;

    # Fetch current artist and song
    $html = file_get_html("http://www.rock.lt/in/iframe.php");
    $e = $html->find("strong", 0);

    # Get artist and song with Each Word Capitalised
    $string = strip_tags($e);
    $array  = explode("-", $string);
    $artist = $db->real_escape_string(ucwords(trim(strtolower($array[0])), " "));
    $song   = $db->real_escape_string(ucwords(trim(strtolower($array[1])), " "));
    
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
    
    $result = $db->query($query_str)->fetch_assoc();
    $last_artist = $db->real_escape_string($result["artist"]);
    $last_song = $db->real_escape_string($result["song"]);

    if ($last_artist == $artist && $last_song == $song) {
        echo "Song has not changed. \n";
        exit();
    }
    
    # Check if this song has already been played
    $result = $db->query("SELECT id FROM unique_songs WHERE `artist`='$artist' AND `song`='$song'");

    # If this is the first time this song is played
    if (!$result->num_rows) {
        
        # Insert the song into `unique_songs`
        $query = $db->prepare("INSERT INTO unique_songs(artist, song) VALUES (?, ?)");
        $query->bind_param('ss', $artist, $song);
        $query->execute();
        $query->close();

    }

    # Get the new/old song ID
    $id = $db->query("SELECT id FROM unique_songs WHERE `artist`='$artist' AND `song`='$song'")->fetch_assoc()["id"];

    echo $id."\n";
    
    # Insert the ID into `stream`
    $db->query("INSERT INTO stream (song_id) VALUES ($id)");
    
}

insert();

