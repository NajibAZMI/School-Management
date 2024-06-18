
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
  <!--  <link rel="stylesheet" href="../../style/login/global.css">  -->
  <link rel="stylesheet" href="../../style/Coordinateur/EMPLOI.css">

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
        <div class="title_emploi">
            <p>Emploi du temps</p>
            </div>
            <div class="tableemploi">
                 <div class="container">
                    <div class="row">
                    <div class="col-md-12">
                    <div class="schedule-table">
                    <table class="table-bg-white">
                    <thead>
                    <tr>
                    <th>Emploi</th>
                    <th>08-10 am</th>
                    <th>10-12 am</th>
                    <th>02-04 pm</th>
                    <th class="last">04-06 pm</th>
                    </tr>
                    </thead>
                    <tbody>
                        
<?php

//session_start();
$id_initial_prof = $_SESSION["id_prof"];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM emploi
        WHERE id_prof = $id_initial_prof";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
$row = $result->fetch_assoc();

$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
for($i=0; $i<6; $i++){
    echo'<tr>';
    echo'<td class="day">'. $jours[$i] .'</td>';

    $j = 0;
   while ($j <4)  {
       $k= $j+4*$i+1;
       $valeur = $row['valeur_' . $k];
       if($valeur!=NULL){
        if($valeur == $row['valeur_' . $k+1] && ($j==0 || $j==2)){
         if($valeur == $row['valeur_' . $k+1] && ($j==0)){
            $sql = "SELECT nom_module,type_module FROM module
                    WHERE id_module = '$valeur'";
             $result = $conn->query($sql);
             if ($result->num_rows > 0) {
                while ($mod = $result->fetch_assoc()) {
                   $nom_module = $mod["nom_module"];
                   $type_module = $mod["type_module"];
                }
             }
             echo'<td class="active" colspan="2">
                 <h4>'.$nom_module.'</h4>
                 <p>'.$type_module.'</p>
                 <div class="hover">
                     <h4>'.$nom_module.'</h4>
                     <p>'.$type_module.'</p>
                 </div>
              </td>';
         }
         if($valeur == $row['valeur_' . $k+1] && ($j==2)){
            $sql = "SELECT nom_module,type_module FROM module
                    WHERE id_module = '$valeur'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while ($mod = $result->fetch_assoc()) {
                  $nom_module = $mod["nom_module"];
                  $type_module = $mod["type_module"];
              }
            }
            echo'<td class="active" colspan="2">
                <h4>'.$nom_module.'</h4>
                <p>'.$type_module.'</p>
                <div class="hover">
                    <h4>'.$nom_module.'</h4>
                    <p>'.$type_module.'</p>
                </div>
            </td>';
         }
         $j++;
        }
         else{

       
       $sql = "SELECT nom_module,type_module FROM module
        WHERE id_module = '$valeur'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($mod = $result->fetch_assoc()) {
                $nom_module = $mod["nom_module"];
                $type_module = $mod["type_module"];
            }
        }
        echo'<td class="active">
                <h4>'.$nom_module.'</h4>
                <p>'.$type_module.'</p>
                <div class="hover">
                    <h4>'.$nom_module.'</h4>
                    <p>'.$type_module.'</p>
                </div>
             </td>';
       }
    }
       else{
        echo'<td></td>';
       }

       $j++;
   }
   echo'</tr>'; 
 }
} else {
    $jours = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    foreach ($jours as $jour) {
        echo '<tr>';
        echo '<td class="day">' . $jour . '</td>';
        for ($i = 0; $i < 4; $i++) {
            echo '<td></td>';
        }
        echo '</tr>';
    }
    
}
?>
<?php $conn->close(); ?>

                    </tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                    </div>
            </div>

             <div class="emploi">
                <p>Télécharger votre emploi du temps</p>
                
                <div class="notestud" >
            <form action="emploi_pdf.php" method="post">
             <button type="submit" name="téléchargé" class="téléchargé">Télécharger</button>
            </form>
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