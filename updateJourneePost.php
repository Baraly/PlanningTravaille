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
    $insert = $bdd->prepare("INSERT INTO Pause(IdUser, HDebut) VALUES(:idU, :HB)");
    $errorDataBase = $insert->execute(array(
        'idU' => $_SESSION['id'],
        'HB' => date('H:i')
    ));
    if (!$errorDataBase)
        echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
    else
        header("location: updateJournee.php");
}

if (isset($_GET['pauseFin'])) {
    $errorDataBase = $bdd->exec("UPDATE Pause SET HFin = '" . date('H:i') . "' WHERE IdUser = " . $_SESSION['id'] . " AND HFin IS NULL");

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
        $request = $bdd->query("SELECT * FROM Pause WHERE IdUser = " . $_SESSION['id']);
        $minutesSomme = 0;
        while ($d = $request->fetch()) {
            $minutesSomme += (date('H', strtotime($d['HFin'])) * 60 + date('i', strtotime($d['HFin']))) - (date('H', strtotime($d['HDebut'])) * 60 + date('i', strtotime($d['HDebut'])));
        }
        $heures = (int)($minutesSomme / 60);
        $minutes = $minutesSomme - ($heures * 60);

        $coupure = $heures . ":" . $minutes . ":00";

        $errorDataBase &= $bdd->exec("UPDATE Horaire SET Coupure = '$coupure' WHERE Datage = '" . date('Y-m-d') . "' AND IdUser = '" . $_SESSION['id'] . "'");

        if ($errorDataBase) {
            $bdd->exec("DELETE FROM Pause WHERE IdUser = " . $_SESSION['id']);
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

if (isset($_GET['supprimer'])) {
    $errorDataBase = $bdd->exec("DELETE FROM Horaire WHERE Datage = '" . $_GET['IdHoraire'] . "' AND IdUser = '".$_SESSION['id']."'");
    if (!$errorDataBase)
        echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
    else
        header("location: modifieJournee.php?mois=" . date('m', strtotime($_GET['IdHoraire'])) . "&annee=" . date('Y', strtotime($_GET['IdHoraire'])));
}