<?php
/**
 * Created by PhpStorm.
 * User: janosfalke
 * Date: 2019-02-06
 * Time: 17:12
 */

require("../../frontend/system/connect.php");


if(!isset($_SESSION)){
    session_start();

}

if(isset($_SESSION['loggedIn'])) {

    if (isset($_POST["supprimer"])) {
        $id = $_POST["villes"];

        $result = pg_query($db, "DELETE FROM villes 
                                      WHERE id_ville = $id");

    } else if(isset($_POST["modifier"])){

        $id = $_POST["id"];
        $nom = strtolower($_POST["nom"]);
        $code_postal = $_POST["code_postal"];
        $longitude = $_POST["longitude"];
        $latitude = $_POST["latitude"];

        if($longitude == ""){
            $longitude = "null";
        }

        if($latitude == ""){
            $latitude = "null";
        }

        $result = pg_query($db, "UPDATE villes SET nom = '$nom', code_postal = $code_postal, 
                                                          longitude = $longitude, latitude = $latitude
                                              WHERE id_ville = $id");
    }
}
?>

<form name="fr" role="form" action='../../frontend/pages/modification.php' method='GET'>
</form>

<script type='text/javascript'>document.fr.submit();</script>


