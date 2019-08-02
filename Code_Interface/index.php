<?php

if(!isset($_SESSION)){
    session_start();
}

if(isset($_SESSION['success'])){
    header('Location: accueil.php');
}

?>


<html>
<head>
    <meta charset="utf-8">
    <title>Projet Base de Donnees 2019</title>
    <link rel="stylesheet" href="frontend/css/main.css">
    <link rel="shortcut icon" href="frontend/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="frontend/img/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="connectMain">
        <div>
            <form action="backend/account/login_handling.php" method="post">
                <fieldset>
                    <label for="user">Utilisateur:</label>
                    <input type="text" name="user" required>
                    <label for="password">Mot de passe:</label>
                    <input type="password" name="password" required>
                    <button type="submit">Se connecter</button>
                </fieldset>
            </form>
        </div>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</html>

