<?php


require("../frontend/system/connect.php");


if(!isset($_SESSION)){
    session_start();

}

if(isset($_SESSION['loggedIn'])) {

    $id_cliche = $_GET['id_cliche'];

    $result = pg_query($db, "SELECT reference_cindoc, sujet, date, index_iconographique, index_personnes,
                                          description_detaillee, notes_de_bas_de_page, remarques, ad.article, ad.id_cliche, id_ville
                                FROM articles_details ad 
                                JOIN articles a ON a.article = ad.article
                                WHERE ad.id_cliche = $id_cliche");

    $row = pg_fetch_row($result,0);

    if($row[9] != null){
        $resultCliche = pg_query($db, "SELECT nombre_cliche, couleur_ou_noir_et_blanc, 
                                          negatif_ou_inversible, id_taille_cliche
                                FROM cliches c
                                WHERE c.id_cliche = $row[9]");

        $rowCliche = pg_fetch_row($resultCliche,0);
    }


    echo '<div class="articleModify-content">
    <form action="backend/account/modArticle.php" method="post">
        <fieldset>
        
            <input type="hidden" name="id_cliche" value="'.$row[9].'" >
            <input type="hidden" name="article" value="'.$row[8].'" >
            
            <label for="reference">Reference</label>
            <input type="text" name="reference" placeholder="'.$row[0].'" value="'.$row[0].'" required>
            
            <label for="sujet">Sujet</label>
            <input type="text" name="sujet" placeholder="'.$row[1].'" value="'.$row[1].'" required>
            
            <label for="ville">Ville</label>
            <select name="ville">';

            $ville = pg_query($db, "SELECT id_ville, nom FROM villes WHERE CAST(code_postal AS TEXT) LIKE '45%'
                                                          ORDER BY nom");

            while ($rowi = pg_fetch_row($ville)) {

                if($row[10] == $rowi[0]){
                    echo '<option value="'.$rowi[0].'" selected>'.ucwords($rowi[1]).'</option>';

                } else {
                    echo '<option value="' . $rowi[0] . '">' . ucwords($rowi[1]) . '</option>';
                }
            }


echo '    </select>
            
            
            <label for="date">Date</label>
            <input type="date" name="date" value="'.$row[2].'">
            
            <label for="nb_cliches">Nombre de clichés</label>';

            if($rowCliche){
                echo '<input type="number" name="nb_cliches" min="1" placeholder="'.$rowCliche[0].'" value="'.$rowCliche[0].'">';
            } else {
                echo '<input type="number" name="nb_cliches" min="1">';
            }


           echo '<label for="nb_coul">Couleur ou noir/blanc</label>
            <select name="nb_coul">
                <option value=""></option>';

            $nb_coul = pg_query($db, "SELECT DISTINCT couleur_ou_noir_et_blanc FROM cliches WHERE couleur_ou_noir_et_blanc IS NOT NULL");


            while ($rowi = pg_fetch_row($nb_coul)) {
                if($rowi[0] == 'nb'){
                    $det = 'Noir et blanc';
                } else if($rowi[0] == 'couleur'){
                    $det = 'Couleur';
                }

                if($rowCliche && $rowCliche[1] == $rowi[0]){
                    echo '<option value="' . $rowi[0] . '" selected>' . $det . '</option>';
                } else {
                    echo '<option value="' . $rowi[0] . '">' . $det . '</option>';
                }
            }


echo '
            </select>
            
            <label for="neg_inv">Négatif ou inversible</label>
            <select name="neg_inv">
                <option value=""></option>';

            $neg_inf = pg_query($db, "SELECT DISTINCT negatif_ou_inversible FROM cliches WHERE couleur_ou_noir_et_blanc IS NOT NULL");

            while ($rowi = pg_fetch_row($neg_inf)) {

                if($rowCliche && $rowCliche[2] == $rowi[0]){
                    echo '<option value="' . $rowi[0] . '" selected>' . ucfirst($rowi[0]) . '</option>';
                } else {
                    echo '<option value="' . $rowi[0] . '">' . ucfirst($rowi[0]) . '</option>';
                }
            }


echo '
            <select>
            
            
            <label for="taille">Taille</label>
            <select name="taille">
                <option value=""></option>';


            $tailles = pg_query($db, "SELECT id_taille_cliche, longueur, largeur FROM tailles_cliches ORDER BY longueur");

            while ($rowi = pg_fetch_row($tailles)) {

                if($rowCliche && $rowCliche[3] == $rowi[0]) {
                    echo '<option value="' . $rowi[0] . '" selected>' . $rowi[1] . 'x' . $rowi[2] . '</option>';
                } else {
                    echo '<option value="' . $rowi[0] . '">' . $rowi[1] . 'x' . $rowi[2] . '</option>';
                }
            }

echo '
            </select>
            
            <label for="index_icono">Index iconographique</label>
            <input type="text" name="index_icono" placeholder="'.$row[3].'" value="'.$row[3].'">
            
            <label for="index_personnes">Index personnes</label>
            <input type="text" name="index_personnes" placeholder="'.$row[4].'" value="'.$row[4].'">
            
            <label for="description">Description detaillée</label>
            <input type="text" name="description" placeholder="'.$row[5].'" value="'.$row[5].'">
            
            <label for="ndbdp">Notes de bas de page</label>
            <input type="text" name="ndbdp" placeholder="'.$row[6].'" value="'.$row[6].'">
            
            <label for="remarques">Remarques</label>
            <input type="text" name="remarques" placeholder="'.$row[7].'" value="'.$row[7].'">
            
            <button type="submit">Modifier</button>
        </fieldset>
    </form>
</div>';

}


?>