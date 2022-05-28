<?php

$bdd = null;
include_once 'function/bdd.php';

$nom = $_GET['nom'];
$prenom = $_GET['prenom'];
$email = $_GET['email'];
$genre = $_GET['genre'];
$code = $_GET['code'];
$societe = $_GET['societe'];

if ($bdd->query("SELECT * FROM User WHERE email = '$email'")->fetch()) {
    header("location: inscription.php?error=email&nom=$nom&prenom=$prenom&email=$email&genre=$genre&code=$code&santos=$santos");
} elseif ($bdd->query("SELECT * FROM User WHERE Code = '$code'")->fetch()) {
    header("location: inscription.php?error=code&nom=$nom&prenom=$prenom&email=$email&genre=$genre&code=$code&santos=$santos");
} else {
    $id = $bdd->query("SELECT count(*) AS n FROM User")->fetch();
    $idNumber = intval($id['n']) + 1;
    $nom = strtolower($nom);
    $nom = ucwords($nom);

    $prenom = strtolower($prenom);
    $prenom = ucwords($prenom);

    $email = strtolower($email);

    if ($genre == "rien")
        $genre = "";

    $bddOK = true;
    if ($societe != "autre")
        $bbdOK = $bdd->exec("INSERT INTO User(`Id`, `Nom`, `Prenom`, `email`, `Genre`, `Code`, `Societe`) VALUES (" . $idNumber . ", '$nom', '$prenom', '$email', '$genre', '$code', '$societe')");
    else
        $bbdOK = $bdd->exec("INSERT INTO User(`Id`, `Nom`, `Prenom`, `email`, `Genre`, `Code`, `Societe`) VALUES (" . $idNumber . ", '$nom', '$prenom', '$email', '$genre', '$code', )");

    if ($bddOK) {
        echo "<p style='text-align: center; font-size: 350%; margin-top: 20%'>Et voilà <span style='font-weight: bold'>$prenom</span>, le compte vient d'être créé !<br>";
        if ($societe != "autre")
            echo "Vous avez été associé à l'entreprise <span style='font-weight: bold'>" . $societe . "</span>.<br>";
        echo "N'oubliez pas que le code d'accès est <span style='font-weight: bold'>$code</span>.<br>Vous pouvez dès à présent vous connecter au site avec ce lien :<br> <a href='index.php'>Authetification Planning</a></p>";
    } else {
        echo "<p style='text-align: center; font-size: 350%; margin-top: 20%'>Un problème est survenu lors de la création de votre compte !<br>Veuillez contacter Baptiste : <span style='font-weight: bold; color: royalblue'>06.50.35.34.21</span></p>";
    }


}

?>