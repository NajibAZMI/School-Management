<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/login/global.css">
    <link rel="stylesheet" href="../../style/prof/notifications.css">

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
            <li class="profili">
                <div class="profi1">
                    <button class="but-profi"><div class="im1"></div></button> 
                      <div class="profi2">
                        <a href="profil.php">profil</a>
                        <a href="../login/logout.php">desconecter</a>
                      </div>
                  </div>
            </li>
            <li>
                <a href="notifications.php" class="notification">
                    <div><img src="" alt=""></div>
                    <span class="badge">3</span>
                </a>
            </li>
           </ul>   
        </nav>
        <article class="article1">
            <?php
// Connexion à la base de données
$host = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connexion échouée: " . mysqli_connect_error());
}
        echo'<div class="notf1">
               <p class="lognot">Notifications</p>
             </div>
             <aside class="aside1">
          ';
// Récupération des notifications
$sql = "SELECT * FROM notification ORDER BY date_notification DESC";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    // Affichage des notifications
    echo '<div class="notf2">';
    while($row = mysqli_fetch_assoc($result)) {
        echo '<form method="post" class="formmodule">';
        echo '<div class="not1">';
        echo '<button class="buttonnotf" type="submit" name="notificationchose">';
        echo '<p>' . $row["message"] . '</p>';
        echo '</button>';
        echo '</div>';
        echo '<input type="hidden" name="idn" value="' . $row['id_premiere_notification'] . '">';
        echo '</form>';
    }
    echo '</div>';
} else {
    echo "Aucune notification.";
}
           echo' </aside>
             ';


             if (isset($_POST['notificationchose'])) {
                $selected_notification_id = $_POST['idn'];
            
                $sql = "SELECT * FROM notification 
                        WHERE id_premiere_notification=$selected_notification_id
                        ";
                $result = mysqli_query($conn, $sql);
            
                if (mysqli_num_rows($result) > 0) {
                    echo'<aside class="aside2">
                             ';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '
                          <div class="titredate">
                             <div class="title"><p>' . $row["message"] . '</p></div>
                             <div class="date"><p>' . $row["date_notification"] . '</p></div>
                          </div>
                          <div class="message">
                              <p>' . $row["description"] . '</>
                          </div>
                        ';
                    }
                    echo'
                     </aside>
                    ';
                }
             }
            
?>
<?php $conn->close(); ?>

        </article>
    </aside>
</body>
</html>