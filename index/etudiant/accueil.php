<?php
session_start();

if (!isset($_SESSION["id_etudiant"])) {
    header('Location: ../login/login.php');
    exit();
}
$idetudiant = $_SESSION["id_etudiant"];


$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ensahservice";

// Connexion à la base de données
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
$query = "SELECT nom_etudiant FROM etudiant WHERE id_etudiant='$idetudiant '"; 
$result=$conn->query($query);

if ($result && $result->num_rows > 0) {
 $row = $result->fetch_assoc();
 $name = $row['nom_etudiant'];
} else {
 $name = "Unknown";
}

$query_professeurs = "SELECT COUNT(*) AS total_professeurs FROM professeur";
$result_professeurs = $conn->query($query_professeurs);

if ($result_professeurs && $result_professeurs->num_rows > 0) {
    $row_professeurs = $result_professeurs->fetch_assoc();
    $total_professeurs = $row_professeurs['total_professeurs'];
} else {
    $total_professeurs = 0;
}
$query_etudiants = "SELECT COUNT(*) AS total_etudiants FROM etudiant";
$result_etudiants = $conn->query($query_etudiants);

if ($result_etudiants && $result_etudiants->num_rows > 0) {
    $row_etudiants = $result_etudiants->fetch_assoc();
    $total_etudiants = $row_etudiants['total_etudiants'];
} else {
    $total_etudiants = 0;
}$query_classe = "SELECT COUNT(*) AS total_classe FROM classe";
$result_classe = $conn->query($query_classe);

if ($result_classe && $result_classe->num_rows > 0) {
    $row_classe = $result_classe->fetch_assoc();
    $total_classe = $row_classe['total_classe'];
} else {
    $total_classe = 0;
}$query_filieres = "SELECT COUNT(*) AS total_filieres FROM filiere";
$result_filieres = $conn->query($query_filieres);

if ($result_filieres && $result_filieres->num_rows > 0) {
    $row_filieres = $result_filieres->fetch_assoc();
    $total_filieres = $row_filieres['total_filieres'];
} else {
    $total_filieres = 0;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
   <!-- <link rel="stylesheet" href="../../style/login/Global.css">-->
    <link rel="stylesheet" href="../../style/etudiant/accueiL.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                    <a href="module_notes.php">Note/module</a>
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
                       <button type="submit" class="searchButton"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                  <div class="lineshearch"></div>
            </li>
            <li>
            <div class="navbar_Horaizontal_column_2">
            <div class="calendrier_navcol"><img src="../../image/calendar.jpg" alt=""></div>
               <div class="affich_date"> <?php echo date("Y-m-d");?></div>

                        </div>
            </li>
            <li>
            <button class="button_profil">
                             <div class="name"><?php echo"Mr.$name" ?></div>
                            </button>
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
            <li class="notif">
                <a href="notifications.php" class="notification">
                    <div><img src="" alt=""></div>
                    <span class="badge">3</span>
                </a>
            </li>
           </ul>   
        </nav>

        <div class="autre">
                       <ul class="autre_1" >
                        
                            <li class="cours"><a href="Modulenote.php">Module</a></li>
                            <li class="Demandes"><a href="profil.php">Profil</a></li>
                            <li class="Emplois"><a href="emploi.php">Emploi</a> </li>
                            <li class="Messagerie"><a href="notifications.php">Messagerie</a></li>
                         
                        </ul>
                     <div class="autre_2">
                     <hr>
            <div class="statistiques_title"> <h2>statistiques</h2></div>
            <ul class="statistique">
    <li class="professeur"><?php echo" $total_professeurs <br>"; ?>PROFESSEURS</li>
    <li class="Etudiants"><?php echo"$total_etudiants <br>"; ?>ÉTUDIANTS</li>
    <li class="filiere"><?php echo" $total_filieres <br>"; ?>FILIÉRES</li>
    <li class="Classes"><?php echo"$total_classe <br>"; ?>CLASSES</li>
   </ul>

                      </div>
            </div>
       
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