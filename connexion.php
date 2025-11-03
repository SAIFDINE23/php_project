<?php
$servername = "mysql"; // nom du service MySQL dans docker-compose
$username = "root";
$password = "root";
$dbname = "laravel";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) {
    die("Erreur de connexion : " . mysqli_connect_error());
}
echo "Connexion réussie à la base de données !";
?>
