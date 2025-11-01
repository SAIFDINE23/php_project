<?php
  //connexion à la base de données
  $host = getenv('MYSQL_HOST') ?: 'localhost';
  $user = getenv('MYSQL_USER') ?: 'root';
  $pass = getenv('MYSQL_PASSWORD') ?: '';
  $db = getenv('MYSQL_DB') ?: 'laravel';
  
  $con = mysqli_connect($host, $user, $pass, $db);
  if(!$con){
     echo "Vous n'êtes pas connecté à la base de donnée";
  }
?>