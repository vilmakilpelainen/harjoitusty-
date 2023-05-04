<?php

require "dbconnection.php";
$dbcon = createDbConnection();
$artist_id = $_GET["id"];

$sql = "SELECT artists.Name as artist, albums.Title as title, tracks.Name as tracks
FROM artists, albums, tracks
WHERE artists.ArtistId = albums.ArtistId AND albums.AlbumId = tracks.AlbumID AND artists.ArtistId = ?";

$statement = $dbcon->prepare($sql);
$statement->execute(array($artist_id));
$rows = $statement->fetchAll(PDO::FETCH_ASSOC);

$data = array();
$albums = array();

foreach ($rows as $row) {
    $artist = $row['artist'];
    $album = $row['title'];
    $track = $row['tracks'];

    $album_exists = false;
    foreach ($albums as &$a) {
        if ($a['title'] == $album) {
            $album_exists = true;
            $a['tracks'][] = $track;
            break;
        }
    }
    if (!$album_exists) {
        $albums[] = array(
            'title' => $album,
            'tracks' => array($track)
        );
    }
    
    $data['artist'] = $artist;
    $data['albums'] = $albums;
}



$json = json_encode($data);

header('Content-type: application/json');

echo $json;
