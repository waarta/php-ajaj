<?php
require_once "myPDO.include.php";

if (isset($_REQUEST['wait'])) {
    usleep(rand(0, 20) * 100000);
}

$q = "";
if (isset($_GET['q'])) {
    $q = $_GET['q'];
}

$pdo = myPDO::getInstance();
$stmt = $pdo->prepare(<<<SQL
    SELECT name
    FROM artist
    WHERE name LIKE '%{$q}%'
    ORDER BY NAME
SQL
);
$stmt->execute();
$stmt->setFetchMode($pdo::FETCH_ASSOC);

foreach ($stmt->fetchAll() as $ligne) {
    echo $ligne['name'] . ' , ';
}
