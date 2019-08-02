<?php
/**
 * Created by PhpStorm.
 * User: janosfalke
 * Date: 2019-02-09
 * Time: 22:31
 */

require("../../frontend/system/connect.php");


if(!isset($_SESSION)){
    session_start();

}

if(isset($_SESSION['loggedIn'])){

    $article = $_POST["article"];
    $reference = $_POST["reference"];
    $sujet = strtolower($_POST['sujet']);
    $date = $_POST['date'];
    $id_ville = $_POST['ville'];
    $index_icono = strtolower($_POST['index_icono']);
    $index_personnes = strtolower($_POST['index_personnes']);
    $nb_cliches = $_POST['nb_cliches'];
    $neg_inv = strtolower($_POST['neg_inv']);
    $nb_coul = $_POST['nb_coul'];
    $id_taille_cliche = $_POST['taille'];
    $description =strtolower($_POST['description']);
    $remarques = strtolower($_POST['remarques']);
    $ndbdp = strtolower($_POST['ndbdp']);
    $id_cliche = $_POST['id_cliche'];


    $queryArticle = "";
    $queryCliches = "";
    $queryArticleDetails = "";

    if($reference != ""){
        $queryArticleDetails = $queryArticleDetails.", reference_cindoc = ".$reference;
    }

    if($sujet != ""){
        $sujet = str_replace("'", "''", $sujet);
        $queryArticleDetails = $queryArticleDetails.", sujet = '".$sujet."'";
    }

    if($id_ville != ""){
        $queryArticleDetails = $queryArticleDetails.", id_ville = ".$id_ville;
    }

    if($date != ""){
        $date = date("Y-m-d", strtotime($date));
        $queryArticleDetails = $queryArticleDetails.", date = '".$date."'";
    }

    if($index_icono != ""){
        $index_icono = str_replace("'", "''", $index_icono);
        $queryArticleDetails = $queryArticleDetails.", index_iconographique = '".$index_icono."'";
    }

    if($index_personnes != ""){
        $index_personnes = str_replace("'", "''", $index_personnes);
        $queryArticleDetails = $queryArticleDetails.", index_personnes = '".$index_icono."'";
    }

    if($nb_cliches != ""){
        $queryCliches = $queryCliches.", nombre_cliche = ".$nb_cliches;
    }

    if($neg_inv != ""){
        $queryCliches = $queryCliches.", negatif_ou_inversible = '".$neg_inv."'";
    } else {
        $queryCliches = $queryCliches.", negatif_ou_inversible = null";
    }


    if($nb_coul != ""){
        $queryCliches = $queryCliches.", couleur_ou_noir_et_blanc = '".$nb_coul."'";
    } else {
        $queryCliches = $queryCliches.", couleur_ou_noir_et_blanc = null";
    }

    if($id_taille_cliche != ""){
        $queryCliches = $queryCliches.", id_taille_cliche = ".$id_taille_cliche;
    } else {
        $queryCliches = $queryCliches.", id_taille_cliche = null";
    }

    if($description != ""){
        $description = str_replace("'", "''", $description);
        $queryArticleDetails = $queryArticleDetails.", description_detaillee = '".$description."'";
    }

    if($ndbdp != ""){
        $ndbdp = str_replace("'", "''", $ndbdp);
        $queryArticleDetails = $queryArticleDetails.", notes_de_bas_de_page = '".$ndbdp."'";
    }

    if($remarques != ""){
        $remarques = str_replace("'", "''", $remarques);
        $queryArticle = $queryArticle.", remarques = '".$remarques."'";
    }


    //Update dans la table articles
    if($queryArticle != "") {
        if (substr($queryArticle, 0, 1) == ",") {
            $queryArticle = substr($queryArticle, 1);
        }

        $queryArticleMain = "UPDATE articles SET " . $queryArticle . " WHERE article = $article";
        pg_query($db, $queryArticleMain);
    }



    //Update dans la table cliches
    if($queryCliches != "") {
        if(substr($queryCliches, 0, 1) == ","){
            $queryCliches = substr($queryCliches, 1);
        }

        $queryClichesMain = "UPDATE cliches SET ". $queryCliches . " WHERE id_cliche = ".$id_cliche;
        pg_query($db, $queryClichesMain);
    }




    //Update dans la table articles_details
    if($queryArticleDetails != "") {
        if(substr($queryArticleDetails, 0, 1) == ","){
            $queryArticleDetails = substr($queryArticleDetails, 1);
        }

        $queryArticleDetailsMain = "UPDATE articles_details SET ". $queryArticleDetails . " WHERE id_cliche = ".$id_cliche;
        pg_query($db, $queryArticleDetailsMain);
    }

}

?>


<form name="fr" role="form" action='../../accueil.php' method='GET'>
</form>

<script type='text/javascript'>document.fr.submit();</script>

