<?php
session_start();
// Inclure la bibliothèque FPDF
require('../../fpdf1/fpdf.php');
$id_prof = $_SESSION['id_prof'];

// Créer une nouvelle classe PDF
class PDF extends FPDF
{
    // En-tête
    function Header()
{
$image = '../../image/t1.png'; 


    $header_height = 20; 
     $this->SetFillColor(173, 216, 230); 
    $this->Rect(0, 0, $this->GetPageWidth(), $header_height, 'F');
     $this->SetTextColor(255, 255, 255); 
     $this->SetFont('Arial', 'B', 14);
     $this->Cell(40, 10, $this->Image($image, 10, 0, 20), 0, 0, 'C', false);
     $this->Cell(0, 10, 'Emploi du temps', 0, 1, 'C');
     $this->Ln(5);
}


    // Pied de page
    function Footer()
    {
        // Positionnement à 1,5 cm du bas
        $this->SetY(-15);
        // Police Arial italique 8
        $this->SetFont('Arial', 'I', 8);
        // Numéro de page
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Créer une instance de PDF
$pdf = new PDF();
$pdf->AddPage();

// Données de l'emploi du temps
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les données de l'emploi du temps pour une classe donnée (remplacez "id_classe" par l'identifiant de la classe)
$sql = "SELECT * FROM emploi WHERE id_prof = $id_prof";
$result = $conn->query($sql);
$sql_nomprof="SELECT nom_prof,prenom_prof FROM professeur WHERE id_prof = $id_prof";
$result_nomprof = $conn->query($sql_nomprof);
$prof_row=$result_nomprof->fetch_assoc();
$nomprof=$prof_row['nom_prof'];
$prenomprof=$prof_row['prenom_prof'];
$pdf->Cell(0, 10, 'Pr.'.$nomprof.'  '.$prenomprof, 0, 1, 'C');
// Générer le tableau
if ($result->num_rows > 0) {
                    // Entête du tableau
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->SetFillColor(173, 216, 230); // Couleur de fond pour l'en-tête du tableau
                    $pdf->Cell(40, 15, 'Jour', 1, 0, 'C', true); // Ajout de la couleur de fond
                    $pdf->Cell(40, 15, '08-10 am', 1, 0, 'C', true);
                    $pdf->Cell(40, 15, '10-12 am', 1, 0, 'C', true);
                    $pdf->Cell(40, 15, '02-04 pm', 1, 0, 'C', true);
                    $pdf->Cell(40, 15, '04-06 pm', 1, 0, 'C', true);
                    $pdf->Ln();
                    
                    $pdf->SetFont('Arial', '', 12);
                    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                    if ($row = $result->fetch_assoc()) {
                        // Réinitialiser $i à zéro pour chaque itération
                        $i = 0;
                        foreach ($jours as $jour) {
                            // Afficher le nom du jour
                            $pdf->SetFillColor(240, 248, 255); // Couleur de fond pour les cellules des jours
                            $pdf->Cell(40, 15, $jour, 1, 0, 'C', true); // Ajout de la couleur de fond
                            // Afficher les valeurs pour chaque jour
                            for ($j = 1; $j <= 4; $j++) {
                                $module_id = $row['valeur_' . (($i * 4) + $j)];
                                // Récupérer le nom et le type du module à partir de la table 'module'
                                $module_sql = "SELECT nom_module, type_module FROM module WHERE id_module = $module_id";
                                $module_result = $conn->query($module_sql);
                                if ($module_result && $module_row = $module_result->fetch_assoc()) {
                                    $module_nom = $module_row['nom_module'];
                                    $module_type = $module_row['type_module'];
                                    $pdf->SetFillColor(255, 255, 255); // Couleur de fond pour les cellules des modules
                                    $pdf->Cell(40, 15, $module_nom . ' (' . $module_type . ')', 1, 0, 'C', true);
                                } else {
                                    $pdf->SetFillColor(255, 255, 255); // Couleur de fond pour les cellules vides
                                    $pdf->Cell(40, 15, '', 1, 0, 'C', true); // Si le module n'est pas trouvé, afficher une cellule vide
                                }
                            }
                            $pdf->Ln();
                            $i++;
                        }
                    }
                } else {
                    $pdf->Cell(0, 10, 'Aucun emploi du temps trouvé pour cette classe.', 0, 1);
                }
                
                

// Nom du fichier de sortie
$nom_fichier = 'emploi_du_temps_classe_' . $id_prof . '.pdf';

// Sortie du PDF en fonction du type de sortie (I = navigateur, D = téléchargement, F = sauvegarde sur le serveur, S = retourne le document en tant que chaîne)
$pdf->Output($nom_fichier, 'D');

// Fermer la connexion à la base de données
$conn->close();
?>