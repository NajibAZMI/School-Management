<?php


$values = json_decode($_POST['values'], true);

session_start();

$selected_prof_id = $_SESSION["selected_prof_id"] ?? null;
$selected_filiere_id = $_SESSION["selected_filiere_id"] ?? null;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO module_classe_professeur_filiere (id_module, id_prof, id_filiere) VALUES (?, ?, ?)");

foreach ($values as $dropzone => $moduleIds) {
    foreach ($moduleIds as $moduleId) {
        $stmt->bind_param("sss", $moduleId, $selected_prof_id, $selected_filiere_id);
        $stmt->execute();
    }
}


if ($stmt->error) {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

