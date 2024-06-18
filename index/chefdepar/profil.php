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

// Vérifier la connexion
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM professeur
        WHERE  id_prof = $id_initiale_prof";        
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



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adress_prof = isset($_POST["adress"]) ? $_POST["adress"] : NULL;
    $password_prod = isset($_POST["password"]) ? sha1($_POST["password"]) : NULL;
    $City_prof = isset($_POST["City"]) ? $_POST["City"] : NULL;
    $profdescription = isset($_POST["profdescription"]) ? $_POST["profdescription"] : NULL;

  if (isset($_FILES["pdf-file"]) && $_FILES["pdf-file"]["error"] == UPLOAD_ERR_OK) {
  
         // Définir le chemin où le fichier sera déplacé
         $fileExtension = pathinfo($_FILES["pdf-file"]["name"], PATHINFO_EXTENSION);
         $imagePath = move_uploaded_file($_FILES["pdf-file"]["tmp_name"], "../../image/image_" . $nom_prof . "." . $fileExtension);
 
         if (!$imagePath) {
             // Gérer les erreurs de téléchargement
             $errorCode = $_FILES["pdf-file"]["error"];
             switch ($errorCode) {
                 // Gérer les différents codes d'erreur ici
             }
         } else {
             // Le fichier a été téléchargé avec succès, définir le chemin pour la base de données
             $image = "../../image/image_" . $nom_prof . "." . $fileExtension;
 
             // Préparer et exécuter la requête SQL pour mettre à jour le profil
             $sql = "UPDATE professeur
                     SET addresse_prof = ?,
                         city = ?,
                         decriptif_prof = ?,
                         image_prof = ?
                     WHERE id_prof = ?";
             $stmt = $conn->prepare($sql);
             $stmt->bind_param("sssss", $adress_prof, $City_prof, $profdescription, $image, $id_initiale_prof);
             if ($stmt->execute()) {
                 echo "Mise à jour du profil réussie.";
             } else {
                 echo "Erreur lors de la mise à jour du profil : " . $stmt->error;
             }
         }
     } else {
         echo "Aucune image sélectionnée ou erreur lors du téléchargement.";
    }
 
$sql = "UPDATE professeur 
        SET modepass_prof = '$password_prod' 
        WHERE id_prof = $id_initiale_prof";

    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "" . $conn->error;
    }    
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/chefdepar/PrOfiL.css">

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
                    <div class="imgAfficModules"></div>
                    <a href="AfficModules.php">Affictation des modules</a>
                </li>
                <li>
                    <div class="imgAfficProfesseurs"></div>
                    <a href="AfficProfesseurs.php">Affic des professeurs</a>  
                </li>
                <li>
                    <div class="imgNotifications"></div>
                    <a href="notifications.php">Notifications</a>  
                </li>
            </ul>
        </div>
    </nav>
    
 <aside class="aside_generale" >
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
                             <div class="name"><?php echo"Dr.$nom_prof" ?></div>
                           
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
                    <!--
              <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" placeholder="first name" value=""></div>            
                                <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text" class="form-control" placeholder="enter phone number" value=""></div>
            -->
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
           echo"<div class='profile-item'>
                <label for='filliere_ensg'>Filliere_ensg:</label>
                <span id='filliere_ensg'><?php echo'htmlspecialchars($filiere_ensg)'; ?></span>
            </div>";
            }
        ?>
           </div>
     </div>
                    
                </aside>
            <aside class="aside2">
                <?php
                if($img_prof==NULL){
                    echo' <div class="imgprof"></div>';
                }else{
                echo ' <img class="imgprof" src="' . $img_prof . '" alt="Image Professeur">';//?? --->  V
                }
                ?>
                <div class="biblio">
                    <h1><?php echo "$prenom_prof"." "."$nom_prof"; ?></h1>
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
             E-sevice &copy; copyright 2024 reserved - author : Hamza.br  && Najib.Azmi
            </div>
            <?php $conn->close(); ?>
    </aside>
    
</body>
</html>