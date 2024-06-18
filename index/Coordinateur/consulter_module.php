<?php
session_start();

if (!isset($_SESSION["id_prof"])) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION["id_module"])) {
    die("Module ID not set.");
}

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ensahservice";

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id_module = $_SESSION["id_module"];

// Fetch module information
$query_module = "SELECT nom_module FROM module WHERE id_module = ?";
$stmt = $conn->prepare($query_module);
$stmt->bind_param('i', $id_module);
$stmt->execute();
$result_module = $stmt->get_result();

if ($result_module && $result_module->num_rows > 0) {
    $row_module = $result_module->fetch_assoc();
    $nom_module = $row_module['nom_module'];
} else {
    $nom_module = "Module inconnu";
}

// Fetch students enrolled in the module
$query_etudiants = "SELECT e.id_etudiant, e.nom_etudiant, e.prenom_etudiant, e.email_etudiant 
                    FROM etudiant e
                    JOIN inscription_module im ON e.id_etudiant = im.id_etudiant
                    WHERE im.id_module = ?";
$stmt = $conn->prepare($query_etudiants);
$stmt->bind_param('i', $id_module);
$stmt->execute();
$result_etudiants = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/coordinateur/affichages_des_notes.css">
    <title>Liste des étudiants inscrits au module <?php echo htmlspecialchars($nom_module); ?></title>
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
                <li><div class="imgEtudinatAbsence"></div><a href="EtudinatAbsence.php">Etudinat/Absence</a></li>
                <li><div class="affichage_des_notes"></div><a href="affichage_des_notes.php">Affichage des notes</a></li>
                <li><div class="imgAffectationM"></div><a href="AffectationM.php">Affectation des modules</a></li>
                <li><div class="imgDefiniremploi"></div><a href="Definiremploi.php">Définir l'emploi</a></li>
                <li><div class="Notesclsse"></div><a href="Class_notes.php">Notes</a></li>
                <li><div class="imgValiderNotes"></div><a href="Consulter_Les_etudiant.php">Consulter les etudiants</a></li>
                <li><div class="imgFormation"></div><a href="Formation.php">classe emploi</a></li>
                <li><div class="imgNotifications"></div><a href="notifications.php">Notifications</a></li>
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
                        <div class="name"><?php echo "Dr. $name"; ?></div>
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
                            <a href="profil.php">Profil</a>
                            <a href="../login/logout.php">Déconnecter</a>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
        <article class="article1">
            <h1>Liste des étudiants inscrits au module <?php echo htmlspecialchars($nom_module); ?></h1>
            <?php if ($result_etudiants && $result_etudiants->num_rows > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID Etudiant</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row_etudiant = $result_etudiants->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row_etudiant['id_etudiant']); ?></td>
                                <td><?php echo htmlspecialchars($row_etudiant['nom_etudiant']); ?></td>
                                <td><?php echo htmlspecialchars($row_etudiant['prenom_etudiant']); ?></td>
                                <td><?php echo htmlspecialchars($row_etudiant['email_etudiant']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun étudiant n'est inscrit à ce module.</p>
            <?php endif; ?>
        </article>
    </aside>
    <script>
        // Script to show the current date
        document.getElementById("current_date").innerHTML = new Date().toLocaleString();
    </script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
