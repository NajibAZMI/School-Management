<?php
session_start();
if (!isset($_SESSION["id_prof"])) {
    header('Location: login.php');
    exit();
}
$id_initiale_prof = $_SESSION["id_prof"];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM professeur
        WHERE id_prof = $id_initiale_prof";        
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nom_prof = $row["nom_prof"];
        $prenom_prof = $row["prenom_prof"];
        $email_prof = $row["email_prof"];
        $role = $row["role"];
        $city = $row["city"];
        $addresse_prof = $row["addresse_prof"];
        $specialite = $row["specialite"];
        $filiere_ensg = $row["filiere_ensg"];
        $decrp_prof = $row["decriptif_prof"];
        $img_prof = $row["image_prof"];
    }
} else {
    echo "Aucun résultat trouvé.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
 <!--   <link rel="stylesheet" href="../../style/login/pROfil_Pr.css">
    <link rel="stylesheet" href="../../style/prof/PRoFIl.css">-->
    <link rel="stylesheet" href="../../style/prof/ProfilVrai.css">
    <title>ENSAH - Profil</title>
</head>
<body>
    <nav class="navbarline">       
        <div class="logo"><p>ENSAH</p></div>
        <div class="linebar"></div>
        <div class="nabar">
            <ul>
                <li><div class="imgAccueil"></div><a href="accueil.php">Accueil</a></li>
                <li><div class="imgEmploi"></div><a href="emploi.php">Emploi</a></li>
                <li><div class="imgProfil"></div><a href="profil.php">Profil</a></li>
                <li><div class="imgModulenote"></div><a href="Modulenote.php">Module/note</a></li>
                <li><div class="imgNotifications"></div><a href="notifications.php">Notifications</a></li>
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
                        <div class="name"><?php echo "Dr. $nom_prof"; ?></div>
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
                            <a href="profil.php" style="z-index: 10;">Profil</a>
                            <a href="../login/logout.php" style="z-index: 10;">Déconnecter</a>
                        </div>
                    </div>
                </li>
            </ul>   
        </nav> 
 <article class="article1">        
                <aside class="aside1">
       <div class="container">
          <div class="profile-card">
            <h1>Profile Information</h1>
            <div class="profile-item">
                <label for="nom">Nom:</label>
                <span id="nom"><?php echo htmlspecialchars($nom_prof); ?></span>
            </div>
            <div class="profile-item">
                <label for="prenom">Prenom:</label>
                <span id="prenom"><?php echo htmlspecialchars($prenom_prof); ?></span>
            </div>
            <div class="profile-item">
                <label for="email">Email:</label>
                <span id="email"><?php echo htmlspecialchars($email_prof); ?></span>
            </div>
            <div class="profile-item">
                <label for="address">Address:</label>
                <span id="address"><?php echo htmlspecialchars($addresse_prof); ?></span>
            </div>
            <div class="profile-item">
                <label for="city">Ville::</label>
                <span id="city"><?php echo htmlspecialchars($city); ?></span>
            </div>
            <div class="profile-item">
                <label for="role">Role:</label>
                <span id="role"><?php echo htmlspecialchars($role); ?></span>
            </div>
            <div class="profile-item">
                <label for="specialite">Specialite:</label>
                <span id="specialite"><?php echo htmlspecialchars($specialite); ?></span>
            </div>
        <?php if($role == 'coordinateur'){
           echo' <div class="profile-item">
                <label for="filliere_ensg">Filliere_ensg:</label>
                <span id="filliere_ensg"><?php echo htmlspecialchars($filiere_ensg); ?></span>
            </div>';
            }
        ?>
           </div>
     </div>
                    
                </aside>
             <aside class="aside2">
                    <?php
                    if($img_prof == NULL){
                        echo '<div class="imgprof"></div>';
                    } else {
                        echo '<img class="imgprof" src="' . $img_prof . '" alt="Image Professeur">';
                    }
                    ?>
                 <div class="biblio">
                    <h1><?php echo "$prenom_prof"." __ "."$nom_prof"; ?></h1>
                    <div class="discripprof">
                        <?php 
                        if($decrp_prof== NULL){
                          echo'  <p>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                                    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                                    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                               </p>';
                        }
                        else{
                            echo '<p>
                             '. $decrp_prof .'
                            </p>';
                        }
                        ?>
                   </div>
                </div> 
                    <div class="edit_button">
                        <a href="update_profil.php" class="btn-primary">Modifier le Profil</a>
                    </div>
                </aside>  
        </article>
        <div class="footer_profil">
                <hr>
                E-service &copy; copyright 2024 reserved - author : Hamza.br  && Najib.Azmi
            </div>
    </aside>
    <?php $conn->close(); ?>
</body>
</html>
