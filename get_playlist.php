<?php

require "dbconnection.php";
$dbcon = createDbConnection();
$playlist_id = $_GET["id"];

$sql = "SELECT tracks.Name as N, tracks.Composer as C
FROM playlists, playlist_track, track
WHERE playlists.PlaylistId = ?
AND playlists.PlaylistId = playlist_track.PlaylistId
AND playlist_track.Tracked = tracks.TrackId";

$statement = $dbcon->prepare($sql);
$statement->execute(array($playlist_id));
$rows = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach($rows as $rows){
    echo "<h3>".$row["N"]."</h3>"."<p>".$row["C"]."</p>"."</br>";
}