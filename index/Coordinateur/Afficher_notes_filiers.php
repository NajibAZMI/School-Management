<?php
session_start();



if (!isset($_SESSION["id_prof"])) {
    header('Location: login.php');
    exit();
}
$id_professeur_initial = $_SESSION["id_prof"];


$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ensahservice";

// Connexion à la base de données
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
$query = "SELECT nom_prof FROM professeur WHERE id_prof='$id_professeur_initial'"; 
$result=$conn->query($query);

if ($result && $result->num_rows > 0) {
 $row = $result->fetch_assoc();
 $name = $row['nom_prof'];
} else {
 $name = "Unknown";

}
if (isset($_POST["Affichage_par_module"])) {
    
    if(isset($_POST["id_classe"])) {
        $id_module = $_POST["id_classe"] ;
        $_SESSION["id_classe"] = $id_module;
        header("Location: Affichage_par_module.php");
        exit();
    } else {
        echo "ID du module non transmis.";
        // Traitez le cas où id_module n'est pas transmis correctement
    }
}
$sql = "SELECT filiere_ensg
          FROM professeur
          WHERE id_prof = $id_professeur_initial
         ";
           $result = mysqli_query($conn, $sql);  
           $row=$result->fetch_assoc();
           $id_filiere_initial =$row['filiere_ensg'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="../../style/coordinateur/affichages_des_notes.css">
    

    <title>ENSAH</title>
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
                    <div class="imgEtudinatAbsence"></div>
                    <a href="EtudinatAbsence.php">Emploi Filière</a>  
                </li>
                <li>
                    <div class="consulter_Etudiant"></div>
                    <a href="consulter-liste_Etudiants.php">Consulter liste Etudiants</a>
                </li>
                <li>
                    <div class="imgAffectationM"></div>
                    <a href="AffectationM.php">Affectation des modules</a>
                </li>
                <li>
                    <div class="imgDefiniremploi"></div>
                    <a href="Definiremploi.php">Définir l'emploi</a>
                </li>
                <li>
                    <div class="imgValiderNotes"></div>
                    <a href="Afficher_notes_filiers.php">Notes Filière</a>
                </li>
                <li>
                    <div class="imgFormation"></div>
                    <a href="Formation.php">classe emploi</a>  
                </li>
                <li>
                    <div class="imgNotifications"></div>
                    <a href="notifications.php">Notifications</a>  
                </li>
            </ul>
        </div>
    </nav>
    <aside>
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
                       <!-- <div class="affich_date"> <?php /* echo date("Y-m-d");*/ ?></div> -->
                       <?php echo'  <div class="affich_date" id="current_date"></div> ';?>
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
        <div class="contaier_tab">
        <?php

                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "ensahservice";
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    }   

                    $sql = "SELECT id_classe, nom_classe FROM classe WHERE id_filiere=' $id_filiere_initial'";
                    $result = mysqli_query($conn, $sql);
                
                    if (mysqli_num_rows($result) > 0) {
                        // Affichage du tableau
                        echo "<table border='1'>";
                        echo "<tr><th>Classe</th><th>Affichage Par module </th><th>Délibérations</th></tr>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row["nom_classe"] . "</td>";
                            echo "<td><form action='Affichage_par_module.php' method='post'>";
                            echo "<input type='hidden' name='id_classe' value='" . $row["id_classe"] . "'>";
                            echo "<button type='submit' name='Affichage_par_module'>Clique</button>";
                            echo "</form></td>";
                            echo "<td><form action='Déliberation.php' method='post'>";
                            echo "<input type='hidden' name='id_classe' value='" . $row["id_classe"] . "'>";
                            echo "<button type='submit' name='Délibérations'>Clique</button>";
                            echo "</form></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "Aucune classe trouvée.";
                    }



?>

    
</div>
</article>
<div class="footer_profil">
            <hr>
          E-sevice &copy; copyright 2024 reserved - author : Hamza.br  && Najib.Azmi
      </div>
</aside>




<script>
date = new Date();
year = date.getFullYear();
month = date.getMonth() + 1;
day = date.getDate();
hour = date.getHours();
min = date.getMinutes();
var mois;
switch(month){
case 1:
mois="Jan";
break;
case 2:
mois="Feb";
break;
case 3:
mois="Mar";
break;
case 4:
mois="Apr";
break;
case 5:
mois="May";
break;
case 6:
mois="Jun";
break;
case 7:
mois="Jul";
break;
case 8:
mois="Aug";
break;
case 9:
mois="Sep";
break;
case 10:
mois="Oct";
break;
case 11:
mois="Nov";
break;
case 12:
mois="Dec";
break;
default:       
mois="  ";
}
document.getElementById("current_date").innerHTML = mois + " " + day + ", " + year + " "+hour+ ":" + min;


</script>


</body>
</html>