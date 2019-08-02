<?php

require("../../frontend/system/connect.php");


if(!isset($_SESSION)){
    session_start();

}

if(isset($_SESSION['loggedIn'])) {

    if (isset($_GET['id_taille_cliche'])){

        $id_taille_cliche = $_GET['id_taille_cliche'];

        $result = pg_query($db, "SELECT id_taille_cliche, longueur, largeur
                                    FROM tailles_cliches 
                                    WHERE id_taille_cliche = $id_taille_cliche");

        $row = pg_fetch_row($result,0);

        ?>


        <form name="modTailleCliche" action="../../backend/account/suppModTailleCliche.php" method="post">
            <fieldset>

                <input type="hidden" name="id" value="<?php echo $row[0]; ?>">

                <label for="longueur">Longueur:</label>
                <input type=number step=any lang="en" placeholder="<?php echo $row[1]; ?>" name="longueur" required>

                <label for="longueur">Largeur:</label>
                <input type=number step=any lang="en" placeholder="<?php echo $row[2]; ?>" name="largeur" required>

                <button type="submit" name="modifier">Modifier</button>
            </fieldset>
        </form>


        <?php
    }
}
?>