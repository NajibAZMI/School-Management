<?php

$values = json_decode($_POST['values'], true);

session_start();

if (isset($_SESSION["elected_class_id"])) {
 $selected_class_id = $_SESSION["elected_class_id"];
}



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "INSERT INTO emploi_class (id_class, ";
$placeholders = "?, ";
$params = array($selected_class_id);


foreach ($values as $dropzone => $value) {
  $column = "valeur_" . substr($dropzone, 8);
  $sql .= $column . ",";
  $placeholders .= "?,";
  $params[] = $value;
}

$sql = rtrim($sql, ",");
$placeholders = rtrim($placeholders, ",");
$sql .= ") VALUES ($placeholders)";


$stmt = $conn->prepare($sql);


$types = str_repeat("s", count($params));
$stmt->bind_param($types, ...$params);


$stmt->execute();

$stmt->close();
$conn->close();
?>
