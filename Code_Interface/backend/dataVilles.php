<?php
require("../frontend/system/connect.php");


$result = pg_query($db,
    "SELECT  COUNT(Ad.article), V.nom as ville,SUM(c.nombre_cliche) as nombre_de_cliche
            FROM articles_details Ad
            JOIN villes V on Ad.id_ville = V.id_ville
            JOIN cliches c on Ad.id_cliche = c.id_cliche
            GROUP BY  V.nom
            ORDER BY nombre_de_cliche DESC 
            LIMIT 25;");

//now print the data
$data = array();
while ($row = pg_fetch_row($result)) {
    $data[] = $row;

}
print json_encode($data);