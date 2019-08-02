<?php
/**
 * Created by PhpStorm.
 * User: janosfalke
 * Date: 2019-02-03
 * Time: 18:22
 */

require("../frontend/system/connect.php");

$id_cliche = $_GET['id_cliche'];

$result = pg_query($db, "SELECT nombre_cliche, negatif_ou_inversible, couleur_ou_noir_et_blanc, longueur, largeur FROM cliches c 
                                JOIN tailles_cliches tc ON c.id_taille_cliche = tc.id_taille_cliche
                                WHERE id_cliche = $id_cliche");

$content = false;

while ($row = pg_fetch_row($result)) {
        $content = true;

        if ($row[2] == 'nb') {
            $row[2] = "noir et blanc";
        }



        if($row[0]){
            echo "<div><p>Nombre de clichés:</p><p>" . $row[0] . "</p></div>";
        }

        echo "<div><h4>Détails:</h4><ul>";

        if($row[1]){
            echo "<li>" . ucfirst($row[1]) . "</li>";
        }

        if($row[2]){
            echo "<li>" . ucfirst($row[2]) . "</li>";

        }

        if($row[3]){
            echo "<li><p>Longueur:</p><p>" . $row[3] . " cm</p></li>";

        }

        if($row[4]){
            echo "<li><p>Largeur:</p><p>" . $row[4] . " cm</p></li>";

        }

        echo "</ul></div>";

}

if(!$content){
    echo "Aucun détail trouvé...";
}



?>