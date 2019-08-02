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

    $longueur = $_POST["longueur"];
    $largeur = $_POST["largeur"];

    $result = pg_query($db, "INSERT INTO tailles_cliches (longueur, largeur)
                                    VALUES($longueur, $largeur)");
}

?>



<form name="fr" role="form" action='../../frontend/pages/modification.php' method='GET'>
</form>

<script type='text/javascript'>document.fr.submit();</script>


