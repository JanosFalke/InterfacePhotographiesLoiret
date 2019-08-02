<?php
    if(!isset($_SESSION)){
        session_start();

    }

   $host        = "host = 127.0.0.1";
   $port        = "port = 5432";
   $dbname      = "dbname = projet";
   $credentials = "user = ".$_SESSION['user']." password=".$_SESSION['password'];

   $db = pg_connect( "$host $port $dbname $credentials"  );

   if(!$db) {
       header('Location: ../../index.php');
   } else {

       $_SESSION['success'] = "true";
       $user = $_SESSION['user'];

       // VERIFIER SI L'UTILISATEUR EST UN ADMIN (GRANTOR) OU PAS --> utilisateur normal!
       $result = pg_query($db, "SELECT DISTINCT grantor
                              FROM information_schema.role_table_grants WHERE grantor = '$user'");
       while ($row = pg_fetch_row($result)) {
           $_SESSION['loggedIn'] = "admin";
       }

      #echo "Opened database successfully\n";
   }


?>