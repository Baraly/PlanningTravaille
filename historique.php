<?php
session_start();

if(!isset($_SESSION['id']))
    header("location: index.php");
else{
    include_once 'function/fonctionMois.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Historique des données</title>
    <style>
        body{
            margin-top: 40px;
            text-align: center;
        }
        a{
            text-decoration: none;
            color: #0000cc;
        }
    </style>
</head>
<body>
    <h1 style="font-size: 60px">Historique des données</h1>

    <?php
    $bdd = null;
    include_once 'function/bdd.php';
    $request = $bdd->query('SELECT MONTH(Datage) AS Mois, YEAR(Datage) AS Annee FROM Horaire WHERE IdUser = "'.$_SESSION['id'].'" GROUP BY MONTH(Datage), YEAR(Datage)');

    ?>
    <div style="text-align: left; margin: 80px 60px; height: 1200px; overflow: auto">
    <?php

    $rien = true;
    while($donnees = $request->fetch()){
        $rien = false;
        echo "<a style='font-size: 60px' href='generateurPDF.php?mois=".$donnees['Mois']."&annee=".$donnees['Annee']."'>Résumé de ".mois($donnees['Mois'])." ".$donnees['Annee']."</a><br>";
    }
    if($rien)
        echo "<h1 style='font-size: 50px; margin-top: 300px; text-align: center; font-style: italic'>Vous n'avez encore aucune donnée</h1>";
    ?>
    </div>
    <div style="margin-top: 100px">
        <a style="font-size: 60px; border: 1px solid gray; border-radius: 30px; background-color: #464646; color: white; padding: 20px 20px" href="planning.php">Retourner à la liste</a>
    </div>
</body>
</html>
<?php } ?>