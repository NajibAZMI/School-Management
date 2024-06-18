<?php  
session_start();
// Inclure la bibliothèque FPDF
require('../../fpdf1/fpdf.php');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";

$conn = new mysqli($servername, $username, $password, $dbname);
$idetud=$_SESSION['id_etudiant'];
//id classe
$sql = "SELECT id_classe FROM etudiant WHERE id_etudiant = $idetud";
$result_id = $conn->query($sql);
$row_id=$result_id->fetch_assoc();
$id_classe=$row_id['id_classe'];

// Ajout de la nouvelle requête SQL
$sql_emploi = "SELECT * FROM emploi_class
        WHERE id_class = '$id_classe'
        ORDER BY id_emploi_classe DESC
        LIMIT 1";

$result_emploi = $conn->query($sql_emploi);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql_nom_classe="SELECT nom_classe FROM classe WHERE id_classe=$id_classe"  ; 
$result_nomclasse = $conn->query($sql_nom_classe);
$row_nomclasse=$result_nomclasse->fetch_assoc();
$nom_classe=$row_nomclasse['nom_classe'];
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
        $this->Cell(0, 10, 'Emploi du temps - ' . $GLOBALS['nom_classe'], 0, 1, 'C');//comment centre ce titre
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



if ($result_emploi->num_rows > 0) {
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
                    if ($row = $result_emploi->fetch_assoc()) {
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
$nom_fichier = 'emploi_du_temps_classe_' . $nom_classe. '.pdf';

// Sortie du PDF en fonction du type de sortie (I = navigateur, D = téléchargement, F = sauvegarde sur le serveur, S = retourne le document en tant que chaîne)
$pdf->Output($nom_fichier, 'D');

// Fermer la connexion à la base de données
$conn->close();
?>
