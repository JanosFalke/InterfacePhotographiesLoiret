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

        $id = $_POST["tailles"];

        $result = pg_query($db, "DELETE FROM tailles_cliches 
                                              WHERE id_taille_cliche = $id");
    } else if(isset($_POST["modifier"])){

        $id = $_POST["id"];
        $longueur = $_POST["longueur"];
        $largeur = $_POST["largeur"];

        $longueur= str_replace(',', '.', $longueur);
        $largeur= str_replace(',', '.', $largeur);

        $result = pg_query($db, "UPDATE tailles_cliches SET longueur = $longueur, largeur = $largeur
                                              WHERE id_taille_cliche = $id");
    }
}

?>

<form name="fr" role="form" action='../../frontend/pages/modification.php' method='GET'>
</form>

<script type='text/javascript'>document.fr.submit();</script>



