<?php
require("../frontend/system/connect.php");


$result = pg_query($db,
    "SELECT V.nom as ville, AVG(C.nombre_cliche) as nombre_de_cliche
            FROM articles_details Ad
            JOIN cliches C on Ad.id_cliche = C.id_cliche
            JOIN villes V on Ad.id_ville = V.id_ville
            GROUP BY V.nom
            ORDER BY nombre_de_cliche DESC
            LIMIT 25;");

//now print the data
$data = array();
while ($row = pg_fetch_row($result)) {
    $data[] = $row;

}
print json_encode($data);