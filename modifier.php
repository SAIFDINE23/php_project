<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include_once "connexion.php";

// Récupération de l'id
$id = $_GET['id'] ?? 0;
if(!$id){
    die("ID invalide !");
}

// Récupération des données de l'employé
$req = mysqli_query($con, "SELECT * FROM Employe WHERE id = $id");
$row = mysqli_fetch_assoc($req);

// Vérification de la soumission du formulaire
if(isset($_POST['button'])){
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $age = $_POST['age'] ?? '';

    if(!empty($nom) && !empty($prenom) && !empty($age)){
        // Protection contre les injections SQL
        $nom = mysqli_real_escape_string($con, $nom);
        $prenom = mysqli_real_escape_string($con, $prenom);
        $age = (int)$age;

        // Requête de modification
        $req = mysqli_query($con, "UPDATE Employe SET nom = '$nom', prenom = '$prenom', age = $age WHERE id = $id");

        if($req){
            header("Location: index.php");
            exit();
        } else {
            $message = "Erreur : Employé non modifié.";
        }
    } else {
        $message = "Veuillez remplir tous les champs !";
    }
}
?>

<div class="form">
    <a href="index.php" class="back_btn"><img src="images/back.png"> Retour</a>
    <h2>Modifier l'employé : <?= htmlspecialchars($row['nom']) ?></h2>
    <p class="erreur_message">
        <?php if(isset($message)) echo $message; ?>
    </p>
    <form action="" method="POST">
        <label>Nom</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($row['nom']) ?>">
        <label>Prénom</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($row['prenom']) ?>">
        <label>Âge</label>
        <input type="number" name="age" value="<?= htmlspecialchars($row['age']) ?>">
        <input type="submit" value="Modifier" name="button">
    </form>
</div>
</body>
</html>
