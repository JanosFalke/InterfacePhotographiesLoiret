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


    $action = $_GET["action"];
    $id_cliche = $_GET["cliche"];

    if($action == "delete") {

        $result = pg_query($db, "DELETE FROM articles_details 
                                      WHERE id_cliche = $id_cliche");

    }

}


?>


<form name="fr" role="form" action='../../accueil.php' method='GET'>
</form>

<script type='text/javascript'>document.fr.submit();</script>

