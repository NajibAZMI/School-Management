<?php 
session_start();
require('../../fpdf1/fpdf.php'); // Inclure la bibliothèque FPDF
$idprof = $_SESSION['id_prof'];

// Connexion à la base de données
$serveur = "localhost"; // Nom du serveur
$utilisateur = "root"; // Nom d'utilisateur MySQL
$motdepasse = ""; // Mot de passe MySQL
$base_de_donnees = "ensahservice"; // Nom de la base de données
$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $base_de_donnees);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("La connexion a échoué : " . $connexion->connect_error);
}

$idclasse = $_POST['id_classe'];

// Fetching the class name
$result = $connexion->query("SELECT nom_classe FROM classe WHERE id_classe='$idclasse'");
$row = $result->fetch_assoc();
$nomclasse = $row['nom_classe'];

// Fetching the coordinator's name
$result = $connexion->query("SELECT nom_prof, prenom_prof FROM professeur WHERE id_prof='$idprof'");
$row = $result->fetch_assoc();
$nomCoordinateur = $row['nom_prof'] . ' ' . $row['prenom_prof'];

$pdf = new FPDF();
$pdf->AddPage();

// Ajouter le header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Universite Abdelmalek Essaadi', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'Ecole Nationale des Sciences Appliquees d\'Al Hoceima', 0, 1, 'C');

// Set background color and text color
$pdf->SetFillColor(200, 220, 255); // Light blue background
$pdf->SetTextColor(0, 0, 0); // Black text

// Displaying class name, coordinator name, and academic year in the same line with borders and background
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(60, 10, "Classe: $nomclasse", 1, 0, 'C', true);
$pdf->Cell(70, 10, "Coordinateur: $nomCoordinateur", 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Annee Universitaire: 2023/2024', 1, 1, 'C', true);

// Dessiner une ligne horizontale après le header
$pdf->SetY(50);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(10); // Espace après la ligne

// Set the total width of the table
$tableWidth = 48 + 48 + 48;

// Calculate the starting X position to center the table
$startX = ($pdf->GetPageWidth() - $tableWidth) / 2;

// Continuer avec le reste du contenu du document
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX($startX); // Set X position for the table header
$pdf->Cell(48, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell(48, 10, 'Nom', 1, 0, 'C', true);
$pdf->Cell(48, 10, 'Prenom', 1, 1, 'C', true);

$result = $connexion->query("SELECT id_etudiant, nom_etudiant, prenom_etudiant FROM etudiant WHERE id_classe='$idclasse'");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id_etudiant = $row['id_etudiant'];
        $nom_etudiant = $row['nom_etudiant'];
        $prenom_etudiant = $row['prenom_etudiant'];

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($startX); // Set X position for each row
        $pdf->Cell(48, 7, $id_etudiant, 1);
        $pdf->Cell(48, 7, $nom_etudiant, 1);
        $pdf->Cell(48, 7, $prenom_etudiant, 1);
        $pdf->Ln(); // Aller à la ligne
    }
} else {
    $pdf->SetX($startX); // Set X position for the "no student" message
    $pdf->Cell(144, 10, 'Aucun étudiant trouvé.', 1, 1, 'C', true);
}

// Envoyer le PDF en téléchargement
$pdf->Output('I', 'liste_etudiants.pdf'); // 'I' affiche dans le navigateur

// Fermer la connexion à la base de données
$connexion->close();
?>