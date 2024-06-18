<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $choix = $_POST['filiere'];
  $id_CP1 = null;
  $id_CP2 = null;
  $id_GI = null;
  $id_ID = null;
  $id_GC = null;

  foreach ($choix as $valeur) {
      switch ($valeur) {
          case 'CP1':
              $id_CP1 = 1;
              break;
          case 'CP2':
              $id_CP2 = 2;
              break;
          case 'GI':
              $id_GI = 3;
              break;
          case 'ID':
              $id_ID = 4;
              break;
          case 'GC':
              $id_GC = 5;
              break;
      }
  }

  $nom_module = $_POST["nom_module"];
  $type_module = $_POST["type_module"];
  $module_id = $_POST["id_module"];
  $nombre_heures = $_POST["nombre_heures"];
  $specialite=$_POST["specialite"];

  $sql = "INSERT INTO module (id_module, nom_module, type_module, nombre_heur, Specialite,id_CP1, id_CP2, id_GI, id_ID, id_GC) 
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssss", $module_id, $nom_module, $type_module, $nombre_heures, $specialite, $id_CP1, $id_CP2, $id_GI, $id_ID, $id_GC);

if ($stmt->execute()) {
echo "";
} else {
echo "" . $stmt->error;
}

$stmt->close();

}
 

  $sql = "SELECT id_module, nom_module, type_module  FROM module ";
  $result = mysqli_query($conn, $sql);  

if (mysqli_num_rows($result) > 0) {
  while ($ligne = mysqli_fetch_assoc($result)) {
    $tableauModules[] = array(
      'nom_module' => $ligne['nom_module'],
      'type_module' => $ligne['type_module'],
      'id_module' => $ligne['id_module']
    );
  } 
}

if (isset($_GET['module_button'])) {
        $selected_module_id = $_GET['idmodule'];

        $sql = "SELECT id_module, nom_module, type_module, Specialite, nombre_heur
                       , id_CP1, id_CP2, id_GI, id_ID, id_GC 
                FROM module 
                WHERE id_module = $selected_module_id 
               ";
        $result = mysqli_query($conn, $sql);  
      
      if (mysqli_num_rows($result) > 0) {
        while ($ligne = mysqli_fetch_assoc($result)) {
          $Moduletable[] = array(
            'nom_module' => $ligne['nom_module'],
            'type_module' => $ligne['type_module'],
            'id_module' => $ligne['id_module'],
            'Specialite' => $ligne['Specialite'],
            'nombre_heur' => $ligne['nombre_heur'],
            'id_CP1' => ($ligne['id_CP1'] != null) ? 'AP1' : null,
            'id_CP2' => ($ligne['id_CP2'] != null) ? 'AP2' : null,
            'id_GI' => ($ligne['id_GI'] != null) ? 'GI' : null,
            'id_ID' => ($ligne['id_ID'] != null) ? 'ID' : null,
            'id_GC' => ($ligne['id_GC'] != null) ? 'GC' : null
          );
        } 
      }
}


if (isset($_GET['delete_button'])) {
  $module_id = $_GET['idmodule'];
     $sqlDelete = "DELETE FROM module WHERE id_module = $module_id";
     $resultDelete = mysqli_query($conn, $sqlDelete);
}

$conn->close();
?>  


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
   <!-- <link rel="stylesheet" href="../../style/login/global.css">
    <link rel="stylesheet" href="../../style/chefdepar/AfficModules.css"> --> 
    <link rel="stylesheet" href="../../style/chefdepar/AfficModules_Nv.css">

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

             <aside class="Moduleinfo">
             <aside class="Moduleinfo1">
                  <div class="titretab"><p>Ajouter Module</p></div>
                     
                      <div class="chercheModule">
                      <form method="post" action="">
                          <div class="enterinfo">
                            <div class="name">
                              <div class="col-md-8"><input type="text" name="nom_module" class="form-control" placeholder="Nom de Module" value=""></div>
                            </div>
                            <div class="adres">
                              <div class="col-md-8">
                               <select name="specialite" id="selectoption" class="form-control search-slt text-white-50">
                                 <option value="Informatique">Informatique</option>
                                 <option value="Mathematique">Mathematique</option>
                                 <option value="Physique">Physique</option>
                                 <option value="Languge">Languge</option>
                                 <option value="Economique">Economique</option>
                               </select>
                              </div>
                              <div class="adres1">
                                <div class="col-md-5"></label><input type="text" name="id_module" class="form-control" placeholder="ID module" value=""></div>
                                <div class="col-md-5"></label><input type="text" name="nombre_heures" class="form-control" placeholder="Nombre heures" value=""></div>
                              </div> 
                            </div>
                            <div class="etat">
                              Prep/Cycle :
                              <input type="checkbox" id="CP1" name="filiere[]" value="CP1">CP1
                              <input type="checkbox" id="CP2" name="filiere[]" value="CP2">CP2
                              <input type="checkbox" id="GI" name="filiere[]" value="GI">GI
                              <input type="checkbox" id="ID" name="filiere[]" value="ID">ID
                              <input type="checkbox" id="GC" name="filiere[]" value="GC">GC
                            </div>
                            <div class="Genre">
                              Type :
                              <input type="radio" name="type_module" value="Cour">Cour
                              <input type="radio" name="type_module" value="TD">TD
                              <input type="radio" name="type_module" value="TP">TP
                            </div>
                            <div class="line1"></div>
                            <div class="inscrip"><input class="input1" type="submit" name="enregistrer" value="Enregistrer"></div>
                          </div>
                        
                      </form>
                      </div>
                  </aside>


                  <aside class="Moduleinfo2">
                    <aside class="Teacherinfo2">   
                        <div class="tableau">
                        <?php foreach ($Moduletable as $module) { ?>
                        <div class="infModule">
                         <div class="infte">
                              <ul>
                                 <li><div class="colomn"><h2>Nom de module  :</h2><p><?php echo $module['nom_module']; ?></p></div></li>
                                 <li><div class="colomn"><h2>Spécialite :</h2><p><?php echo $module['Specialite']; ?></p></div></li>
                                 <li><div class="colomn"><h2>Filieres     :</h2><p><?php   $filieres = [];
                                                                          if ($module['id_CP1'] !== null) { $filieres[] = 'AP1';}
                                                                          if ($module['id_CP2'] !== null) {$filieres[] = 'AP2';}
                                                                          if ($module['id_GI'] !== null) {$filieres[] = 'GI'; }
                                                                          if ($module['id_ID'] !== null) { $filieres[] = 'ID'; }
                                                                          if ($module['id_GC'] !== null) {$filieres[] = 'GC';}
                                                                          echo implode(', ', $filieres); ?></p></div>
                                  </li>
                              </ul>
                              <ul>
                                 <li><div class="colomn"><h2>Nombre heurs  :</h2><p><?php echo $module['nombre_heur']; ?></p></div></li>
                                 <li><div class="colomn"><h2>Genre      :</h2><p><?php echo $module['type_module']; ?></p></div></li>     
                              </ul>
                         </div>
                         <div class="line2"></div>
                         <form method="get">
                             <input type="hidden" name="idmodule" value="<?php echo $module['id_module']; ?>">
                             <button type="submit" name="delete_button" class="edit-btn">Delete</button>
                         </form>                  
                       </div>
                       <?php 
                          } 
                       ?>  
                            </div>
                  

                </aside>

            </aside>
            </aside>
                      <section>
                            <div class="tbl-header">
                            <table>
                             <thead>
                                      <tr>
                                        <th><p>Modules</p></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                                <div class="tbl-content">
                                  <table>
                                    <tbody>
                                    <?php foreach ($tableauModules as $module) { ?>
                                      <form method="GET">
                                      <button class="divtd" type="submit" name="module_button">
                                        <p><?php echo $module['nom_module']; ?></p>
                                        <div>
                                          <p><?php echo $module['type_module']; ?></p>
                                          <input type="hidden" name="idmodule" value="<?php echo $module['id_module']; ?>">
                                        </div>
                                      </button>

                            </form>
                             <?php 
                                  } 
                             ?>                                                       
                        </tbody>
                       </table>
                 </div>
               </section>
            
                

        </article>
        <div class="footer_profil">
            <hr>
          E-sevice &copy; copyright 2024 reserved - author : Hamza.br  && Najib.Azmi
      </div>
    </aside>
</body>
</html>


