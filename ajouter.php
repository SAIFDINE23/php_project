<?php
// Traiter le formulaire AVANT toute sortie HTML
if(isset($_POST['button'])){
    // Extraction des informations
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $age = $_POST['age'] ?? '';

    if($nom && $prenom && $age){
        include_once "connexion.php";

        // Requête d'ajout
        $req = mysqli_query($con , "INSERT INTO Employe VALUES(NULL, '$nom', '$prenom','$age')");
        if($req){
            // Redirection après succès
            header("Location: index.php");
            exit(); // Toujours mettre exit() après header
        } else {
            $message = "Employé non ajouté";
        }
    } else {
        $message = "Veuillez remplir tous les champs !";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form">
        <a href="index.php" class="back_btn"><img src="images/back.png"> Retour</a>
        <h2>Ajout un employé</h2>
        <p class="erreur_message">
            <?php 
            if(isset($message)){
                echo $message;
            }
            ?>
        </p>
        <form action="" method="POST">
            <label>Nom</label>
            <input type="text" name="nom">
            <label>Prénom</label>
            <input type="text" name="prenom">
            <label>âge</label>
            <input type="number" name="age">
            <input type="submit" value="Ajouter" name="button">
        </form>
    </div>
</body>
</html>
