<?php

if(!isset($_SESSION)){
    session_start();
}

if(!$_SESSION['success']){
    header('Location: ../../index.php');
}


require("../system/connect.php");

if(isset($_SESSION['loggedIn'])){ ?>


    <html>
    <head>
        <meta charset="utf-8">
        <title>Projet Base de Donnees 2019</title>
        <link rel="stylesheet" href="../css/main.css">
        <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
    </head>
    <body>
    <?php include("../templates/header.php"); ?>

    <main>
        <section>
            <nav class="topnav">
                <div>
                        <a href="../../accueil.php">Dashboard</a>
                        <a href="charts.php">Charts</a>


                        <?php if(isset($_SESSION['loggedIn'])): ?>

                            <a class="active" href="modification.php">Modifications</a>

                        <?php endif; ?>
                </div>
            </nav>
        </section>

        <section>
            <div class="modificationMainDiv">
                <div class="insertionMain">

                    <h3>Insertion</h3>

                    <div class="insertionArticle">

                        <h4><p>Pour un article <span><img src="../img/open.png" alt="Modifier"></span></p></h4>


                        <div>
                            <form action="../../backend/account/insertionArticle.php" method="post">
                                <fieldset>
                                    <label for="reference">Reference cindoc:</label>
                                    <input type="text" name="reference" required>
                                    <label for="ville">Ville:</label>
                                    <select name="ville">
                                        <option value="" selected></option>

                                        <?php
                                        $villes = pg_query($db, "SELECT id_ville, nom 
                                                              FROM villes 
                                                              WHERE CAST(code_postal AS TEXT) LIKE'45%'
                                                              ORDER BY nom");

                                        while ($row = pg_fetch_row($villes)) {
                                            echo '<option value="'.$row[0].'">'.ucwords($row[1]).'</option>';
                                        }
                                        ?>
                                    </select>
                                    <label for="sujet">Sujet:</label>
                                    <input type="text" name="sujet">
                                    <label for="date">Date:</label>
                                    <input type="date" name="date">
                                    <label for="indexicono">Index iconographique:</label>
                                    <input type="text" name="indexicono">
                                    <label for="indexpers">Index personnes:</label>
                                    <input type="text" name="indexpers">
                                    <label for="nbcliches">Nombre de clichés:</label>
                                    <input type="number" min="1" name="nbcliches">
                                    <label for="neg_inv">Négatif ou inversible:</label>
                                    <select name="neg_inv">
                                        <option value="" selected></option>
                                        <?php
                                        $neg_inv = pg_query($db, "SELECT DISTINCT negatif_ou_inversible 
                                                              FROM cliches
                                                              WHERE negatif_ou_inversible IS NOT NULL");

                                        while ($row = pg_fetch_row($neg_inv)) {
                                            echo '<option value="'.$row[0].'">'.ucfirst($row[0]).'</option>';
                                        }
                                        ?>
                                    </select>
                                    <label for="nb_coul">Couleur ou noir/blanc:</label>
                                    <select name="nb_coul">
                                        <option value="" selected></option>
                                        <?php
                                        $nb_coul = pg_query($db, "SELECT DISTINCT couleur_ou_noir_et_blanc
                                                              FROM cliches 
                                                              WHERE couleur_ou_noir_et_blanc IS NOT NULL");

                                        while ($row = pg_fetch_row($nb_coul)) {
                                            if($row[0] == 'nb'){
                                                $det = 'Noir et blanc';
                                            } else {
                                                $det = 'Couleur';
                                            }
                                            echo '<option value="'.$row[0].'">'.$det.'</option>';
                                        }
                                        ?>
                                    </select>

                                    <label for="taille">Taille du cliché:</label>
                                    <select name="taille">
                                        <option value="" selected></option>
                                        <?php
                                        $tailles = pg_query($db, "SELECT id_taille_cliche, longueur, largeur 
                                                              FROM tailles_cliches 
                                                              ORDER BY longueur");

                                        while ($row = pg_fetch_row($tailles)) {
                                            echo '<option value="'.$row[0].'">'.$row[1].'x'.$row[2].'</option>';
                                        }
                                        ?>
                                    </select>

                                    <label for="description">Description detaillée:</label>
                                    <input type="text" name="description">
                                    <label for="ndbdp">Notes de bas de page:</label>
                                    <input type="text" name="ndbdp">
                                    <label for="remarques">Remarques:</label>
                                    <input type="text" name="remarques">
                                    <button type="submit">Insérer</button>
                                </fieldset>
                            </form>
                        </div>
                    </div>

                    <div class="insertionVille">

                        <h4>Pour une ville <span><img src="../img/open.png" alt="Modifier"></span></h4>
                        <div>
                            <form action="../../backend/account/insertionVille.php" method="post">
                                <fieldset>
                                    <label for="nom">Nom:</label>
                                    <input type="text" name="nom" required>
                                    <label for="codepostal">Code postal:</label>
                                    <input type="number" name="codepostal" required>
                                    <label for="longitude">Longitude (Lambert 93):</label>
                                    <input type="number" name="longitude">
                                    <label for="latitude">Latitude (Lambert 93):</label>
                                    <input type="number" name="latitude">
                                    <button type="submit">Insérer</button>
                                </fieldset>
                            </form>
                        </div>
                    </div>

                    <div class="insertionTailleCiche">

                        <h4>Pour une taille de cliché <span><img src="../img/open.png" alt="Modifier"></span></h4>
                        <div>
                            <form action="../../backend/account/insertionTailleCliche.php" method="post">
                                <fieldset>
                                    <label for="longueur">Longueur:</label>
                                    <input type=number step=any lang="en" name="longueur" required>
                                    <label for="largeur">Largeur:</label>
                                    <input type=number step=any lang="en" name="largeur" required>
                                    <button type="submit">Insérer</button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modificationMain">

                    <h3>Modification / Suppression</h3>

                    <div class="modSuppVille">

                        <h4>Pour une ville <span><img src="../img/open.png" alt="Modifier"></span></h4>

                        <div>
                            <form action="../../backend/account/suppModVille.php" method="post">
                                <fieldset>

                                    <select name="villes">
                                        <?php
                                        $villes = pg_query($db, "SELECT id_ville, nom 
                                                              FROM villes 
                                                              WHERE CAST(code_postal AS TEXT) LIKE'45%'
                                                              ORDER BY nom");

                                        while ($row = pg_fetch_row($villes)) {
                                            echo '<option value="'.$row[0].'">'.ucwords($row[1]).'</option>';
                                        }
                                        ?>
                                    </select>

                                    <label class="modificationVille"><img src="../img/edit.png" alt="Modifier"></label>
                                    <button type="submit" name="supprimer" onclick="return confirm('Cette ville va être supprimé définitivement!')"><img src="../img/delete.png" alt="Supprimer"></button>
                                </fieldset>
                            </form>
                        </div>

                        <div class="modificationsDivVille"></div>
                    </div>

                    <div class="modSuppTailleCliche">

                        <h4>Pour une taille de cliché <span><img src="../img/open.png" alt="Modifier"></span></h4>

                        <div>
                            <form action="../../backend/account/suppModTailleCliche.php" method="post">
                                <fieldset>

                                    <select name="tailles">
                                        <?php
                                        $tailles = pg_query($db, "SELECT id_taille_cliche, longueur, largeur 
                                                              FROM tailles_cliches 
                                                              ORDER BY longueur");

                                        while ($row = pg_fetch_row($tailles)) {
                                            echo '<option value="'.$row[0].'">'.$row[1].'x'.$row[2].'</option>';
                                        }
                                        ?>
                                    </select>
                                    <label class="modificationTailleCliche"><img src="../img/edit.png" alt="Modifier"></label>
                                    <button type="submit" name="supprimer" onclick="return confirm('Cette taille de cliché va être supprimé définitivement!')"><img src="../img/delete.png" alt="Supprimer"></button>
                                </fieldset>
                            </form>
                        </div>

                        <div class="modificationsDivTailleCliche"></div>

                    </div>
                </div>
                <div>
                    <div>
                        <h3>Informations sur la base de données</h3>

                        <div class="informationBaseDeDonnees">
                            <h4>Taille de la base de données</h4>

                            <div>
                                <?php $result = pg_query($db, "SELECT pg_database_size('projet'), pg_size_pretty(pg_database_size('testi'))");

                                $row = pg_fetch_row($result,0);

                                echo "<p>".$row[0]." bytes/octets (".$row[1].")</p>";
                                ?>
                            </div>
                            <div>
                            <h4>Taille des tables individuelles</h4>
                                <?php

                                $tables = array("articles","articles_details", "cliches", "villes", "tailles_cliches");
                                foreach($tables as $nom_table) {
                                    $result = pg_query($db, "SELECT pg_total_relation_size('$nom_table'), pg_size_pretty(pg_total_relation_size('$nom_table'))");
                                    $row = pg_fetch_row($result, 0);

                                    if($nom_table == "articles_details"){
                                        $nom_table = "articles details";
                                    }

                                    if($nom_table == "tailles_cliches"){
                                        $nom_table = "tailles cliches";
                                    }

                                    echo "<p><span>".ucfirst($nom_table). ":</span> " . $row[0]." bytes/octets (".$row[1].")</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main>

    <?php include("../templates/footer.php"); ?>

    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type = "text/javascript" src = "../../backend/js/modification.js" ></script>


    </html>



<?php } else {


}

?>
