<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF8" />
    <title>Inscription planning</title>
    <style>
        body{
            margin-top: 10%;
            text-align: center;
        }
        label{
            display: block;
        }
    </style>
</head>
<body>

<h1 style="font-size: 375%; margin-bottom: 20%">Inscription au planning</h1>

<form action="inscriptionPost.php" method="GET">
    <?php
    if(isset($_GET['error'])){
        ?>
        <label style="font-size: 375%">Nom : <input type="text" name="nom" value="<?= $_GET['nom'] ?>" placeholder="Lellouche" required  style="font-size: 70%"></label><br>
        <label style="font-size: 375%">Prénom : <input type="text" name="prenom" value="<?= $_GET['prenom'] ?>" placeholder="Gilles" required style="font-size: 70%"></label><br>
        <?php
        if($_GET['error'] == 'email'){
            echo "<h1 style='font-size: 375%; color: lightcoral'>Cette adresse email est déjà utilisée</h1><br>";
        }
        ?>
        <label style="font-size: 375%">Email : <input type="email" name="email" value="<?= $_GET['email'] ?>" placeholder="gilles.lellouche@gmail.fr" required style="font-size: 70%"></label><br>
        <label style="font-size: 375%">Genre :
            <select name="genre" required style="font-size: 70%">
                <?php
                if($_GET['genre'] == "M")
                    echo "<option value='M' selected>M</option>";
                else
                    echo "<option value='M'>M</option>";

                if($_GET['genre'] == "Mr")
                    echo "<option value='Mr' selected>Mr</option>";
                else
                    echo "<option value='Mr'>Mr</option>";

                if($_GET['genre'] == "Mlle")
                    echo "<option value='Mlle' selected>Mlle</option>";
                else
                    echo "<option value='Mlle'>Mlle</option>";

                if($_GET['genre'] == "Mme")
                    echo "<option value='Mme' selected>Mme</option>";
                else
                    echo "<option value='Mme'>Mme</option>";

                if($_GET['genre'] == "rien")
                    echo "<option value='rien' selected>rien</option>";
                else
                    echo "<option value='rien'>rien</option>";
                ?>
            </select>
        </label><br>
        <?php
        if($_GET['error'] == 'code'){
            echo "<h1 style='font-size: 375%; color: lightcoral'>Veuillez choisir un autre code</h1><br>";
        }
        ?>
        <label style="font-size: 375%">Clé personnelle : <input type="tel" name="code" value="<?= $_GET['code'] ?>" minlength="6" maxlength="6" placeholder="000000" required style="font-size: 70%; width: 16%"> (6 chiffres)</label><br>
        <label style="font-size: 375%">Travaillez-vous chez Santos ?
            <select name="santos" required style="font-size: 70%">
                <?php
                if($_GET['santos'] == "oui"){
                    echo "<option value='oui' selected>Oui</option>";
                    echo "<option value='non'>Non</option>";
                }
                else{
                    echo "<option value='oui'>Oui</option>";
                    echo "<option value='non' selected>Non</option>";
                }
                ?>
            </select>
        </label>
        <?php
    }
    else{
        ?>
        <label style="font-size: 375%">Nom : <input type="text" name="nom" placeholder="Lellouche" required  style="font-size: 70%"></label><br>
        <label style="font-size: 375%">Prénom : <input type="text" name="prenom" placeholder="Gilles" required style="font-size: 70%"></label><br>
        <label style="font-size: 375%">Email : <input type="email" name="email" placeholder="gilles.lellouche@gmail.fr" required style="font-size: 70%"></label><br>
        <label style="font-size: 375%">Genre :
            <select name="genre" required style="font-size: 70%">
                <option value="M">M</option>
                <option value="Mr">Mr</option>
                <option value="Mlle">Mlle</option>
                <option value="Mme">Mme</option>
                <option value="rien">rien</option>
            </select>
        </label><br>
        <label style="font-size: 375%">Clé personnelle : <input type="tel" name="code" minlength="6" maxlength="6" placeholder="000000" required style="font-size: 70%; width: 16%"> (6 chiffres)</label><br>
        <label style="font-size: 375%">Travaillez-vous chez Santos ?
            <select name="santos" required style="font-size: 70%">
                <option value="oui">Oui</option>
                <option value="non">Non</option>
            </select>
        </label>
        <?php
    }
    ?>
    <input type="submit" value="Créer" style="font-size: 312%; margin-top: 8%">
</form>
</body>
</html>