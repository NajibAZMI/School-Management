<?php
session_start();
require('../../fpdf1/fpdf.php'); // Inclure la bibliothèque FPDF
$idprof=$_SESSION['id_prof'];
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
$idmodule = $_SESSION['id_module'];
$idprof = $_SESSION['id_prof'];
$result_prof = $connexion->query("SELECT nom_prof, prenom_prof FROM professeur WHERE id_prof='$idprof'");
$row_prof = $result_prof->fetch_assoc();
$result_module = $connexion->query("SELECT nom_module FROM module WHERE id_module='$idmodule'");
$row_module = $result_module->fetch_assoc();
$nommodule = $row_module['nom_module'];
$nomprof = $row_prof['nom_prof'];
$prenomprof = $row_prof['prenom_prof'];

$result3 = $connexion->query("SELECT id_classe FROM module_classe_professeur_filiere WHERE id_prof='$idprof' AND id_module='$idmodule'");
$row_idclasse = $result3->fetch_assoc();
$idclasse = $row_idclasse['id_classe'];
$result_nomclasse = $connexion->query("SELECT nom_classe FROM classe WHERE id_classe='$idclasse'");
$row_nomclasse = $result_nomclasse->fetch_assoc();
$nomclasse = $row_nomclasse['nom_classe'];
$pdf = new FPDF();
$pdf->AddPage();

// Ajouter votre contenu dans l'en-tête
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'Universite Abdelmalek Essaadi', 0, 1, 'C');
$pdf->Cell(0, 10, 'Ecole Nationale des Sciences Appliquees', 0, 1, 'C');
$pdf->Cell(0, 10, "d'Al Hoceima", 0, 1, 'C');
$pdf->Ln(); // Aller à la ligne


$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFont('Arial', '', 10);

/***************** */
$pdf->SetFillColor(200, 220, 255); // Bleu clair
    $pdf->SetFont('Arial', '', 10);
    
    // Afficher chaque information dans une cellule distincte avec un espace entre elles
    $pdf->Cell(48, 10, "AU: 2023/2024", 1, 0, 'C', true); // Avec fond
$pdf->Cell(48, 10, "Classe: $nomclasse", 1, 0, 'C', true); // Avec fond
$pdf->Cell(48, 10, "Professeur: $nomprof $prenomprof", 1, 0, 'C', true); // Avec fond
$pdf->Cell(48, 10, "Module: $nommodule", 1, 0, 'C', true); // Avec fond

/************************** */
//FIN d'en-etet
// Dessiner une ligne horizontale après les informations
$pdf->SetY(45); 
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(20);
/************************** */

$result = $connexion->query("SELECT id_etudiant, nom_etudiant, prenom_etudiant FROM etudiant WHERE id_classe='$idclasse'  ");
if ($result && $result->num_rows > 0) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(48, 10, 'ID', 1);
    $pdf->Cell(48, 10, 'Nom', 1);
    $pdf->Cell(48, 10, 'Prénom', 1);
    $pdf->Cell(48, 10, 'Note', 1);
    $pdf->Ln(); // Aller à la ligne

    while ($row = $result->fetch_assoc()) {
        $id_etudiant = $row['id_etudiant'];
        $nom_etudiant = $row['nom_etudiant'];
        $prenom_etudiant = $row['prenom_etudiant'];

        // Récupérer la note de l'étudiant
        $note_query = $connexion->query("SELECT note_module FROM notes WHERE id_etudiant='$id_etudiant' AND id_module='$idmodule'");
        $note = ($note_query && $note_query->num_rows > 0) ? $note_query->fetch_assoc()['note_module'] : 'Non définie';

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(48, 7, $id_etudiant, 1);
        $pdf->Cell(48, 7, $nom_etudiant, 1);
        $pdf->Cell(48, 7, $prenom_etudiant, 1);
        $pdf->Cell(48, 7, $note, 1);
        $pdf->Ln(); // Aller à la ligne
    }
} else {
    $pdf->Cell(0, 10, 'Aucun étudiant trouvé.', 0, 1);
}

// Envoyer le PDF en téléchargement
$pdf->Output('D', 'liste_etudiants.pdf');

// Fermer la connexion à la base de données
$connexion->close();

?>
