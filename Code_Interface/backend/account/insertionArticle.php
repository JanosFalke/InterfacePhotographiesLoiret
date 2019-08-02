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

    $reference = $_POST["reference"];
    $sujet = strtolower($_POST['sujet']);
    $date = $_POST['date'];
    $id_ville = $_POST['ville'];
    $index_icono = strtolower($_POST['indexicono']);
    $index_personnes = strtolower($_POST['indexpers']);
    $nb_cliches = $_POST['nbcliches'];
    $neg_inv = strtolower($_POST['neg_inv']);
    $nb_coul = $_POST['nb_coul'];
    $id_taille_cliche = $_POST['taille'];
    $description =strtolower($_POST['description']);
    $remarques = strtolower($_POST['remarques']);
    $ndbdp = strtolower($_POST['ndbdp']);


    $queryArticle = "";
    $queryArticle_2 = "";
    $queryCliches = "";
    $queryCliches_2 = "";
    $queryArticleDetails = ", reference_cindoc";
    $queryArticleDetails_2 = ", ".$reference."";

    if($sujet != ""){
        $queryArticleDetails = $queryArticleDetails.", sujet";
        $queryArticleDetails_2 = $queryArticleDetails_2.", '$sujet'";
    }

    if($id_ville != ""){
        $queryArticleDetails = $queryArticleDetails.", id_ville";
        $queryArticleDetails_2 = $queryArticleDetails_2.", $id_ville";
    }

    if($date != ""){
        $date = date("Y-m-d", strtotime($date));
        $queryArticleDetails = $queryArticleDetails.", date";
        $queryArticleDetails_2 = $queryArticleDetails_2.", '$date'";
    }

    if($index_icono != ""){
        $queryArticleDetails = $queryArticleDetails.", index_iconographique";
        $queryArticleDetails_2 = $queryArticleDetails_2.", '$index_icono'";
    }

    if($index_personnes != ""){
        $queryArticleDetails = $queryArticleDetails.", index_personnes";
        $queryArticleDetails_2 = $queryArticleDetails_2.", '$index_personnes'";
    }

    if($nb_cliches != ""){
        $queryCliches = $queryCliches.", nombre_cliche";
        $queryCliches_2 = $queryCliches_2.", $nb_cliches";
    }

    if($neg_inv != ""){
        $queryCliches = $queryCliches.", negatif_ou_inversible";
        $queryCliches_2 = $queryCliches_2.", '$neg_inv'";
    }

    if($nb_coul != ""){
        $queryCliches = $queryCliches.", couleur_ou_noir_et_blanc";
        $queryCliches_2 = $queryCliches_2.", '$nb_coul'";

    }

    if($id_taille_cliche != ""){
        $queryCliches = $queryCliches.", id_taille_cliche";
        $queryCliches_2 = $queryCliches_2.", $id_taille_cliche";
    }

    if($description != ""){
        $queryArticleDetails = $queryArticleDetails.", description_detaillee";
        $queryArticleDetails_2 = $queryArticleDetails_2.", '$description'";
    }

    if($ndbdp != ""){
        $queryArticleDetails = $queryArticleDetails.", notes_de_bas_de_page";
        $queryArticleDetails_2 = $queryArticleDetails_2.", '$ndbdp'";
    }

    if($remarques != ""){
        $queryArticle = $queryArticle.", remarques";
        $queryArticle_2 = $queryArticle_2.", '$remarques'";
    }



    //charger num article puis inserer +1
    $query_numArticleDetails = pg_query($db, "SELECT article FROM articles_details ORDER BY article DESC LIMIT 1");
    $query_numArticle = pg_query($db, "SELECT article FROM articles ORDER BY article DESC LIMIT 1");


    $num_articleDetails = pg_fetch_result($query_numArticle,0,0);
    $num_articleArticle = pg_fetch_result($query_numArticle,0,0);

    if($num_articleArticle > $num_articleDetails){
        $num_article = $num_articleArticle +1;

    } else {
        $num_article = $num_articleDetails +1;
    }


    //Insertion dans la table articles
    $queryArticleMain = "INSERT INTO articles (article".$queryArticle.") VALUES (".$num_article."".$queryArticle_2.")";
    pg_query($db, $queryArticleMain);



    if(substr($queryCliches, 0, 1) == ","){
        $queryCliches = substr($queryCliches, 1);
        $queryCliches_2 = substr($queryCliches_2, 1);
    }

    //Insertion dans la table cliches
    if($queryCliches == ""){
        $queryCliches = "nombre_cliche";
        $queryCliches_2 = 'null';
    }

        $queryClichesMain = "INSERT INTO cliches (" . $queryCliches . ") VALUES (" . $queryCliches_2 . ")";
        pg_query($db, $queryClichesMain);


        $query_idCliche = pg_query($db, "SELECT id_cliche FROM cliches ORDER BY id_cliche DESC LIMIT 1");
        $id_cliche = pg_fetch_result($query_idCliche,0,0);
        $queryArticleDetails = $queryArticleDetails.", id_cliche";

        $queryArticleDetails_2 = $queryArticleDetails_2.", $id_cliche";



    $queryArticleDetails = "article".$queryArticleDetails;
    $queryArticleDetails_2 = "$num_article".$queryArticleDetails_2;

    //Insertion dans la table articles_details
    $queryArticleDetailsMain = "INSERT INTO articles_details (".$queryArticleDetails.") VALUES (".$queryArticleDetails_2.")";

    pg_query($db, $queryArticleDetailsMain);


}

?>



<form name="fr" role="form" action='../../frontend/pages/modification.php' method='GET'>
</form>

<script type='text/javascript'>document.fr.submit();</script>



