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

$query = "SELECT * FROM etudiant 
           WHERE id_etudiant='$idetudiant '"; 
$result=$conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nom_etu = $row["nom_etudiant"];
        $prenom_etu = $row["prenom_etudiant"];
        $email_etud = $row["email_etudiant"];
        $city_etu = $row["city_etu"];
        $addresse_etu = $row["adress_etu"];
        $decrp_etu = $row["descriptif_etu"];
        $img_etud = $row["image_etudiant"];
        $id_classe = $row["id_classe"];
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
    
   <!-- <link rel="stylesheet" href="../../style/login/Global.css">-->
    <link rel="stylesheet" href="../../style/etudiant/profil.css">
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

    <aside  class="aside_generale">

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
                             <div class="name"><?php echo"Mr.$nom_etu" ?></div> 
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
                <aside class="aside1">
       <div class="container">
          <div class="profile-card">
            <h1>Profile Information</h1>
            <div class="profile-item">
                <label for="nom">Nom:</label>
                <span id="nom"><?php echo htmlspecialchars($nom_etu); ?></span>
            </div>
            <div class="profile-item">
                <label for="prenom">Prenom:</label>
                <span id="prenom"><?php echo htmlspecialchars($prenom_etu); ?></span>
            </div>
            <div class="profile-item">
                <label for="email">Email:</label>
                <span id="email"><?php echo htmlspecialchars($email_etud); ?></span>
            </div>
            <div class="profile-item">
                <label for="address">Address:</label>
                <span id="address"><?php echo htmlspecialchars($addresse_etu); ?></span>
            </div>
            <div class="profile-item">
                <label for="city">Ville::</label>
                <span id="city"><?php echo htmlspecialchars($city_etu); ?></span>
            </div>
            <?php
            $query = "SELECT nom_classe FROM classe WHERE id_classe=' $id_classe '"; 
            $result=$conn->query($query);
            
            if ($result && $result->num_rows > 0) {
             $row = $result->fetch_assoc();
             $name_classe = $row['nom_classe'];
            } else {
             $name = "Unknown";
            }
            
            ?>
            <div class="profile-item">
                <label for="nom_classe">Classe:</label>
                <span id="nom_classe"><?php echo htmlspecialchars($name_classe); ?></span>
            </div>
       
           </div>
     </div>
                    
                </aside>
             <aside class="aside2">
                    <?php
                    if($img_etud  == NULL){
                        echo '<div class="imgetud"></div>';
                    } else {
                        echo '<img class="imgetud" src="' . $img_etud . '" alt="Image Etudiant">';
                    }
                    ?>
                 <div class="biblio">
                    <h1><?php echo "$prenom_etu"." __ "."$nom_etu"; ?></h1>
                    <div class="discripetud">
                        <?php 
                        if($decrp_etu== NULL){
                          echo'  <p>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                                    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                                    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                               </p>';
                        }
                        else{
                            echo '<p>
                             '. $decrp_etu .'
                            </p>';
                        }
                        ?>
                   </div>
                </div> 
                    <div class="edit_button">
                        <a href="updateprofil.php" class="btn-primary">Modifier le Profil</a>
                    </div>
                </aside>  
        </article>
     
       
     <div class="footer_profil">
            <hr>
          E-sevice &copy; copyright 2024 reserved - author : Hamza.br  && Najib.Azmi
      </div>
    </aside>
</body>
</html>