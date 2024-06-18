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

$sql = "SELECT *FROM professeur
        WHERE id_prof = $id_initiale_prof";        
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nom_prof = $row["nom_prof"];
        $prenom_prof = $row["prenom_prof"];
        $decrp_prof = $row["decriptif_prof"];
        $img_prof = $row["image_prof"];
        $email_prof = $row["email_prof"];
        $city = $row["city"];
        $addresse_prof = $row["addresse_prof"];
    }
} else {
    echo "Aucun résultat trouvé.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous que le formulaire est soumis avec enctype="multipart/form-data"
    
    // Récupérer les données du formulaire
    $adress_prof = isset($_POST["adress"]) ? $_POST["adress"] : NULL;
    $Email = isset($_POST["email"]) ? $_POST["email"] : NULL;
    $City_prof = isset($_POST["City"]) ? $_POST["City"] : NULL;
    $profdescription = isset($_POST["profdescription"]) ? $_POST["profdescription"] : NULL;
    
    $image = $img_prof;
    
    // Vérifier si un fichier a été téléchargé
    if (isset($_FILES["pdf-file"]) && $_FILES["pdf-file"]["error"] == UPLOAD_ERR_OK) {
        // Définir le chemin où le fichier sera déplacé
        $fileExtension = pathinfo($_FILES["pdf-file"]["name"], PATHINFO_EXTENSION);
        $imagePath = "../../image/image_" . $nom_prof . "." . $fileExtension;

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
            $sql = "UPDATE professeur
                    SET addresse_prof = ?,
                        email_prof = ?,
                        city = ?,
                        decriptif_prof = ?,
                        image_prof = ?
                    WHERE id_prof = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $adress_prof,$Email, $City_prof, $profdescription, $image, $id_initiale_prof);
            if ($stmt->execute()) {
                //refreche
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Erreur lors de la mise à jour du profil : " . $stmt->error;
            }
            $stmt->close();
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="../../style/login/Global.css">-->
    <link rel="stylesheet" href="../../style/Coordinateur/update_profil.css">
   <!--< <link rel="stylesheet" href="../../style/prof/PRoFIl.css">-->

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
                        <input type="text" class="form-control" name="email" placeholder="Change email" value="<?php echo $email_prof; ?>"/>
                       </div>    
                       <div class="col-md-8">
                      <label for="">Adresse:</label>
                        <input type="text" class="form-control" name="adress" placeholder="Adress" value="<?php echo $addresse_prof; ?>"/>
                      </div> 
                    </div> 

                    <div class="place">
                       <div class="col-md-3">
                        <label for=""> Ville:</label>
                            <input type="text" class="form-control" name="City"  placeholder="City" value="<?php echo $city; ?>"/>
                        </div>    
                      <div class="col-md-9">
                      <label for="">Description:</label>
                      <input type="text" class="form-control" name="profdescription" placeholder="modifier votre description"value="<?php echo $decrp_prof; ?>"/>
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
                if($img_prof==NULL){
                    echo' <div class="imgprof"></div>';
                }else{
                echo ' <img class="imgprof" src="' . $img_prof . '" alt="Image Professeur">';
//??
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
