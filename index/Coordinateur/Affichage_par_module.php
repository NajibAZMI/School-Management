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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT nom_prof, image_prof FROM professeur WHERE id_prof='$id_professeur_initial'";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['nom_prof'];
    $src = $row['image_prof'];
} 

if (isset($_POST['id_classe'])) {
    $id_classe = $_POST['id_classe'];
    $query = "SELECT nom_classe FROM classe WHERE id_classe='$id_classe'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nom_classe = $row['nom_classe'];
    } else {
        $nom_classe = "Unknown";
    }

    // Récupérer les modules de la classe
    $query = "SELECT m.id_module, m.nom_module, m.type_module
              FROM module m 
              JOIN module_classe_professeur_filiere mcpf ON m.id_module = mcpf.id_module 
              WHERE mcpf.id_classe = '$id_classe'";
    $result = $conn->query($query);

    // Debugging: Vérifier les résultats de la requête
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
} else {
    header('Location: affichage_des_notes.php');
    exit();
}

if (isset($_POST["consulter_notes_module"])) {
    if (isset($_POST["id_module"])) {
        $id_module = $_POST["id_module"];
        $_SESSION["id_module"] = $id_module;
        header("Location: consulter_module.php");
        exit();
    } else {
        echo "ID du module non transmis.";
        
    }
}

if (isset($_POST["telecharger_notes_module_filiere"])) {
    if (isset($_POST["id_module"])) {
        $id_module = $_POST["id_module"];
        $_SESSION["id_module"] = $id_module;
        header("Location: telecharger_notes_module_filiere.php");
        exit();
    } else {
        echo "ID du module non transmis.";
        // Traitez le cas où id_module n'est pas transmis correctement
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/coordinateur/affichages_des_notes.css">
    <title>Modules de la classe <?php echo htmlspecialchars($nom_classe); ?></title>
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
                <div class="affich_date" id="current_date"></div>
            </div>
        </li>
        <li>
            <button class="button_profil">
                <div class="name"><?php echo "Dr.$name"; ?></div>
                <img src="../image/<?php echo htmlspecialchars($src); ?>" alt="My img">
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
                <button class="but-profi"><div class="im1"></div></button> 
                <div class="profi2">
                    <a href="profil.php">profil</a>
                    <a href="../login/logout.php">déconnecter</a>
                </div>
            </div>
        </li>
       </ul>   
    </nav>

    <article class="article1">
        <h1>Modules de la classe <?php echo htmlspecialchars($nom_classe); ?></h1>
        <div class="contaier_tab">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Nom du module</th><th>Consulter</th><th>Télécharger</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                if($row['type_module']=='Cour'){
                    $id_module=$row["id_module"];
                    echo "<tr>";
                    echo "<td>" . $row["nom_module"]. "</td>";
                    echo "<td><form action='consulter_module.php' method='post'>";
                    echo "<input type='hidden' name='id_module' value='" . $id_module. "'>";
                    echo "<button type='submit' name='consulter_notes_module'>Consulter $id_module</button>";
                    echo "</form></td>";
                    echo "<td><form action='telecharger_notes_module_filiere.php' method='post'>";
                    echo "<input type='hidden' name='id_module' value='" . $id_module. "'>";
                    echo "<button type='submit' name='telecharger_notes_module_filiere'>Télécharger</button>";
                    echo "</form></td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        } else {
            echo "Aucun module trouvé pour cette classe.";
        }
        ?>
        </div>
    </article>
         
</aside>
<script>
    // Script to show the current date
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hour = date.getHours();
    var min = date.getMinutes();
    var mois;
    switch(month){
        case 1: mois="Jan"; break;
        case 2: mois="Feb"; break;
        case 3: mois="Mar"; break;
        case 4: mois="Apr"; break;
        case 5: mois="May"; break;
        case 6: mois="Jun"; break;
        case 7: mois="Jul"; break;
        case 8: mois="Aug"; break;
        case 9: mois="Sep"; break;
        case 10: mois="Oct"; break;
        case 11: mois="Nov"; break;
        case 12: mois="Dec"; break;
        default: mois=" ";
    }
    document.getElementById("current_date").innerHTML = mois + " " + day + ", " + year + " " + hour + ":" + min;
</script>
</body>
</html>
