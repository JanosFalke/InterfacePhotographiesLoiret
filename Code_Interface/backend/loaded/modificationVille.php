<?php

require("../../frontend/system/connect.php");


if(!isset($_SESSION)){
    session_start();

}

if(isset($_SESSION['loggedIn'])) {

    if (isset($_GET['id_ville'])){

        $id_ville = $_GET['id_ville'];

        $result = pg_query($db, "SELECT id_ville, nom, code_postal, longitude, latitude
                                    FROM villes 
                                    WHERE id_ville = $id_ville");

        $row = pg_fetch_row($result,0);

        ?>


        <form name="modVille" action="../../backend/account/suppModVille.php" method="post">
            <fieldset>

                <input type="hidden" name="id" value="<?php echo $row[0]; ?>">

                <label for="nom">Nom:</label>
                <input type="text" placeholder="<?php echo ucwords($row[1]); ?>" name="nom" required>

                <label for="code_postal">Code postal:</label>
                <input type="number" placeholder="<?php echo $row[2]; ?>" name="code_postal" required>

                <label for="longitude">Longitude (Lambert 93):</label>
                <input type="number" placeholder="<?php echo $row[3]; ?>" name="longitude">

                <label for="latitude">Latitude (Lambert 93):</label>
                <input type="number" placeholder="<?php echo $row[4]; ?>" name="latitude">

                <button type="submit" name="modifier">Modifier</button>
            </fieldset>
        </form>


        <?php
    }
}
?>