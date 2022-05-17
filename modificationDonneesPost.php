<?php

session_start();

$bdd = null;
include_once 'function/bdd.php';
include_once 'function/fonctionHeures.php';

$estSantos = $bdd->query("SELECT * FROM User WHERE Id = '" . $_SESSION['id'] . "' AND Santos = 1")->fetch();

if (isset($_GET['ajout'])) {
    if (!$bdd->query("SELECT * FROM Horaire WHERE DAY(Datage) = '" . date('d', strtotime($_POST['date'])) . "' AND MONTH(Datage) = '" . date('m', strtotime($_POST['date'])) . "' AND YEAR(Datage) = '" . date('Y', strtotime($_POST['date'])) . "' AND IdUSer = " . $_SESSION['id'])->fetch()) {

        if (strtotime($_POST['HD']) < strtotime($_POST['HF'])) {

            $heureTravailleStr = differenceHeures($_POST['HD'], $_POST['HF']);

            if (!$estSantos) {
                $coupure = $_POST['coupure'];
                if (strtotime($heureTravailleStr) < strtotime($coupure))
                    header("location: updateJournee.php?modifie=ajouterJournee&errorCoupure=true&date=" . $_POST['date'] . "&HD=" . $_POST['HD'] . "&HF=" . $_POST['HF'] . "&coupure=" . $_POST['coupure'] . "&decouche=" . $_POST['decouche']);
            } else {
                $minutesHeureTravaille = (int)date('H', strtotime($heureTravailleStr)) * 60 + (int)date('i', strtotime($heureTravailleStr));

                if ($minutesHeureTravaille < 390)
                    $coupure = '00:00:00';
                elseif ($minutesHeureTravaille < 570)
                    $coupure = '00:45:00';
                elseif ($minutesHeureTravaille < 750)
                    $coupure = '01:15:00';
                else
                    $coupure = '01:30:00';
            }

            switch ($_POST['decouche']) {
                case "oui":
                    $decouche = 1;
                    break;
                default:
                    $decouche = 0;
                    break;
            }

            $insert = $bdd->prepare("INSERT INTO Horaire(IdUser, Datage, HDebut, HFin, Coupure, Decouchage) VALUES(:idUser, :datage, :debut, :fin, :coupure, :decouchage)");
            $errorDataBase = $insert->execute(array(
                'idUser' => $_SESSION['id'],
                'datage' => date("Y-m-d", strtotime($_POST['date'])),
                'debut' => date("H:i:s", strtotime($_POST['HD'])),
                'fin' => date("H:i:s", strtotime($_POST['HF'])),
                'coupure' => $coupure,
                'decouchage' => $decouche
            ));

            if (!$errorDataBase)
                echo "<h1 style='font-size: 60px; margin-top: 200px; text-align: center'>ERROR DataBase</h1>";
            else
                header("location: planning.php");
        } else
            header("location: updateJournee.php?modifie=ajouterJournee&errorHeure=true&date=" . $_POST['date'] . "&HD=" . $_POST['HD'] . "&HF=" . $_POST['HF'] . "&coupure=" . $_POST['coupure'] . "&decouche=" . $_POST['decouche']);
    } else
        header("location: updateJournee.php?modifie=ajouterJournee&errorAjout=true&date=" . $_POST['date'] . "&HD=" . $_POST['HD'] . "&HF=" . $_POST['HF'] . "&coupure=" . $_POST['coupure'] . "&decouche=" . $_POST['decouche']);
}

if (isset($_GET['modifieJournee'])) {
    $donnees = $bdd->query("SELECT * FROM Horaire WHERE Datage = '" . $_GET['modifieJournee'] . "' AND IdUser='" . $_SESSION['id'] . "'")->fetch();

    $okDataBase = true;

    $tempsTravaille = (date('H', strtotime($_POST['HF'])) * 60 + date('i', strtotime($_POST['HF']))) - (date('H', strtotime($_POST['HD'])) * 60 + date('i', strtotime($_POST['HD'])));
    if ($tempsTravaille > 0) {

        if ($donnees['HDebut'] != date('H:i:s', strtotime($_POST['HD'])))
            $okDataBase &= $bdd->exec("UPDATE Horaire SET HDebut = '" . date('H:i:s', strtotime($_POST['HD'])) . "' WHERE Datage = '" . $_GET['modifieJournee'] . "' AND IdUser='" . $_SESSION['id'] . "'");

        if ($donnees['HFin'] != date('H:i:s', strtotime($_POST['HF'])) or $donnees['HFin'] == null)
            $okDataBase &= $bdd->exec("UPDATE Horaire SET HFin = '" . date('H:i:s', strtotime($_POST['HF'])) . "' WHERE Datage = '" . $_GET['modifieJournee'] . "' AND IdUser='" . $_SESSION['id'] . "'");


        if ($bdd->query("SELECT * FROM User WHERE Id = '" . $_SESSION['id'] . "' AND Santos = 0")->fetch()) {
            if ($donnees['Coupure'] != date('H:i:s', strtotime($_POST['coupure']) and (date('H', strtotime($_POST['coupure'])) * 60 + date('i', strtotime($_POST['coupure'])))) > $tempsTravaille) {
                $okDataBase &= $bdd->exec("UPDATE Horaire SET Coupure = '" . date('H:i:s', strtotime($_POST['coupure'])) . "'  WHERE Datage = '" . $_GET['modifieJournee'] . "' AND IdUser='" . $_SESSION['id'] . "'");
            }
        } else {
            $coupure = $_POST['coupure'];
            if ($coupure == "auto") {
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
            }

            if ($coupure != date('H:i:s', strtotime($donnees['Coupure'])))
                $okDataBase &= $bdd->exec("UPDATE Horaire SET Coupure = '$coupure' WHERE Datage = '" . $_GET['modifieJournee'] . "' AND IdUser='" . $_SESSION['id'] . "'");
        }

        $decouchage = 0;
        if ($_POST['decouche'] == 'oui')
            $decouchage = 1;

        if ($donnees['Decouchage'] != $decouchage)
            $okDataBase &= $bdd->exec("UPDATE Horaire SET Decouchage = '$decouchage' WHERE Datage = '" . $_GET['modifieJournee'] . "' AND IdUser='" . $_SESSION['id'] . "'");

        if ($okDataBase)
            header("location: modifieJournee.php?mois=" . date('m', strtotime($donnees['Datage'])) . "&annee=" . date('Y', strtotime($donnees['Datage'])));
        else
            echo "<h1 style='font-size: 60px; margin-top: 200px'>ERROR DataBase</h1>";
    } else
        header("location: modifieJournee.php?error=true&id=" . $_GET['modifieJournee']);
}

?>
