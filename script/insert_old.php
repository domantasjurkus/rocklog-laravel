<?php
/**
 *
 * The purpose of this script is to save new songs into the database.
 * A cron job will invoke this file every minute, ensuring we save every song.
 *
 */

require "simple_html_dom.php";
require "db_connect.php";

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

# Query DB for all records
$query_str = "SELECT * FROM songs ";
$result = $db->query($query_str);

# If no records ar present
if (!$result->num_rows || !$result) {

    # Insert first record
    $query_str = "INSERT INTO songs(artist, song) VALUES ('$artist', '$song')";
    $a = $db->query($query_str);
    log_insert($a, "First song inserted", "Failed to insert first song");
    exit();
}

# If records are present - check if the song has not changed
# i.e. last record is equal to the one we scrapped
$query_str = "SELECT artist,song FROM songs ORDER BY id DESC LIMIT 1;";
$result = $db->query($query_str)->fetch_assoc();
$last_artist = $db->real_escape_string($result["artist"]);
$last_song = $db->real_escape_string($result["song"]);

if ($last_artist == $artist && $last_song == $song) {
    log_insert(1, "Song has not changed", "Song has not changed");
    exit();
}

# Else - insert new song
$query_str = "INSERT INTO songs(artist, song) VALUES ('$artist', '$song')";
$a = $db->query($query_str);
log_insert($a, "New song inserted", "Failed to insert new song");
exit();



function log_insert($action, $success_message, $fail_message) {
    echo ($action?$success_message:$fail_message)."\n";
    echo date("Y-m-d H:i:s", time())."\n";
    file_put_contents("insert.log", date("Y-m-d H:i:s", time())." ".($action?$success_message:$fail_message)."\n", FILE_APPEND);
}