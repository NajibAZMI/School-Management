<?php
session_start();

if (!isset($_SESSION["id_prof"])) {
    header('Location: login.php');
    exit();
}
$id_prof_initial = $_SESSION["id_prof"];

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ensahservice";
$conn = mysqli_connect($host, $user, $pass, $dbname);
if (mysqli_connect_errno()) {
     die("Failed to connect to MySQL: " . mysqli_connect_error());
}
$query = "SELECT nom_prof FROM professeur WHERE id_prof='$id_prof_initial'"; 
$result=$conn->query($query);

if ($result && $result->num_rows > 0) {
 $row = $result->fetch_assoc();
 $name = $row['nom_prof'];
} else {
 $name = "Unknown";
}


$sql = "SELECT filiere_ensg
          FROM professeur
          WHERE id_prof = $id_prof_initial
         ";
           $result = mysqli_query($conn, $sql);  
    if (mysqli_num_rows($result) > 0) {
       while ($row = mysqli_fetch_assoc($result)) {
        $selected_filiere_id = $row['filiere_ensg'];
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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
       <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
       <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
   <!-- <link rel="stylesheet" href="../../style/login/Global.css">
    <link rel="stylesheet" href="../../style/coordinateur/Definiremploi.css"> -->
    <link rel="stylesheet" href="../../style/coordinateur/Define_Emploi.css">
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
          <section class="partieprofesseur">
            <div class="choseplace">
               <div class="classroom"><p>professeur emplois</p></div>
            </div>
            <div class="choseresult">
              <?php
  

        $sql = "SELECT nom_prof, id_prof, prenom_prof
                FROM professeur
                WHERE id_CP1 = $selected_filiere_id OR id_CP2 = $selected_filiere_id OR id_ID = $selected_filiere_id OR id_GC = $selected_filiere_id OR id_GI = $selected_filiere_id
               ";
       $result = mysqli_query($conn, $sql);

     
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<form method="post" class="formmodule">';
        echo '<div class="module-prof-class">';
        echo '<button type="submit" class="button-modules" name="profesr_module"> <p>' . $row['nom_prof'] . ' ' . $row['prenom_prof'] .'</p> </button>'; 
        echo '<input type="hidden" name="selected_prof_id" value="' . $row['id_prof'] . '">'; 
        echo '</div>';
        echo '</form>';
    }

    echo '</section>';
    
    echo '<section class="partiemodule">';
    echo '<div class="drag-container">';

    if (isset($_POST['profesr_module'])) {

      $selected_prof_id = $_POST['selected_prof_id'];
      $_SESSION["elected_prof_id"] = $selected_prof_id ;

      $sql = "SELECT m.id_module, m.nom_module, m.type_module, m.nombre_heur
              FROM module_classe_professeur_filiere mcpf
              JOIN module m ON m.id_module = mcpf.id_module
              WHERE mcpf.id_prof = $selected_prof_id AND mcpf.id_filiere = $selected_filiere_id
              ";
      $result = mysqli_query($conn, $sql);
     
      while ($row = mysqli_fetch_assoc($result)) {
        
        $nombreheur = $row['nombre_heur'];
        if ($nombreheur == 2) {  
          echo '<div class="box" draggable="true" id="' . $row['id_module'] . '" data-value="' . $row['id_module'] . '">' . $row['nom_module'] . $row['type_module'] .'</div>';
        } else if ($nombreheur == 4) {
          echo '<div class="box" draggable="true" id="' . $row['id_module'] . '" data-value="' . $row['id_module'] . '">' . $row['nom_module'] . $row['type_module'] . '</div>';
          echo '<div class="box" draggable="true" id="' . $row['id_module'] . '" data-value="' . $row['id_module'] . '">' . $row['nom_module'] . $row['type_module'] . '</div>';
        }
      }

    } 
  ?>
       <div class="buttvalide"><button class="buttinsert" onclick="insertValues()">INSERT</button></div>
       </div>



<div class="palcemodule">
              <div class="tableemploi">
                  <div class="container-lg">
                     <div class="row">
                     <div class="col-md-12">
                     <div class="schedule-table">
                     <table class="table bg-white">
                     <thead>
                     <tr>
                     <th id="tth">Emploi</th>
                     <th id="tth">08-10 am</th>
                     <th id="tth">10-12 am</th>
                     <th id="tth">02-04 pm</th>
                     <th id="tth">04-06 pm</th>
                     </tr>
                     </thead>
                     <tbody>
 
                     <tr>
                         <td id="tth" class="day">Monday</td>
                         <td id="dropzone1" class="dropzone"></td>
                         <td id="dropzone2" class="dropzone"></td>
                         <td id="dropzone3" class="dropzone"></td>
                         <td id="dropzone4" class="dropzone"></td>
                     </tr>
                     <tr>
                         <td id="tth" class="day">Tuesday</td>
                         <td id="dropzone5" class="dropzone"></td>
                         <td id="dropzone6" class="dropzone"></td>
                         <td id="dropzone7" class="dropzone"></td>
                         <td id="dropzone8" class="dropzone"></td>
                  </tr>
                  <tr>
                      <td id="tth" class="day">Wednesday</td>
                      <td id="dropzone9" class="dropzone"></td>
                      <td id="dropzone10" class="dropzone"></td>
                      <td id="dropzone11" class="dropzone"></td>
                      <td id="dropzone12" class="dropzone"></td>
                  </tr>
                  <tr>
                      <td id="tth" class="day">Thursday</td>
                      <td id="dropzone13" class="dropzone"></td>
                      <td id="dropzone14" class="dropzone"></td>
                      <td id="dropzone15" class="dropzone"></td>
                      <td id="dropzone16" class="dropzone"></td>
                  </tr>
                  <tr>
                      <td id="tth" class="day">Friday</td>
                      <td id="dropzone17" class="dropzone"></td>
                      <td id="dropzone18" class="dropzone"></td>
                      <td id="dropzone19" class="dropzone"></td>
                      <td id="dropzone20" class="dropzone"></td>
                  </tr>
                  <tr>
                      <td id="tth" class="day">Saturday</td>
                      <td id="dropzone21" class="dropzone"></td>
                      <td id="dropzone22" class="dropzone"></td>
                      <td id="dropzone23" class="dropzone"></td>
                      <td id="dropzone24" class="dropzone"></td>
                  </tr>
                     </tbody>
                     </table>
                     </div>
                     </div>
                     </div>
                     </div>
             </div>
             
          </div>

    </section> 
    

   </article>

   <div class="footer_profil">
            <hr>
          E-sevice &copy; copyright 2024 reserved - author : Hamza.br  && Najib.Azmi
      </div>

    </aside>
    
    
  <script>
    var dropzones = document.querySelectorAll('.dropzone');
    var boxes = document.querySelectorAll('[draggable="true"]');
    var values = {};

    dropzones.forEach(function(dropzone) {
      dropzone.addEventListener('dragover', function(event) {
        event.preventDefault();
      });

      dropzone.addEventListener('drop', function(event) {
        event.preventDefault();
        var box = document.getElementById(event.dataTransfer.getData('text/plain'));
        dropzone.appendChild(box);
        var value = box.id;
        values[dropzone.id] = value;
      });
    });

    boxes.forEach(function(box) {
      box.addEventListener('dragstart', function(event) {
        event.dataTransfer.setData('text/plain', this.id);
      });
    });

    function insertValues() {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'Phpdefinireemploi.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          console.log('Values inserted successfully');
        }
      };
      xhr.send('values=' + encodeURIComponent(JSON.stringify(values)));
    }
   </script>
  




    
</body>
</html>