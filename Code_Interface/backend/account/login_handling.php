<?php
/**
 * Created by PhpStorm.
 * User: janosfalke
 * Date: 2019-02-04
 * Time: 21:47
 */





if(isset($_POST['user']) && isset($_POST['password'])) {
    if(!isset($_SESSION)){
        session_start();
    }

    $name = strtolower($_POST['user']);
    $password = $_POST['password'];


    $_SESSION['user'] = $name;
    $_SESSION['password'] = $password;
}


?>
    <form name="fr" role="form" action='../../accueil.php' method='GET'>
    </form>

    <script type='text/javascript'>document.fr.submit();</script>
