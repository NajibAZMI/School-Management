<?php
// Connexion à la base de données

if (!isset($_SESSION["id_prof"])) {
    header('Location: login.php');
    exit();
}
$id_initial_prof = $_SESSION["id_prof"];



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ensahservice";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM emploi
        WHERE id_prof = $id_initial_prof";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
$row = $result->fetch_assoc();

$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
for($i=0; $i<6; $i++){
    echo'<tr>';
    echo'<td class="day">'. $jours[$i] .'</td>';

    $j = 0;
   while ($j <4)  {
       $k= $j+4*$i+1;
       $valeur = $row['valeur_' . $k];
       if($valeur!=NULL){
        if($valeur == $row['valeur_' . $k+1] && ($j==0 || $j==2)){
         if($valeur == $row['valeur_' . $k+1] && ($j==0)){
            $sql = "SELECT nom_module,type_module FROM module
                    WHERE id_module = '$valeur'";
             $result = $conn->query($sql);
             if ($result->num_rows > 0) {
                while ($mod = $result->fetch_assoc()) {
                   $nom_module = $mod["nom_module"];
                   $type_module = $mod["type_module"];
                }
             }
             echo'<td class="active" colspan="2">
                 <h4>'.$nom_module.'</h4>
                 <p>'.$type_module.'</p>
                 <div class="hover">
                     <h4>'.$nom_module.'</h4>
                     <p>'.$type_module.'</p>
                 </div>
              </td>';
         }
         if($valeur == $row['valeur_' . $k+1] && ($j==2)){
            $sql = "SELECT nom_module,type_module FROM module
                    WHERE id_module = '$valeur'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while ($mod = $result->fetch_assoc()) {
                  $nom_module = $mod["nom_module"];
                  $type_module = $mod["type_module"];
              }
            }
            echo'<td class="active" colspan="2">
                <h4>'.$nom_module.'</h4>
                <p>'.$type_module.'</p>
                <div class="hover">
                    <h4>'.$nom_module.'</h4>
                    <p>'.$type_module.'</p>
                </div>
            </td>';
         }
         $j++;
        }
         else{

       
       $sql = "SELECT nom_module,type_module FROM module
        WHERE id_module = '$valeur'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($mod = $result->fetch_assoc()) {
                $nom_module = $mod["nom_module"];
                $type_module = $mod["type_module"];
            }
        }
        echo'<td class="active">
                <h4>'.$nom_module.'</h4>
                <p>'.$type_module.'</p>
                <div class="hover">
                    <h4>'.$nom_module.'</h4>
                    <p>'.$type_module.'</p>
                </div>
             </td>';
       }
    }
       else{
        echo'<td></td>';
       }

       $j++;
   }
   echo'</tr>'; 
 }
} else {
    $jours = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    foreach ($jours as $jour) {
        echo '<tr>';
        echo '<td class="day">' . $jour . '</td>';
        for ($i = 0; $i < 4; $i++) {
            echo '<td></td>';
        }
        echo '</tr>';
    }
    
}
?>

