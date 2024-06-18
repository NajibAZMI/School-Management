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
           WHERE id_etudiant='$idetudiant'"; 
$result=$conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nom_etu = $row["nom_etudiant"];
        $prenom_etu = $row["prenom_etudiant"];
        $email_etu = $row["email_etudiant"];
        $city_etu = $row["city_etu"];
        $addresse_etu = $row["adress_etu"];
        $decrp_etu = $row["descriptif_etu"];
        $img_etud = $row["image_etudiant"];
        $id_classe = $row["id_classe"];
    }
} else {
    echo "Aucun résultat trouvé.";
}





/************************************************************************************* */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous que le formulaire est soumis avec enctype="multipart/form-data"
    
    // Récupérer les données du formulaire
    $nv_adress= isset($_POST["adress"]) ? $_POST["adress"] : NULL;
    $nv_Email = isset($_POST["email"]) ? $_POST["email"] : NULL;
    $nv_City = isset($_POST["City"]) ? $_POST["City"] : NULL;
    $nv_description = isset($_POST["etuddescription"]) ? $_POST["etuddescription"] : NULL;
    
    $image = $img_etud;
    
    // Vérifier si un fichier a été téléchargé
    if (isset($_FILES["pdf-file"]) && $_FILES["pdf-file"]["error"] == UPLOAD_ERR_OK) {
        // Définir le chemin où le fichier sera déplacé
        $fileExtension = pathinfo($_FILES["pdf-file"]["name"], PATHINFO_EXTENSION);
        $imagePath = "../../image/image_" . $nom_etu . "." . $fileExtension;

        if (move_uploaded_file($_FILES["pdf-file"]["tmp_name"],$imagePath)){
            // Le fichier a été téléchargé avec succès, définir le chemin pour la base de données
            $image = $imagePath;
        } else {
             // Gérer les erreurs de téléchargement
             $errorCode = $_FILES["pdf-file"]["error"];
             switch ($errorCode) {
                 // Gérer les différents codes d'erreur ici
                 // Par exemple :
                 // case UPLOAD_ERR_INI_SIZE:
                 //     echo "Le fichier téléchargé dépasse la directive upload_max_filesize dans php.ini.";
                 //     break;
                 // case UPLOAD_ERR_FORM_SIZE:
                 //     echo "Le fichier téléchargé dépasse la directive MAX_FILE_SIZE qui a été spécifiée dans le formulaire HTML.";
                 //     break;
                 // etc../image/image_" . $nom_prof . "." . $fileExtension;
                 }
            }
     }

            // Préparer et exécuter la requête SQL pour mettre à jour le profil
            $sql = "UPDATE etudiant
                    SET adress_etu = ?,
                    email_etudiant = ?,
                        city_etu = ?,
                        descriptif_etu = ?,
                        image_etudiant = ?
                    WHERE id_etudiant= ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $nv_adress,$nv_Email, $nv_City, $nv_description, $image, $idetudiant);

            if ($stmt->execute()) {
                //refreche
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Erreur lors de la mise à jour du profil : " . $stmt->error;
            }
            
            $stmt->close();
    }
/**************************************************************************************************** */
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
   <!-- <link rel="stylesheet" href="../../style/login/Global.css">-->
    <link rel="stylesheet" href="../../style/etudiant/updateprofil.css">
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

    <aside class="aside_generale">

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
         <div class="contenu">
             <aside class="aside1">
                <div class="editprof">
                    <p class="logprof">Modifier Profil</p>
                </div>
             <div class="enterinfo">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="upperinfo">
                    <!--<div class="name">
                        <div class="col-md-5"><label  class="form-control"  value=""><?php echo $prenom_prof; ?></label></div>  
                        <div class="col-md-5"><label  class="form-control"  value=""><?php echo $nom_prof; ?></label></div>  
                    </div>-->

                    <div class="adres">
                       <div class="col-md-8">
                            <label for="">Email:</label>
                        <input type="text" class="form-control" name="email" placeholder="Change email" value="<?php echo $email_etu; ?>"/>
                       </div>    
                       <div class="col-md-8">
                      <label for="">Adresse:</label>
                        <input type="text" class="form-control" name="adress" placeholder="Adress" value="<?php echo $addresse_etu; ?>"/>
                      </div> 
                    </div> 

                    <div class="place">
                       <div class="col-md-3">
                        <label for=""> Ville:</label>
                            <input type="text" class="form-control" name="City"  placeholder="City" value="<?php echo $city_etu; ?>"/>
                        </div>    
                      <div class="col-md-9">
                      <label for="">Description:</label>
                      <input type="text" class="form-control" name="etuddescription" placeholder="modifier votre description"value="<?php echo $decrp_etu; ?>"/>
                      </div> 

                    </div>
                 </div>
                    <div class="line1"></div>
                    <div class="underinfo">
                        <div class="imageinsert">
                          <input type="hidden" name="MAX_FILE_SISE" value="100000"/>
                          <input class="imginput" type="file" id="pdf-file" name="pdf-file" accept="image/*"/>
                       </div>
                       <div class="inscrip"><input class="input1" type="submit" value="UPDATE PROFILE"></div>
                    </div>
        </form>
                </div>

             </aside>
             <aside class="aside2">
                <?php
                if($img_etud==NULL){
                    echo' <div class="imgetud"></div>';
                }else{
                echo ' <img class="imgetud" src="' . $img_etud . '" alt="Image Etudiant">';
//??
                }
                ?>
                <div class="biblio">
                    <h1><?php echo "$prenom_etu"." "."$nom_etu"; ?></h1>
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
               </aside>  
          </div>              
            <?php $conn->close(); ?>
        </article>
       
     <div class="footer_profil">
            <hr>
          E-sevice &copy; copyright 2024 reserved - author : Hamza.br  && Najib.Azmi
      </div>
    </aside>


</body>
</html>