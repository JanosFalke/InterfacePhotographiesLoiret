<?php
/**
 * Created by PhpStorm.
 * User: janosfalke
 * Date: 2019-02-04
 * Time: 21:56
 */
if(!isset($_SESSION)){
    session_start();

}

session_destroy();


?>

<form name="fr" role="form" action='../../index.php' method='GET'>
</form>

<script type='text/javascript'>document.fr.submit();</script>
