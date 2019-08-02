<?php
require("../frontend/system/connect.php");


$result = pg_query($db,
    "SELECT Tc.longueur, Tc.largeur, sum(C.nombre_cliche) as nb_cliches
            FROM cliches C
            JOIN tailles_cliches Tc on C.id_taille_cliche = Tc.id_taille_cliche
            GROUP BY (Tc.longueur, Tc.largeur)
            ORDER BY nb_cliches DESC 
            LIMIT 5;");

//now print the data
$data = array();
while ($row = pg_fetch_row($result)) {
    $data[] = $row;

}
print json_encode($data);