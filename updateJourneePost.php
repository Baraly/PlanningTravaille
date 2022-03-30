<?php
session_start();

date_default_timezone_set('Europe/Paris');

$bdd = null;
include_once 'function/bdd.php';
include_once 'function/fonctionHeures.php';


if (isset($_GET['ajoutNow'])) {
    if (!$bdd->query("SELECT * FROM Horaire WHERE Datage = '" . date('Y-m-d') . "' AND IdUser = '" . $_SESSION['id'] . "'")->fetch()) {
        $insert = $bdd->prepare("INSERT INTO Horaire(IdUser, Datage, HDebut) VALUES(:idUser, :datage, :debut)");

        $errorDataBase = $insert->execute(array(
            'idUser' => $_SESSION['id'],
            'datage' => date('Y-m-d'),
            'debut' => date('H:i')));
    }
    if (!$errorDataBase)
        echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
    else
        header("location: updateJournee.php");
}


if (isset($_GET['pause'])) {
    $donnees = $bdd->query("SELECT Id FROM Horaire WHERE IdUser = '" . $_SESSION['id'] . "' AND Datage = '" . date('Y-m-d') . "'")->fetch();
    $insert = $bdd->prepare("INSERT INTO Pause(IdHoraire, HDebut) VALUES(:idH, :HB)");
    $errorDataBase = $insert->execute(array(
        'idH' => $donnees['Id'],
        'HB' => date('H:i')
    ));
    if (!$errorDataBase)
        echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
    else
        header("location: updateJournee.php");
}

if (isset($_GET['pauseFin'])) {
    $donnees = $bdd->query("SELECT Id FROM Horaire WHERE IdUser = '" . $_SESSION['id'] . "' AND Datage = '" . date('Y-m-d') . "'")->fetch();
    $errorDataBase = $bdd->exec("UPDATE Pause SET HFin = '" . date('H:i') . "' WHERE IdHoraire = " . $donnees['Id'] . " AND HFin IS NULL");

    if (!$errorDataBase)
        echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
    else
        header("location: updateJournee.php");
}

if (isset($_GET['finitNow'])) {

    $errorDataBase = $bdd->exec("UPDATE Horaire SET HFin = '" . date('H:i') . "' WHERE Datage = '" . date('Y-m-d') . "' AND IdUser = '" . $_SESSION['id'] . "'");

    $donnees = $bdd->query("SELECT * FROM Horaire WHERE Datage = '" . date('Y-m-d') . "' AND IdUser = '" . $_SESSION['id'] . "'")->fetch();

    $heureTravailleStr = differenceHeures($donnees['HDebut'], $donnees['HFin']);
    $minutesHeureTravaille = (int)date('H', strtotime($heureTravailleStr)) * 60 + (int)date('i', strtotime($heureTravailleStr));

    if ($bdd->query("SELECT * FROM User WHERE Id = '" . $_SESSION['id'] . "' AND Santos = 1")->fetch()) {
        if ($minutesHeureTravaille < 390)
            $coupure = '00:00:00';
        elseif ($minutesHeureTravaille < 570)
            $coupure = '00:45:00';
        elseif ($minutesHeureTravaille < 750)
            $coupure = '01:15:00';
        else
            $coupure = '01:30:00';

        if ($coupure != '00:00:00')
            $errorDataBase &= $bdd->exec("UPDATE Horaire SET Coupure = '$coupure' WHERE Datage = '" . date('Y-m-d') . "' AND IdUser = '" . $_SESSION['id'] . "'");
    } else {
        $request = $bdd->query("SELECT * FROM Pause WHERE IdHoraire = " . $donnees['Id']);
        $minutesSomme = 0;
        while ($d = $request->fetch()) {
            $minutesSomme += (date('H', strtotime($d['HFin'])) * 60 + date('i', strtotime($d['HFin']))) - (date('H', strtotime($d['HDebut'])) * 60 + date('i', strtotime($d['HDebut'])));
        }
        $heures = (int)($minutesSomme / 60);
        $minutes = $minutesSomme - ($heures * 60);

        $coupure = $heures . ":" . $minutes . ":00";

        $errorDataBase &= $bdd->exec("UPDATE Horaire SET Coupure = '$coupure' WHERE Datage = '" . date('Y-m-d') . "' AND IdUser = '" . $_SESSION['id'] . "'");

        if ($errorDataBase) {
            $bdd->exec("DELETE FROM Pause WHERE IdHoraire = " . $donnees['Id']);
        }
    }

    if (!$errorDataBase)
        echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
    else
        header("location: updateJournee.php");
}

if (isset($_GET['dodoCamion'])) {
    $errorDataBase = $bdd->exec("UPDATE Horaire set Decouchage = 1 WHERE Datage = '" . date('Y-m-d') . "' AND IdUser = '" . $_SESSION['id'] . "'");

    if (!$errorDataBase)
        echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
    else
        header("location: updateJournee.php");
}

if (isset($_GET['pasDodoCamion'])) {
    $errorDataBase = $bdd->exec("UPDATE Horaire SET Decouchage = 0 WHERE Datage = '" . date('Y-m-d') . "' AND IdUser = '" . $_SESSION['id'] . "'");

    if (!$errorDataBase)
        echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
    else
        header("location: updateJournee.php");
}

if (isset($_GET['modifieJournee'])) {
    $donnees = $bdd->query("SELECT * FROM Horaire WHERE Id = '" . $_GET['modifieJournee'] . "'")->fetch();

    $errorDataBase = true;

    if (date('H', strtotime($_POST['HD'])) * 60 + date('i', strtotime($_POST['HD'])) < date('H', strtotime($_POST['HF'])) * 60 + date('i', strtotime($_POST['HF']))) {
        $tempsTravaille = (date('H', strtotime($_POST['HF'])) * 60 + date('i', strtotime($_POST['HF']))) - (date('H', strtotime($_POST['HD'])) * 60 + date('i', strtotime($_POST['HD'])));

        if ($tempsTravaille > 0) {
            if ($donnees['HDebut'] != date('H:i:s', strtotime($_POST['HD'])))
                $errorDataBase &= $bdd->exec("UPDATE Horaire SET HDebut = '" . date('H:i:s', strtotime($_POST['HD'])) . "' WHERE Id = '" . $_GET['modifieJournee'] . "'");

            if ($donnees['HFin'] != date('H:i:s', strtotime($_POST['HF'])) or $donnees['HFin'] == null)
                $errorDataBase &= $bdd->exec("UPDATE Horaire SET HFin = '" . date('H:i:s', strtotime($_POST['HF'])) . "' WHERE Id = '" . $_GET['modifieJournee'] . "'");
        }

        if ($bdd->query("SELECT * FROM User WHERE Id = '" . $_SESSION['id'] . "' AND Santos = 0")->fetch()) {
            if ($donnees['Coupure'] != date('H:i:s', strtotime($_POST['coupure']) and (date('H', strtotime($_POST['coupure'])) * 60 + date('i', strtotime($_POST['coupure'])))) > $tempsTravaille) {
                $errorDataBase &= $bdd->exec("UPDATE Horaire SET Coupure = '" . date('H:i:s', strtotime($_POST['coupure'])) . "'  WHERE Id = '" . $_GET['modifieJournee'] . "'");
            }
        } else {
            $heureTravailleStr = differenceHeures($_POST['HD'], $_POST['HF']);
            $minutesHeureTravaille = (int)date('H', strtotime($heureTravailleStr)) * 60 + (int)date('i', strtotime($heureTravailleStr));

            if ($minutesHeureTravaille < 390)
                $coupure = '00:00:00';
            elseif ($minutesHeureTravaille < 570)
                $coupure = '00:45:00';
            elseif ($minutesHeureTravaille < 750)
                $coupure = '01:15:00';
            else
                $coupure = '01:30:00';

            if ($coupure != date('H:i:s', strtotime($donnees['Coupure'])))
                $errorDataBase &= $bdd->exec("UPDATE Horaire SET Coupure = '$coupure' WHERE Id = '" . $_GET['modifieJournee'] . "'");
        }

        $decouchage = 0;
        if ($_POST['decouche'] == 'oui')
            $decouchage = 1;

        if ($donnees['Decouchage'] != $decouchage)
            $errorDataBase &= $bdd->exec("UPDATE Horaire SET Decouchage = '$decouchage' WHERE Id = '" . $_GET['modifieJournee'] . "'");

        if (!$errorDataBase)
            echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
        else
            header("location: modifieJournee.php?mois=" . date('m', strtotime($donnees['Datage'])) . "&annee=" . date('Y', strtotime($donnees['Datage'])));
    } else {
        header("location: modifieJournee.php?error=true&id=" . $_GET['modifieJournee']);
    }
}

if (isset($_GET['supprimer'])) {
    $donnees = $bdd->query("SELECT Datage FROM Horaire WHERE Id = '" . $_GET['IdHoraire'] . "'")->fetch();
    $errorDataBase = $bdd->exec("DELETE FROM Horaire WHERE Id = '" . $_GET['IdHoraire'] . "'");

    if (!$errorDataBase)
        echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
    else
        header("location: modifieJournee.php?mois=" . date('m', strtotime($donnees['Datage'])) . "&annee=" . date('Y', strtotime($donnees['Datage'])));
}