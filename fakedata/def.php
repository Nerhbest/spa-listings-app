<?php


$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=avitoapp", "nerhx", "czar");
$stmt = $pdo->query("select id from listings
where not exists(select id from listing_images where listing_images.listing_id = listings.id)");
$ids =  $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();


$insertStmt = $pdo->prepare("INSERT INTO listing_images (listing_id, img_path) values (:listing_id,:path)");
$insertStmt->bindParam(":listing_id", $listing_id);
$insertStmt->bindParam(":path", $path);

$path = "listings/default.jpg";

foreach ($ids as $id)
{
    $listing_id = $id['id'];
    $insertStmt->execute();
}