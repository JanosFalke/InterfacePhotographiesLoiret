<?php
/**
 * Created by PhpStorm.
 * User: janosfalke
 * Date: 2019-01-28
 * Time: 21:35
 */
/**
 * Projet BSD 2019
 * Falke Janos & Adalbert Michael
 */

if(!isset($_SESSION)){
    session_start();
}


if(!$_SESSION['success']){
    header('Location: index.php');
}

require("frontend/system/connect.php");


?>
<html>
<head>
    <meta charset="utf-8">
    <title>Projet Base de Donnees 2019</title>
    <link rel="stylesheet" href="frontend/css/main.css">
    <link rel="shortcut icon" href="frontend/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="frontend/img/favicon.ico" type="image/x-icon">
</head>
<body>
<?php include("frontend/templates/header.php"); ?>

<main class="mainStartpage">
    <section>
        <nav class="topnav">
            <div>
                    <a class="active" href="#">Dashboard</a>
                    <a href="frontend/pages/charts.php">Charts</a>

                    <?php if(isset($_SESSION['loggedIn'])): ?>

                        <a href="frontend/pages/modification.php">Modifications</a>
                    <?php endif; ?>
            </div>
        </nav>
    </section>

    <section class="mainContent">
        <div>
            <div id="search_box">
                <div>
                    <label for="search_reference">Reference cindoc</label>
                    <input type="text" name="search_reference" class="search_box search_box_classic" placeholder="Chercher reference..." class="form-control" />
                </div>
                <div>
                    <label for="search_article">Article</label>
                    <input type="text" name="search_article" class="search_box search_box_classic" placeholder="Chercher article..." class="form-control" />

                    <label for="search_sujet">Sujet</label>
                    <input type="text" name="search_sujet" class="search_box search_box_classic" placeholder="Chercher sujet..." class="form-control" />

                    <label for="search_ville">Ville / CP</label>
                    <input type="text" name="search_ville" class="search_box search_box_ville" placeholder="Chercher ville / cp..." class="form-control" />
                    <select name="perimetre" class="perimetre_box">
                        <option value="0" selected>0km</option>
                        <option value="10">10km</option>
                        <option value="20">20km</option>
                        <option value="50">50km</option>
                        <option value="100">100km</option>
                    </select>
                </div>
                <div>
                    <label for="search_date">Date</label>
                    <input type="date" name="search_date" class="search_box search_box_date search_box_classic"  class="form-control" />

                    <label for="search_index_icono">Index iconographique</label>
                    <input type="text" name="search_index_icono" class="search_box search_box_classic" placeholder="Chercher index icono..." class="form-control" />

                    <label for="search_index_personnes">Index personnes</label>
                    <input type="text" name="search_index_personnes" class="search_box search_box_classic" placeholder="Chercher index personnes..." class="form-control" />
                </div>
                <div>
                    <label for="search_neg_inf">Negatif / Inversible</label>
                    <div>
                        <select name="search_neg_inf" class="neg_inf_box">
                            <option value="tous" selected>Tous</option>
                            <option value="négatif">Négatif</option>
                            <option value="inversible">Inversible</option>
                        </select>
                    </div>

                    <label for="search_coul_nb">Couleur ou Noir/Blanc</label>
                    <div>
                        <select name="search_coul_nb" class="coul_nb_box">
                            <option value="tous" selected>Tous</option>
                            <option value="couleur">Couleur</option>
                            <option value="nb">Noir et Blanc</option>
                        </select>
                    </div>

                    <label for="search_tailles">Taille du cliché</label>
                    <div>
                        <select name="search_tailles" class="taille_cliche_box">
                            <option value="tous" selected>Tous</option>
                            <?php
                            $tailles = pg_query($db, "SELECT id_taille_cliche, longueur, largeur 
                                                              FROM tailles_cliches 
                                                              ORDER BY longueur");

                            while ($row = pg_fetch_row($tailles)) {
                                echo '<option value="'.$row[0].'">'.$row[1].'x'.$row[2].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                </div>
            </div>

            <div class="lignesPPageSecion">
                <label for="lignesPPage">Lignes par page</label>
                <select name="lignesPPage" id="lignesPPage">
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250" selected="selected">250</option>
                    <option value="500">500</option>
                </select>
            </div>
            <div id="target-content"><div class="chargementDonnees"><img src="frontend/img/loading.gif"></div></div>
        </div>
    </section>

</main>

<?php include("frontend/templates/footer.php"); ?>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type = "text/javascript" src = "backend/js/select.js" ></script>
</html>
