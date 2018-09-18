<?php
require_once "myPDO.include.php";

$id = "1";
if (isset($_GET['q'])) {
    $id = $_GET['q'];
}

$pdo = myPDO::getInstance();
$stmt = $pdo->prepare(<<<SQL
    SELECT DISTINCT LPAD(track.number,2,0) as num,
                    song.name,
                    TIME_FORMAT(SEC_TO_TIME(duration),'%i:%s') as duration
    FROM song, album, track
    WHERE album.id = $id
    AND album.id = track.album
    AND track.song = song.id
    ORDER BY track.number
SQL
);
$stmt->execute();
$stmt->setFetchMode($pdo::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($stmt->fetchAll());
