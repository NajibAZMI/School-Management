<?php 
session_start();

if (!isset($_SESSION["id_etudiant"])) {
    header('Location: ../login/login.php');
    exit();
}
$idetudiant = $_SESSION["id_etudiant"];


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";

$conn = new mysqli($servername, $username, $password, $dbname);
$query = "SELECT nom_etudiant FROM etudiant WHERE id_etudiant='$idetudiant '"; 
$result=$conn->query($query);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// ID de l'étudiant
$id_etudiant = $_SESSION['id_etudiant']; 

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['nom_etudiant'];
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
    
   <!-- <link rel="stylesheet" href="../../style/login/Global.css">-->
    <link rel="stylesheet" href="../../style/etudiant/module_notes.css">
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
     <article class="article1">

     <div class="title_tab">
      <p>Affichage des Notes</p>
    </div>

<div class="container_tab">   
<?php
// Requête SQL pour récupérer tous les modules de la filière avec les notes de l'étudiant si elles existent
$sql = "SELECT m.nom_module, 
       IFNULL(n.note_module, 'Pas encore') AS note
FROM module m
INNER JOIN module_classe_professeur_filiere mcpf ON m.id_module = mcpf.id_module
LEFT JOIN notes n ON m.id_module = n.id_module AND n.id_etudiant = $id_etudiant
WHERE mcpf.id_filiere = (
    SELECT id_filiere 
    FROM etudiant 
    WHERE id_etudiant = $id_etudiant
) 
AND m.type_module = 'Cour'";
$result = $conn->query($sql);
if (!$result) {
   
    echo "Erreur MySQL : " . $conn->error;
}
// Affichage des résultats dans un tableau HTML
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Module</th>
                <th>Note</th>
                <th>Status</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["nom_module"]."</td>
                <td>".$row["note"]."</td>";
          if($row["note"] >= 10)
             echo "<td>V</td>";
            else
            echo "<td>NV</td>";

           echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Aucun module trouvé pour cet étudiant.";
}
$conn->close();
?>
</div>
    </article>
       
       
     <div class="footer_profil">
            <hr>
          E-sevice &copy; copyright 2024 reserved - author : Hamza.br  && Najib.Azmi
      </div>
    </aside>

    </body>
    </html> 