<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF8"/>
    <title>Inscription planning</title>
    <style>
        body {
            margin-top: 10%;
            text-align: center;
            z-index: 1;
        }

        label {
            display: block;
        }

        input[type="text"], input[type="email"], input[type="tel"] {
            padding: 2% 1%;
            border: none;
            border-bottom: 1px gray solid;
            border-radius: 0;
            margin: 2% 0;
            width: 80%;
            font-size: 350%;
        }

        input[type="tel"] {
            display: inline-block;
        }

        select {
            display: inline-block;
            font-size: 350%;
            padding: 2% 2%;
            margin: 2% 0;
            border-radius: 30px;
        }

        input[type="submit"] {
            -webkit-appearance: none;
            width: 78%;
            margin-top: 20%;
            font-size: 350%;
            padding: 3% 0;
            border: none;
            border-radius: 20px;
            color: white;
            background-color: #3B326C;
            box-shadow: #3B326C 0 60px 60px -40px;
        }

        .popup {
            display: inline-block;
            padding: 6% 4%;
            position: fixed;
            width: 80%;
            font-size: 350%;
            border: 5px solid lightslategrey;
            box-shadow: lightgray 0 0 40px 20px;
            border-radius: 60px;
            left: 45%;
            top: 45%;
            transform: translate(-45%, -45%);
            background-color: white;
            z-index: 9;
        }

        .popup p {
            margin-top: 0;
            padding-top: 0;
        }

        .popup a {
            display: inline-block;
            width: 70%;
            color: black;
            text-decoration: none;
            padding: 2% 0;
            border: 3px dashed gray;
            border-radius: 30px;
        }

        .popup a:hover {
            color: black;
            text-decoration: none;
        }

        .overlay {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-color: white;
            z-index: 8;
        }
    </style>
</head>
<div class="overlay" id="overlay">
    <body>

    <h1 style="font-size: 375%; margin-bottom: 20%">Inscription au planning</h1>

    <form action="inscriptionPost.php" method="GET">
        <?php
        if (isset($_GET['error'])) {
            ?>
            <input type="text" name="nom" value="<?= $_GET['nom'] ?>" placeholder="Nom" required><br>
            <input type="text" name="prenom" value="<?= $_GET['prenom'] ?>" placeholder="Pr??nom" required><br>
            <?php
            if ($_GET['error'] == 'email') {
                ?>
                <div class="popup" id="popupEmail">
                    <style>
                        .overlay {
                            background-color: rgba(0, 0, 0, 0.5);
                        }
                    </style>
                    <p>L'adresse email <span style="font-weight: bold"><?= $_GET['email'] ?></span> est d??j??
                        utilis??e.<br>
                        Si vous rencontrez des difficult??s ?? vous connecter ?? votre compte, alors veuillez contacter le
                        support :
                        <span style="color: royalblue; text-decoration: underline">baptiste.bronsin@outlook.com</span>.
                    </p>
                    <a href="#" onclick="closePopup('popupEmail')">J'ai compris</a>
                </div>
                <?php
            }
            ?>
            <input type="email" name="email" value="<?= $_GET['email'] ?>" placeholder="E-mail" required><br>
            <select name="genre" required>
                <?php
                if ($_GET['genre'] == "M")
                    echo "<option value='M' selected>M</option>";
                else
                    echo "<option value='M'>M</option>";

                if ($_GET['genre'] == "Mr")
                    echo "<option value='Mr' selected>Mr</option>";
                else
                    echo "<option value='Mr'>Mr</option>";

                if ($_GET['genre'] == "Mlle")
                    echo "<option value='Mlle' selected>Mlle</option>";
                else
                    echo "<option value='Mlle'>Mlle</option>";

                if ($_GET['genre'] == "Mme")
                    echo "<option value='Mme' selected>Mme</option>";
                else
                    echo "<option value='Mme'>Mme</option>";

                if ($_GET['genre'] == "rien")
                    echo "<option value='rien' selected>rien</option>";
                else
                    echo "<option value='rien'>rien</option>";
                ?>
            </select><br>
            <?php
            if ($_GET['error'] == 'code') {
                ?>
                <div class="popup" id="popupCode">
                    <style>
                        .overlay {
                            background-color: rgba(0, 0, 0, 0.5);
                        }
                    </style>
                    <p>La cl?? personnelle <span style="font-weight: bold"><?= $_GET['code'] ?></span> est d??j??
                        utilis??e.<br>
                        Si vous rencontrez des difficult??s ?? vous connecter ?? votre compte, alors veuillez contacter le
                        support :
                        <span style="color: royalblue; text-decoration: underline">baptiste.bronsin@outlook.com</span>.
                    </p>
                    <a href="#" onclick="closePopup('popupCode')">J'ai compris</a>
                </div>
                <?php
            }
            ?>
            <input type="tel" name="code" value="<?= $_GET['code'] ?>" minlength="6" maxlength="6"
                   placeholder="Cl?? personnelle" required><br>
            <label style="font-size: 350%">Pour quelle entreprise travaillez-vous :
                <select name="societe" required style="font-size: 100%">
                    <?php

                    $bdd = null;
                    include_once 'function/bdd.php';

                    $request = $bdd->query("SELECT NomSociete FROM Coupure GROUP BY NomSociete");
                    while ($donnee = $request->fetch()) {
                        if ($_GET['societe'] == $donnee['NomSociete'])
                            echo "<option value='" . $donnee['NomSociete'] . "' selected>" . $donnee['NomSociete'] . "</option>";
                        else
                            echo "<option value='" . $donnee['NomSociete'] . "'>" . $donnee['NomSociete'] . "</option>";

                    }
                    ?>
                </select>
            </label>
            <?php
        } else {
            ?>
            <input type="text" name="nom" placeholder="Nom" required><br>
            <input type="text" name="prenom" placeholder="Pr??nom" required><br>
            <input type="email" name="email" placeholder="E-mail" required><br>
            <select name="genre" required>
                <option value="M">M</option>
                <option value="Mr">Mr</option>
                <option value="Mlle">Mlle</option>
                <option value="Mme">Mme</option>
                <option value="rien">rien</option>
            </select>
            <br>
            <input type="tel" name="code" minlength="6" maxlength="6" placeholder="Cl?? personnelle" required>
            <br>
            <label style="font-size: 350%">Pour quelle entreprise travaillez-vous :
                <select name="societe" required style="font-size: 100%">
                    <?php
                    $bdd = null;
                    include_once 'function/bdd.php';

                    $request = $bdd->query("SELECT NomSociete FROM Coupure GROUP BY NomSociete");
                    while ($donnee = $request->fetch()) {
                        echo "<option value='" . $donnee['NomSociete'] . "'>" . $donnee['NomSociete'] . "</option>";
                    }
                    ?>
                    <option value="autre">Autre</option>
                </select>
            </label>
            <?php
        }
        ?>

        <input type="submit" value="Cr??er mon compte">

    </form>
    <script>
        function closePopup(type) {
            document.getElementById("overlay").style.backgroundColor = "white";
            document.getElementById(type).style.display = "none";
        }
    </script>
    </body>
</div>
</html>