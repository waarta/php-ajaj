<?php
require_once "myPDO.include.php";

$id = "1";
if (isset($_GET['q'])) {
    $id = $_GET['q'];
}

$pdo = myPDO::getInstance();
$stmt = $pdo->prepare(<<<SQL
    SELECT DISTINCT id, name as txt
    FROM album
    WHERE artist = $id
    ORDER BY name
SQL
);
$stmt->execute();
$stmt->setFetchMode($pdo::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($stmt->fetchAll());
