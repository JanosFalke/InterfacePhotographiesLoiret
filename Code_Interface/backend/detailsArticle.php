<?php

require("../frontend/system/connect.php");

$id_cliche = $_GET["id_cliche"];

$result = pg_query($db,
    "SELECT index_personnes, index_iconographique, description_detaillee, notes_de_bas_de_page, remarques 
            FROM articles_details ad
            JOIN articles a ON a.article = ad.article
            WHERE id_cliche = $id_cliche");

$row = pg_fetch_row($result,0);
$printed = false;


if(isset($_SESSION['loggedIn'])) {
    echo "<td colspan='6'><div>";
} else {
    echo "<td colspan='7'><div>";
}


//Index_personnes
if($row[0] != null){
    $printed = true;
    echo "<h4>Index personnes:</h4>";
    echo "<p>".ucwords($row[0])."</p>";

}

//Index iconographique
if($row[1] != null){
    if($printed == true){
        echo "<br>";
    }
    $printed = true;
    echo "<h4>Index iconographique:</h4>";
    echo "<p>".ucfirst($row[1])."</p>";
}

//Description detaillée
if($row[2] != null){
    if($printed == true){
        echo "<br>";
    }
    $printed = true;
    echo "<h4>Description detaillée:</h4>";
    echo "<p>".ucfirst($row[2])."</p>";
}

//Notes de bas de page
if($row[3] != null){
    if($printed == true){
        echo "<br>";
    }
    $printed = true;
    echo "<h4>Notes de bas de page:</h4>";
    echo "<p>".ucfirst($row[3])."</p>";
}

//Remarques
if($row[4] != null){
    if($printed == true){
        echo "<br>";
    }
    echo "<h4>Remarques:</h4>";
    echo "<p>".ucfirst($row[4])."</p>";
}


echo "</div></td>";
?>