<?php

require "dbconnection.php";
$dbcon = createDbConnection();
$body = file_get_contents("php://input");
$data = json_decode($body);
$artist_id = strip_tags($data->id);

try{
    $dbcon->beginTransaction();

    $dbcon->exec("DELETE FROM invoice_items 
    WHERE TrackId IN (SELECT TrackId FROM tracks 
    WHERE AlbumId IN(SELECT AlbumId FROM albums 
    WHERE ArtistId In( SELECT ArtistId FROM artists 
    WHERE ArtistId = $artist_id)))");

    $dbcon->exec("DELETE FROM playlist_track 
    WHERE TrackId IN (SELECT TrackId FROM tracks 
    WHERE AlbumId IN(SELECT AlbumId FROM albums 
    WHERE ArtistId In( SELECT ArtistId FROM artists 
    WHERE ArtistId = $artist_id)))");

    $dbcon->exec("DELETE FROM tracks
    WHERE AlbumId IN (SELECT AlbumId FROM albums
    WHERE ArtistId IN(SELECT ArtistId FROM artists
    WHERE ArtistId = $artist_id))");

    $dbcon->exec("DELETE FROM albums
    WHERE ArtistId = $artist_id");

    $dbcon->exec("DELETE FROM artists
    WHERE ArtistId = $artist_id");

    $dbcon->commit();
} catch(Exception $e){
    $dbcon->rollBack();
    echo "Failed:".$e->getMessage();
}
