<?php
require("../frontend/system/connect.php");

if(!isset($_SESSION)){
    session_start();

}

$search_values = $_GET["query"];
$perimetre = $_GET["perimetre"];
$ville = $search_values["ville"];

$villeExiste = false;
if($ville != ''){
    if (!ctype_digit($search_values["ville"])) {
        $ville = strtolower($ville);
        $villeExistes = pg_query($db, "SELECT COUNT(DISTINCT nom) FROM articles_details ad
                                  LEFT JOIN Villes v ON v.id_ville = ad.id_ville
                                  WHERE nom = '$ville'");
    } else {
        $villeExistes = pg_query($db, "SELECT COUNT(DISTINCT nom) FROM articles_details ad
                                  LEFT JOIN Villes v ON v.id_ville = ad.id_ville
                                  WHERE CAST(code_postal AS TEXT) LIKE '$ville'");
    }

    $rowsVilleExiste = pg_fetch_result($villeExistes, 0, 0);

    if($rowsVilleExiste == 1){
        $villeExiste = true;

        if (!ctype_digit($search_values["ville"])) {
            $long_latQuery = pg_query($db, "SELECT longitude, latitude FROM villes 
                                                WHERE nom = '$ville'");

        } else {
            $long_latQuery = pg_query($db, "SELECT longitude, latitude FROM villes 
                                                WHERE CAST(code_postal AS TEXT) LIKE '$ville'");
        }
        $rowLongLat = pg_fetch_row($long_latQuery,0);
        $long_start = $rowLongLat[0];
        $lat_start = $rowLongLat[1];
    }
}


$limit = $_GET["rowNumbers"]; //nombre d'enregistrement par page maximal
$desc = "";

if($_GET["sorted"] == null){
    $sorted = "article";

} else if($_GET["sorted"] == "Reference" || $_GET["sorted"] == "Referencesame"){
    $sorted = "reference_cindoc";

} else if($_GET["sorted"] == "Ville" || $_GET["sorted"] == "Villesame"){
    $sorted = "nom";

}  else {
    $sorted = $_GET["sorted"];
}


if(substr($_GET["sorted"], -4) == "same") {
    $desc = "DESC";
    if(substr($sorted, -4) == "same"){
        $sorted = substr_replace($sorted, "", -4);
    }
}


if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
if($_GET["page"] == null){
    $page=1;
}
$start_from = ($page-1) * $limit;


if($search_values["article"] == "" && $search_values["reference"] == "" && $search_values["sujet"] == "" &&
    $search_values["date"] == "" && $search_values["ville"] == "" && $search_values["index_icono"] == "" &&
    $search_values["index_personnes"] == "" && $search_values["neg_inf"] == "" &&
    $search_values["coul_nb"] == "" && $search_values["taille"] == ""){

    $queryy = ("SELECT reference_cindoc, article, sujet, nom, date , id_cliche 
                FROM articles_details ad
                LEFT JOIN Villes v ON v.id_ville = ad.id_ville
                ORDER BY $sorted $desc 
                OFFSET $start_from LIMIT $limit
     ");

    $mainResult = pg_query($db, $queryy);

    $rowCount = pg_query($db, "SELECT COUNT(*) FROM articles_details");
    $clicheCount = pg_query($db, "SELECT SUM(c.nombre_cliche) FROM articles_details ad JOIN cliches c ON c.id_cliche = ad.id_cliche");

    $villeCount = pg_query($db, "SELECT COUNT(DISTINCT ad.id_ville) FROM articles_details ad");

    $executionQuery = "EXPLAIN ANALYZE ".$queryy;
    $executionResult = pg_query($db, $executionQuery);

} else
{

    $query = "";

    $first = false;
    if($search_values["article"] != ''){
        $query = $query."CAST(article AS TEXT) LIKE '%".$search_values["article"]."%' ";
        $first = true;
    }

    if($search_values["reference"] != ''){
        if($first){
            $query = $query."AND ";
        }
        $first = true;
        $query = $query."CAST(reference_cindoc AS TEXT) LIKE '%".$search_values["reference"]."%' ";
    }

    if($search_values["sujet"] != ''){
        if($first){
            $query = $query."AND ";
        }
        $first = true;
        $query = $query."sujet LIKE '%".strtolower($search_values["sujet"])."%' ";
    }


    if($search_values["index_icono"] != ''){
        if($first){
            $query = $query."AND ";
        }
        $first = true;
        $query = $query."index_iconographique LIKE '%".strtolower($search_values["index_icono"])."%' ";
    }

    if($search_values["index_personnes"] != ''){
        if($first){
            $query = $query."AND ";
        }
        $first = true;
        $query = $query."index_personnes LIKE '%".strtolower($search_values["index_personnes"])."%' ";
    }

    if($search_values["date"] != ''){
        if($first){
            $query = $query."AND ";
        }
        $first = true;
        $query = $query."CAST(date as TEXT) LIKE '".$search_values["date"]."' ";
    }

    if($search_values["ville"] != '') {
        if($perimetre == 0 || !$villeExiste) {
            if($first){
                $query = $query."AND ";
            }
            $first = true;

            if (!ctype_digit($search_values["ville"])) {
                $villeNom = strtolower($search_values["ville"]);
                $query = $query . "nom LIKE '%" . $villeNom . "%' ";

            } else {
                $codePostal = $search_values["ville"];
                $query = $query . "CAST(code_postal AS TEXT) LIKE '" . $codePostal . "%' ";
            }
        }
    }

    if($search_values["neg_inf"] != ''){
        if($first){
            $query = $query."AND ";
        }
        $first = true;
        $query = $query."negatif_ou_inversible LIKE '".$search_values["neg_inf"]."' ";
    }

    if($search_values["coul_nb"] != ''){
        if($first){
            $query = $query."AND ";
        }
        $first = true;
        $query = $query."couleur_ou_noir_et_blanc LIKE '".$search_values["coul_nb"]."' ";
    }

    if($search_values["taille"] != ''){
        if($first){
            $query = $query."AND ";
        }
        $first = true;
        $query = $query."id_taille_cliche = ".$search_values["taille"]." ";
    }


    if($query != ''){
        $query = "WHERE ".$query;
    }


    if($perimetre == 0 || !$villeExiste) {
        $mainQuery = "SELECT reference_cindoc, article, sujet, nom, ad.date, ad.id_cliche 
              FROM articles_details ad 
              LEFT JOIN Villes v ON v.id_ville = ad.id_ville 
              JOIN cliches c ON c.id_cliche = ad.id_cliche
              " . $query . "
              GROUP BY reference_cindoc, article, sujet, nom, ad.date, ad.id_cliche 
              ORDER BY $sorted $desc 
              OFFSET $start_from LIMIT $limit";

    } else{
        $mainQuery = "SELECT reference_cindoc, article, sujet, nom, ad.date, ad.id_cliche , longitude, latitude
              FROM articles_details ad 
              LEFT JOIN Villes v ON v.id_ville = ad.id_ville
              JOIN cliches c ON c.id_cliche = ad.id_cliche
              ".$query."
              GROUP BY reference_cindoc, article, sujet, nom, ad.date, ad.id_cliche, longitude, latitude
              HAVING (SQRT(POWER((".$long_start."-v.longitude),2)+POWER((".$lat_start."-v.latitude),2)))/1000 <= ".$perimetre." 
              ORDER BY $sorted $desc 
              OFFSET $start_from LIMIT $limit";

    }

    $executionQuery = "EXPLAIN ANALYSE ".$mainQuery;

    $executionResult = pg_query($db, $executionQuery);
    $mainResult = pg_query($db, $mainQuery);



    if($perimetre == 0 || !$villeExiste) {
        $rowCountQuery = "SELECT COUNT(*) 
                      FROM articles_details ad
                      LEFT JOIN Villes v ON v.id_ville = ad.id_ville
                      JOIN cliches c ON c.id_cliche = ad.id_cliche
                      " . $query;
    } else {
        $rowCountQuery = "SELECT COUNT(*) 
                      FROM articles_details ad
                      LEFT JOIN Villes v ON v.id_ville = ad.id_ville
                      JOIN cliches c ON c.id_cliche = ad.id_cliche
                      " .$query."
                      GROUP BY longitude, latitude
                      HAVING (SQRT(POWER((".$long_start."-v.longitude),2)+POWER((".$lat_start."-v.latitude),2)))/1000 <= ".$perimetre."
                      ;";
    }

    $rowCount = pg_query($db, $rowCountQuery);


    if($perimetre == 0 || !$villeExiste) {
        $clicheCountQuery = "SELECT SUM(c.nombre_cliche) 
                          FROM articles_details ad
                          LEFT JOIN Villes v ON v.id_ville = ad.id_ville
                          JOIN cliches c ON c.id_cliche = ad.id_cliche
                          " . $query;
    } else {
        $clicheCountQuery = "SELECT SUM(c.nombre_cliche) 
                          FROM articles_details ad
                          LEFT JOIN Villes v ON v.id_ville = ad.id_ville
                          JOIN cliches c ON c.id_cliche = ad.id_cliche
                          " .$query."
                          GROUP BY longitude, latitude
                          HAVING (SQRT(POWER((".$long_start."-v.longitude),2)+POWER((".$lat_start."-v.latitude),2)))/1000 <= ".$perimetre.";";
    }

    $clicheCount = pg_query($db, $clicheCountQuery);

    if($perimetre == 0 || !$villeExiste) {
        $villeCountQuery = "SELECT COUNT(DISTINCT ad.id_ville) 
                          FROM articles_details ad
                          LEFT JOIN Villes v ON v.id_ville = ad.id_ville
                          JOIN cliches c ON c.id_cliche = ad.id_cliche
                          " . $query;
    } else {
        $villeCountQuery = "SELECT COUNT(DISTINCT ad.id_ville) 
                          FROM articles_details ad
                          LEFT JOIN Villes v ON v.id_ville = ad.id_ville
                          JOIN cliches c ON c.id_cliche = ad.id_cliche
                          " .$query."
                          GROUP BY longitude, latitude
                          HAVING (SQRT(POWER((".$long_start."-v.longitude),2)+POWER((".$lat_start."-v.latitude),2)))/1000 <= ".$perimetre.";";
    }

    $villeCount = pg_query($db, $villeCountQuery);


}

if (!$mainResult) {
    echo "Une erreur s'est produite.\n";
    exit;
}

$rows = 0;
$nb_cliches = 0;
$nb_villes = 0;

while ($rowCountData = pg_fetch_row($rowCount)) {
    $rows += $rowCountData[0];
}

while ($rowclicheCountData = pg_fetch_row($clicheCount)) {
    $nb_cliches += $rowclicheCountData[0];
}

while ($rowVilleCount = pg_fetch_row($villeCount)) {
    $nb_villes += $rowVilleCount[0];
}

if($nb_villes > 1) {
    $villeHeader = "villes";
} else {
    $villeHeader = "ville";
}


$total_pages = ceil($rows/$limit);

echo "<div class='statsRows'><p><span class='foundEnreg'>".number_format($nb_villes, 0, ',', ' ')."</span> $villeHeader </p>";
echo "<p><span class='foundEnreg'>".number_format($rows, 0, ',', ' ')."</span> enregistrements</p>";
echo "<p><span class='foundEnreg'>".number_format($nb_cliches, 0, ',', ' ')."</span> clichés</p></div>";

echo "
<div id='interface_table'>
    <table>
    <thead>
        <tr id='sort_table'>";


    if($sorted == "reference_cindoc"){
        if($desc == "") {
            echo "<th class='sortedDown'>Reference</th>";
        } else {
            echo "<th class='sortedUp'>Reference</th>";
        }
    } else {
        echo "<th class='unsorted'>Reference</th>";
    }

    if($sorted == "Article"){
        if($desc == "") {
            echo "<th class='sortedDown'>Article</th>";
        } else {
            echo "<th class='sortedUp'>Article</th>";
        }
    } else {
        echo "<th class='unsorted'>Article</th>";
    }

    if($sorted == "Sujet"){
        if($desc == "") {
            echo "<th class='sortedDown'>Sujet</th>";
        } else {
            echo "<th class='sortedUp'>Sujet</th>";
        }
    } else {
        echo "<th class='unsorted'>Sujet</th>";
    }

    if($sorted == "nom"){
        if($desc == "") {
            echo "<th class='sortedDown'>Ville</th>";
        } else {
            echo "<th class='sortedUp'>Ville</th>";
        }
    } else {
        echo "<th class='unsorted'>Ville</th>";
    }

    if($sorted == "Date"){
        if($desc == "") {
            echo "<th class='sortedDown'>Date</th>";
        } else {
            echo "<th class='sortedUp'>Date</th>";
        }
    } else {
        echo "<th class='unsorted'>Date</th>";
    }


    echo "<th class='nosort'>Cliché</th>";

    if(isset($_SESSION['loggedIn'])) {
        echo "<th class='nosort modificationsTab'>Modifications</th>";
    }



    echo "</tr>
        </theah><tbody onscroll='closePopUpVilleCliche()'>";


while ($row = pg_fetch_row($mainResult)) {
    echo '<tr id=\'content_table\'>';
    echo "<td class='detailsVille cliche".$row[5]."'>" . $row[0] . "</td>";
    echo "<td class='detailsVille cliche".$row[5]."'>" . $row[1] . "</td>";
    echo "<td class='detailsVille cliche".$row[5]."'>" . ($row[2]) . "</td>";

    echo '<td><div class="popUpVilles popUpVilles'.ucwords(str_replace(' ', '', $row[3])).'"
    ><span onclick="popUpVilleEnter(event,\''.ucwords(str_replace(' ', '', $row[3])).''.$row[5]."','".$row[3].'\')">'. ucwords($row[3]).'</span></div>
    <div class="popupTextVille popupTextVille'.ucwords(str_replace(' ', '', $row[3])).''.$row[5].'"><i>Loading...</i></div></td>';

    echo "<td class='detailsVille cliche".$row[5]."'>" .date("d/m/Y",strtotime($row[4])) . "</td>";

    echo '<td><div class="popUpCliches popUpCliches'.$row[5].'"><span onclick="popUpClicheEnter(event, '.$row[5].')">Détails cliché</span></div>
    <div class="popupTextCliche popupTextCliche'.$row[5].'"><i>Loading...</i></div></td>';

    if(isset($_SESSION['loggedIn'])) {
        echo "<td class='modificationInterface'>
                <p class='cliche".$row[5]."'><img src='frontend/img/edit.png' alt='edit'></p>
                <p><a href='backend/account/suppArticle.php?action=delete&cliche=$row[5]'><img src='frontend/img/delete.png' alt='delete' onclick=\"return confirm('Cet article va être supprimé définitivement!')\"></a></p>
            </td>";
    }

    echo "</tr>";


    echo "<tr class='tableVille ".$row[5]."'></tr>";


}

echo "</tbody></table></div>";

echo "<div id='pagination'>";

if($page > 1){
    echo '<p class="pageArrow" id="page1"><<</p>';
} else {
    echo '<p id="page1" class="pageArrow unactive"><<</p>';
}

if($page > 10) {
    echo '<p class="pageArrow" id="page' . ($page - 10) . '"><</p>';
} else if($page > 1){
    echo '<p class="pageArrow" id="page1"><</p>';
} else {
    echo '<p id="page1" class="pageArrow unactive"><</p>';
}


if($page > 0 && $page < $total_pages+1){

    if($total_pages > 5) {
        if ($page < 3) {
            for ($i = 0; $i <= 4; $i++) {

                if($page == $i+1){
                    echo '<p class ="selectedPage" id="page' . (1 + $i) . '">' . (1 + $i) . "</p>";

                } else {
                    echo '<p id="page' . (1 + $i) . '">' . (1 + $i) . "</p>";
                }
            }

        } else if ($page >= $total_pages - 2) {
            for ($i = -4; $i <= 0; $i++) {
                if($page == $total_pages+$i){
                    echo '<p class="selectedPage" id="page' . ($total_pages + $i) . '">' . ($total_pages + $i) . "</p>";
                } else {
                    echo '<p id="page' . ($total_pages + $i) . '">' . ($total_pages + $i) . "</p>";
                }

            }

        } else {
            for ($i = -2; $i <= 2; $i++) {
                if($page == $page+$i) {
                    echo '<p class="selectedPage" id="page' . ($page + $i) . '">' . ($page + $i) . "</p>";
                } else {
                    echo '<p id="page' . ($page + $i) . '">' . ($page + $i) . "</p>";
                }
            }
        }

    } else {
        for ($i = 1; $i <= $total_pages; $i++) {
            if($page == $i){
                echo '<p class="selectedPage" id="page' . $i . '">' . $i . "</p>";
            } else {
                echo '<p id="page' . $i . '">' . $i . "</p>";
            }
        }
    }
}

if($page < $total_pages-9){
    echo '<p class="pageArrow" id="page'.($page+10).'">></p>';
} else if($page < $total_pages){
    echo '<p class="pageArrow" id="page'.($total_pages).'">></p>';
} else {
    echo '<p id="page' .($total_pages). '" class="pageArrow unactive">></p>';
}

if($page < $total_pages) {
    echo '<p class="pageArrow" id="page' . ($total_pages) . '">>></p>';
} else {
    echo '<p id="page' .($total_pages). '" class="pageArrow unactive">>></p>';
}

echo "</div>";


$executionDetails = array();
//Execution time : Analyse
while ($rowExecution = pg_fetch_row($executionResult)) {
    array_unshift($executionDetails, $rowExecution[0]);
}

echo "<div class='infoExecution'>
        <p>".$executionDetails[1]."</p>
        <p>".$executionDetails[0]."</p></div>";

if(isset($_SESSION['loggedIn'])) {
    //Quand l'admin appuie sur modifier article
    echo '<div class="articleModify"><div class="chargementDonnees"><img src="frontend/img/loading.gif"></div></div>';
}
?>