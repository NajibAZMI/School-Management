
<?php
// Récupérer les valeurs envoyées par JavaScript
$values = json_decode($_POST['values'], true);

// Récupérer la valeur de selected_prof_id
//if (isset($_GET['selected_prof_id'])) {
//  $selected_prof_id= $_GET['selected_prof_id']; // Récupération de la valeur de idprof
//}

session_start();

if (isset($_SESSION['idprof'])) {
  $selected_prof_id = $_SESSION['idprof']; // Récupération de la valeur de idprof
}

// Effectuer la connexion à la base de données MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql = "INSERT INTO emploi (id_prof, ";
$placeholders = "?, ";
$params = array($selected_prof_id);

// Générer les colonnes et les placeholders dynamiquement
foreach ($values as $dropzone => $value) {
  $column = "valeur_" . substr($dropzone, 8);
  $sql .= $column . ",";
  $placeholders .= "?,";
  $params[] = $value;
}

$sql = rtrim($sql, ",");
$placeholders = rtrim($placeholders, ",");
$sql .= ") VALUES ($placeholders)";


// Préparer la requête
$stmt = $conn->prepare($sql);

// Binder les valeurs aux paramètres
$types = str_repeat("s", count($params));
$stmt->bind_param($types, ...$params);

// Exécuter la requête
$stmt->execute();

$stmt->close();
$conn->close();
?>
