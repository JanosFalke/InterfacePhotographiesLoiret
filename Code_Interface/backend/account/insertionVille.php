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

if(isset($_SESSION['loggedIn'])){

    $nom = $_POST["nom"];
    $codepostal = $_POST["codepostal"];

    $longitude = $_POST["longitude"];
    $latitude = $_POST["latitude"];

    if($longitude == ""){
        $longitude = "null";
    }

    if($latitude == ""){
        $latitude = "null";
    }

    $result = pg_query($db, "INSERT INTO villes (nom, code_postal, longitude, latitude)
                                    VALUES('$nom', $codepostal, $longitude, $latitude)");
}
?>

<form name="fr" role="form" action='../../frontend/pages/modification.php' method='GET'>
</form>

<script type='text/javascript'>document.fr.submit();</script>


