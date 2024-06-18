<?php 
session_start();
$serveur = "localhost"; 
$utilisateur = "root"; 
$motdepasse = "";
$base_de_donnees = "ensahservice"; 


if (!isset($_SESSION["id_prof"])) {
    header('Location: login.php');
    exit();
}
$id_initial_prof = $_SESSION["id_prof"];


$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $base_de_donnees);
$query = "SELECT nom_prof FROM professeur WHERE id_prof='$id_initial_prof '"; 
$result=$connexion->query($query);

if ($result && $result->num_rows > 0) {
 $row = $result->fetch_assoc();
 $name = $row['nom_prof'];
} else {
 $name = "Unknown";
}


if(isset($_POST['submit'])) {

    $notes = array();
    $idmodule = $_SESSION['id_module'];
    $idprof=$_SESSION['id_prof'];
    
    if(isset($_POST['notes'])) {
        
        $notes = $_POST['notes'];
     
        if(!empty($notes)) {

            foreach($notes as $id_etudiant => $note) {
               
                $id_etudiant = $connexion->real_escape_string($id_etudiant);
                $note = $connexion->real_escape_string($note);

               
                $checkQuery = "SELECT * FROM notes WHERE id_etudiant = '$id_etudiant' AND id_module='$idmodule'";
                $result = $connexion->query($checkQuery);

                if($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $existingNote = $row['note_module'];
                    
                    if(!empty($note)) {
                        $updateQuery = "UPDATE notes SET note_module = '$note' WHERE id_etudiant = '$id_etudiant' AND id_module='$idmodule'";
                        $connexion->query($updateQuery);
                    } else {
                        if($existingNote != 0) {
                           continue;
                        } else {
                           continue;
                        }
                    }
                    
                    
                } else {
                   
                     
                        $insertQuery = "INSERT INTO notes (id_etudiant, id_module, note_module) VALUES ('$id_etudiant', '$idmodule', '$note')";
                        $connexion->query($insertQuery);
                    
                }
            }

          
            header("Location: saisir_modifier.php?status=success");
            exit();
        }
    }
    
   
    header("Location: saisir_modifier.php?status=error");
    exit();
}



?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des étudiants</title>
    <link rel="stylesheet" href="../../style/prof/saIsirmodifier.css">
 
</head>
<body>
<nav class="navbarline">       
        <div class="logo"><p>ENSAH</p></div>
        <div class="linebar"></div>
        <div class="nabar">
            <ul>
                <li>
                   <div class="imgAccueil"></div>
                   <a href="accueil.php">Accueil</a>
                </li>
                <li>
                    <div class="imgEmploi"></div>
                    <a href="emploi.php">Emploi</a>
                </li>
                <li>
                    <div class="imgProfil"></div>
                    <a href="profil.php">Profil</a>
                </li>
                <li>
                    <div class="imgModulenote"></div>
                    <a href="Modulenote.php">Module/note</a>
                </li>
               
                <li>
                    <div class="imgNotifications"></div>
                    <a href="notifications.php">Notifications</a>  
                </li>
            </ul>
        </div>
    </nav>
 <aside class="aside_generale">

    <nav class="navbarcolomne">
           <ul>
            <li>
                <div class="wrap">
                    <div class="search">
                       <input type="text" class="searchTerm" placeholder="Search...">
                       <button type="submit" class="searchButton"></button>
                    </div>
                  </div>
                  <div class="lineshearch"></div>
            </li>
            <li>
            <div class="navbar_Horaizontal_column_2">
                        <div class="calendrier_navcol"><img src="../../image/calendar.jpg" alt=""></div>
                        <div class="affich_date"><?php echo date("Y-m-d"); ?></div>
                           
                        </div>
            </li>
            <li>
            <button class="button_profil">
                             <div class="name"><?php echo"Dr.$name" ?></div>
                           
                            </button>
            </li>

            <li class="notif">
                <a href="notifications.php" class="notification">
                    <div><img src="" alt=""></div>
                    <span class="badge">3</span>
                </a>
            </li>

            <li class="profili">
                <div class="profi1">
                    <button class="but-profi" style="z-index: 10;"><div class="im1"></div></button> 
                      <div class="profi2" style="z-index: 10;">
                        <a href="profil.php" style="z-index: 10;">profil</a>
                        <a href="../login/logout.php" style="z-index: 10;">desconecter</a>
                      </div>
                  </div>
            </li>
           
           </ul>   
        </nav>
<article class="article1">
    <div class="container">
        <div class="ENSAH_TAG">
        <p >Université Abdelmalek Essaadi</p>
        <p>Ecole Nationale des Sciences Appliquées</p>
        <p> d'Al Hoceima</p>
        </div>
        <ul class="infoliste">
        <?php 
        $idmodule = $_SESSION['id_module'];
        $idprof=$_SESSION['id_prof'];
        $result_prof=$connexion->query("SELECT nom_prof,prenom_prof FROM professeur WHERE id_prof='$idprof'");
        $row_prof=$result_prof->fetch_assoc();
        $result_module=$connexion->query("SELECT nom_module FROM module WHERE id_module='$idmodule'");
        $row_module=$result_module->fetch_assoc();
        $nommodule=$row_module['nom_module'];
        $nomprof=$row_prof['nom_prof'];
        $prenomprof=$row_prof['prenom_prof'];
       /* ________________________________________________________________ */
       $result3=$connexion->query("SELECT id_classe FROM module_classe_professeur_filiere WHERE id_prof='$idprof' AND id_module='$idmodule'");
       $row_idclasse=$result3->fetch_assoc();
       $idclasse=$row_idclasse['id_classe'];
       $result_nomclasse=$connexion->query("SELECT nom_classe FROM classe WHERE id_classe='$idclasse'");
       $row_nomclasse=$result_nomclasse->fetch_assoc();
       $nomclasse=$row_nomclasse['nom_classe'];
        ?>
        <li class="nom_prof"><?php echo "Professeur :".$nomprof." ".$prenomprof?></li>
        <li class="module_nom"><?php echo"Module :".$nommodule ?></li>
        <li class="classe"><?php echo"Classe :". $nomclasse?></li>
        <li class="year"><?php echo"Année universitaire : 2023/2024" ?></li>
</ul>

        <div class="row">
            <form action="" method="post">
                <table class="class_table">
                    <thead>
                        <tr> 
                            <th>ID Etudiant</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Note</th>  
                            <th>Nouvelle note </th> <!-- Nouvelle colonne pour la note -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $result = $connexion->query("SELECT id_etudiant, nom_etudiant, prenom_etudiant, email_etudiant FROM etudiant WHERE id_classe='$idclasse'");
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row['id_etudiant'] . '</td>';
                                echo '<td>' . $row['nom_etudiant'] . '</td>';
                                echo '<td>' . $row['prenom_etudiant'] . '</td>'; $idmodule= $_SESSION['id_module'];
                                $result_note = $connexion->query("SELECT note_module FROM notes WHERE id_etudiant='{$row["id_etudiant"]}' AND id_module='$idmodule'");

                                if($result_note->num_rows > 0){
                                    $row1 = $result_note->fetch_assoc();
                                echo '<td>' .$row1['note_module'] . '</td>';
                                }
                                else{
                                    echo '<td>la note n\'a pas encore été saisi,</td>';     
                                }
                                // Formulaire pour saisir la note de l'étudiant
                                echo '<td><input type="number" name="notes['.$row['id_etudiant'].']"  min="0" max="20" ></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="8">Aucun étudiant trouvé.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Bouton de validation pour insérer toutes les notes -->
                <button type="submit" name="submit">Valider</button>
            </form>
            <form action="Notes_PDF.php" method="post">
                <button type="submit" name="téléchargé">téléchargé</button>
            </form>
        </div>
        <div class="row">
            <!-- Reste du contenu de la page... -->
        </div>
    </div> 
    </article>
    
    <div class="footer_profil">
            <hr>
          E-sevice &copy; copyright 2024 reserved - author : Hamza.br  && Najib.Azmi
      </div>
    </aside>
</body>
</html>