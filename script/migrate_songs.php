<?php

require "db_connect.php";

 /**
  * 
  * 1. Migrate songs from `songs` to `unique_songs`.
  *
  */

function migrate_songs() {
    global $db;
    
    # Clean-up in case last import went bad
    $db->query("DELETE FROM stream WHERE 1=1;");
    $db->query("ALTER TABLE stream AUTO_INCREMENT=1");
    $db->query("DELETE FROM unique_songs WHERE 1=1;");
    $db->query("ALTER TABLE unique_songs AUTO_INCREMENT=1");

    # Query DB for all records
    $old = $db->query("SELECT * FROM songs");

    # Loop through each song in `songs`
    while ($old_row = $old->fetch_assoc()) {
        
        $old_artist = $old_row["artist"];
        $old_song   = $old_row["song"];
        
        echo $old_row["id"]." - ".$old_artist." - ".$old_song."\n";

        # Check if the current song has already been migrated
        $stmt = $db->prepare("SELECT id FROM unique_songs WHERE `artist`=? AND `song`=?");
        $stmt->bind_param('ss', $old_artist, $old_song);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();
        
        echo "\n";
        
        # If song was not found in `unique_songs`
        if (!$id) {
            
            # Insert the song into `unique_songs`
            $query = $db->prepare("INSERT INTO unique_songs(artist, song) VALUES (?, ?)");
            $query->bind_param('ss', $old_artist, $old_song);
            $query->execute();
            $query->close();
            
            continue;
        }
        
        echo "Song ".$old_row["id"]." already inserted - skipping \n";
        $id = null;

    }
    
}

migrate_songs();

/**
 * 
 * 2. Set up `stream` to contain IDs of unique songs.
 * 
 */

function update_stream() {
    global $db;

    # Clean-up in case last import went bad
    $db->query("DELETE FROM stream WHERE 1=1;");
    $db->query("ALTER TABLE stream AUTO_INCREMENT=1");

    # Query DB for all songs that have been played on air
    # These include duplicates
    $old_rows = $db->query("SELECT * FROM songs");

    while ($old_row = $old_rows->fetch_assoc()) {

        $old_artist = $db->real_escape_string($old_row["artist"]);
        $old_song   = $db->real_escape_string($old_row["song"]);
        $old_time   = $old_row["created_at"];

        # Get the ID of this song
        $query_string = "SELECT id FROM unique_songs WHERE `artist`='$old_artist' AND `song`='$old_song'";
        $query = $db->query($query_string);
        
        $result = $query->fetch_assoc();
        $id = $result["id"];
        
        # Insert the info into `stream`
        $query = $db->prepare("INSERT INTO stream(song_id, created_at) VALUES (?, ?)");
        $query->bind_param('is', $id, $old_time);
        $query->execute();
        $query->close();

    }
    
}

update_stream();
