<?php
/**
 * Created by PhpStorm.
 * User: janosfalke
 * Date: 2019-01-28
 * Time: 21:35
 */
/**
 * Projet BSD 2019
 * Falke Janos & Adalbert Michael
 */

if(!isset($_SESSION)){
    session_start();
}

if(!$_SESSION['success']){
    header('Location: ../../index.php');
}

require("../system/connect.php");


?>
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
                    <a class="active" href="charts.php">Charts</a>

                    <?php if(isset($_SESSION['loggedIn'])): ?>

                    <a href="modification.php">Modifications</a>

                    <?php endif; ?>
            </div>
        </nav>
    </section>

    <section>
        <div class="mainStatistics">
            <div>
                <h4>Statistiques et visualisation des requêtes utiles </h4>

            </div>

            <div class="chartVilles">
                <div class="headerChart">
                    <h3>Top 25 Villes : Nombre d'articles & de clichés</h3>
                </div>
                <div class="chart_container">
                    <canvas id="topVilles"></canvas>
                </div>
            </div>
            <div class="chartFormats">
                <div class="headerChart">
                    <h3>Top 5 : Nombre de clichés par format</h3>
                </div>
                <div class="chart_container">
                    <canvas id="topFormats"></canvas>
                </div>
            </div>
            <div class="chartMoyen">
                <div class="headerChart">
                    <h3>Top 25 : Nombre de clichés moyen par article dans une ville</h3>
                </div>
                <div class="chart_container">
                    <canvas id="topMoyen"></canvas>
                </div>
            </div>
            <div class="statsContent">
                <div class="headerChart">
                    <h3>Quelques statistiques utiles:</h3>
                </div>
                <div class="statistiques_container">

                </div>
            </div>
        </div>
    </section>


</main>

<?php include("../templates/footer.php"); ?>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="../../backend/plugins/chartjs/chart.min.js"></script>
<script type = "text/javascript" src = "../../backend/js/charts.js" ></script>
</html>
