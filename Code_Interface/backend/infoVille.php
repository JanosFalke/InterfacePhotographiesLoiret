<?php
/**
 * Created by PhpStorm.
 * User: janosfalke
 * Date: 2019-02-03
 * Time: 17:20
 */

require("../frontend/system/connect.php");

$ville = $_GET['ville'];

$result = pg_query($db, "SELECT nom, code_postal, latitude, longitude FROM villes WHERE nom = '$ville'");
$row = pg_fetch_row($result,0);


echo "<h4>".ucfirst($row[0])."</h4>";
echo "<ul><li><p>Code postal: </p><p>".$row[1]."</p></li>";
echo "<li><p>Latitude: </p><p>".$row[2]."</p></li>";
echo "<li><p>Longitude: </p><p>".$row[3]."</p></li></ul>";
?>