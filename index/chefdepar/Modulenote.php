<?php
// Connexion à la base de données
session_start();

if (!isset($_SESSION["id_prof"])) {
    header('Location: login.php');
    exit();
}
$id_professeur_initial = $_SESSION["id_prof"];

if (isset($_POST["saisir_Modifier"])) {
    if (isset($_POST["id_module"])) {
        $_SESSION["id_module"] = $_POST["id_module"];
    }
    header("Location: saisir_modifier.php");
    exit();
}


$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ensahservice";

// Connexion à la base de données
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
$query = "SELECT nom_prof FROM professeur WHERE id_prof='$id_professeur_initial '"; 
$result=$conn->query($query);

if ($result && $result->num_rows > 0) {
 $row = $result->fetch_assoc();
 $name = $row['nom_prof'];
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
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="../../style/chefdepar/ModulENotE.css">

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
                    <a href="AfficModules.php">Affic des modules</a>
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
                        <div class="affich_date"><?php echo date("Y-m-d"); ?></div>
                           
                        </div>
            </li>
            <li>
            <button class="button_profil">
                             <div class="name"><?php echo"Dr.$name" ?></div>
                           
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
            <div class="EnsgResp">
                <div class="Ensgname"><p>Enseignant</p></div>
                <div class="Ensgmodule">
                    <table border="1">
                        <tr>
                            <th>Intitulé</th>
                            <th>Saisir/Modifier</th>
                            <th>Consulter</th>
                            <th>Télécharger</th>
                        </tr>
                        <?php
                        $sql = "SELECT mcpf.id_prof, mcpf.id_module, mcpf.id_classe, m.nom_module, m.type_module, m.nombre_heur, c.nom_classe 
                                FROM module_classe_professeur_filiere AS mcpf
                                JOIN module AS m ON mcpf.id_module=m.id_module
                                JOIN classe AS c ON mcpf.id_classe=c.id_classe
                                WHERE mcpf.id_prof =$id_professeur_initial";
                        $result = mysqli_query($conn, $sql);  

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>
                                    <div class="module-info">
                                        <h2>' . $row["nom_module"] . '</h2>
                                        <h2>' . $row["nom_classe"] . '</h2>
                                        <h2>' . $row["type_module"] . '</h2>
                                        <h2>' . $row["nombre_heur"] . '</h2>
                                    </div>
                                </td>';
                                
                                if ($row["type_module"] == 'Cour') {
                                    echo '<td>
                                        <form method="post">
                                            <input type="hidden" name="id_module" value="' . $row['id_module'] . '">
                                            <button type="submit" name="saisir_Modifier">Action</button>
                                        </form>
                                    </td>';
                                    echo '<td>
                                        <form method="post">
                                            <input type="hidden" name="id_module" value="' . $row['id_module'] . '">
                                            <button type="submit" name="Consulter_Note">Action</button>
                                        </form>
                                    </td>';
                                    echo '<td>
                                        <form method="post">
                                            <input type="hidden" name="id_module" value="' . $row['id_module'] . '">
                                            <button type="submit" name="Telecharger">Action</button>
                                        </form>
                                    </td>';
                                } else {
                                    echo '<td></td><td></td><td></td>';
                                }
                                
                                echo '</tr>';
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </article>
        <div class="footer_profil">
            <hr>
            E-service &copy; copyright 2024 reserved - author : Hamza.br & Najib.Azmi
        </div>
    </aside>
</body>
</html>
<?php $conn->close(); ?>