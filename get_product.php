<?php
include_once("includes/connectDb.php");
$id = $_GET["id"];
$query = $pdo -> prepare("SELECT * FROM products WHERE id = :id");
$query -> bindParam(":id", $id);
$query -> execute();
$row = $query -> fetch(PDO::FETCH_ASSOC);
$result = $row;
header('Content-Type: application/json');
echo json_encode($result);
?>