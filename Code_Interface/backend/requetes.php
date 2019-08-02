<?php
/**
 * Created by PhpStorm.
 * User: janosfalke
 * Date: 2019-02-10
 * Time: 22:39
 */


require("../frontend/system/connect.php");



//Nombre de cliché avant 2000
$result = pg_query($db, "SELECT SUM(C.nombre_cliche) as nombre_de_cliche
                                  FROM articles_details Ad
                                  JOIN cliches C on Ad.id_cliche = C.id_cliche
                                  WHERE Ad.date < to_date('01 01 2000', 'DD MM YYYY');");


while ($row = pg_fetch_row($result)) {

    echo "<div><p><span>Nombre de clichés avant 2000:</span>" .number_format($row[0], 0, ',', ' ')."</p>";

}


//Nombre de cliché pris en moyenne
$result = pg_query($db, "SELECT AVG(C.nombre_cliche) FROM cliches C;");


while ($row = pg_fetch_row($result)) {

    echo "<p><span>Nombre de clichés pris en moyenne :</span>" .bcdiv($row[0],1,2). "</p></div>";

}


?>




