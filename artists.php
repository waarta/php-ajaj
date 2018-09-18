<?php
require_once "myPDO.include.php";

$style = "1";
if (isset($_GET['q'])) {
    $style = $_GET['q'];
}

$pdo = myPDO::getInstance();
$stmt = $pdo->prepare(<<<SQL
    SELECT DISTINCT artist.id, artist.name as txt
    FROM artist, album
    WHERE artist.id = album.artist
    AND album.genre = $style
    ORDER BY artist.name
SQL
);
$stmt->execute();
$stmt->setFetchMode($pdo::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($stmt->fetchAll());
