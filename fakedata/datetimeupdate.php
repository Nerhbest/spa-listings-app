<?php


$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=avitoapp", "nerhx", "czar");
$stmt = $pdo->query("select id from listings");
$ids =  $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$insertStmt = $pdo->prepare("update listings SET created_at = :created_at where id = :listing_id");
$insertStmt->bindParam(":listing_id", $listing_id);
$insertStmt->bindParam(":created_at", $random_date);


foreach ($ids as $id) {
    $random_date = date("Y-m-d H:i:s",mt_rand(1530835200,1533572393));
    $listing_id = $id['id'];
    $insertStmt->execute();
}