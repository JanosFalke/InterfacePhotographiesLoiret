<?php
/**
 * Created by PhpStorm.
 * User: janosfalke
 * Date: 2019-02-06
 * Time: 16:25
 */


require("../frontend/system/connect.php");


//CODE POSTAL ==> Insertion Ville
if(isset($_GET['cp'])) {
    $code_postal = $_GET['cp'];

    $result = pg_query($db, "SELECT nom, code_postal, longitude, latitude
                                    FROM villes 
                                    WHERE code_postal LIKE '$code_postal'");
    $rowCount = pg_num_rows($result);

    $data = array();

    if($rowCount != 0){
        $row = pg_fetch_row($result,0);
        $data[] = "true";
        $data[] = $row;

    } else {
        $data[] = "false";
    }

    print json_encode($data);

}

//Tailles de clichés ==> Insertion Taille de cliché
if(isset($_GET['tailleX']) && isset($_GET['tailleY'])) {
    $tailleX = $_GET['tailleX'];
    $tailleY = $_GET['tailleY'];

    $result = pg_query($db, "SELECT longueur, largeur
                                    FROM tailles_cliches 
                                    WHERE longueur = $tailleX
                                    AND largeur = $tailleY");
    $rowCount = pg_num_rows($result);

    $data = array();

    if($rowCount != 0){
        $data[] = "true";

    } else {
        $data[] = "false";
    }

    print json_encode($data);

}
?>