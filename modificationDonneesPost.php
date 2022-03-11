<?php

session_start();

$bdd = null;
include_once 'function/bdd.php';
include_once 'function/fonctionHeures.php';

    $estSantos = $bdd->query("SELECT * FROM User WHERE Id = '".$_SESSION['id']."' AND Santos = 1")->fetch();
    // Vaut vrai si c'est Stéphane

    if(isset($_GET['ajout'])){
        if(!$bdd->query("SELECT * FROM Horaire WHERE DAY(Datage) = '".date('d', strtotime($_POST['date']))."' AND MONTH(Datage) = '".date('m', strtotime($_POST['date']))."' AND YEAR(Datage) = '".date('Y', strtotime($_POST['date']))."' AND IdUSer = ".$_SESSION['id'])->fetch()){

            if(strtotime($_POST['HD']) < strtotime($_POST['HF'])){

                $heureTravailleStr = differenceHeures($_POST['HD'], $_POST['HF']);

                if(!$estSantos){
                    $coupure = $_POST['coupure'];
                    if(strtotime($heureTravailleStr) > strtotime($coupure)){
                        switch($_POST['decouche']){
                            case "oui": $decouche = 1; break;
                            default: $decouche = 0; break;
                        }
                    }
                    else
                        header("location: updateJournee.php?modifie=ajouterJournee&errorCoupure=true&date=".$_POST['date']."&HD=".$_POST['HD']."&HF=".$_POST['HF']."&coupure=".$_POST['coupure']."&decouche=".$_POST['decouche']);
                }
                else{
                    switch($_POST['decouche']){
                        case "oui": $decouche = 1; break;
                        default: $decouche = 0; break;
                    }

                    $minutesHeureTravaille = (int)date('H', strtotime($heureTravailleStr)) * 60 + (int)date('i', strtotime($heureTravailleStr));

                    if($minutesHeureTravaille < 390)
                        $coupure = '00:00:00';
                    elseif($minutesHeureTravaille < 570)
                        $coupure = '00:45:00';
                    elseif($minutesHeureTravaille < 750)
                        $coupure = '01:15:00';
                    else
                        $coupure = '01:30:00';

                }

                // Est commun au deux

                $insert = $bdd->prepare("INSERT INTO Horaire(IdUser, Datage, HDebut, HFin, Coupure, Decouchage) VALUES(:idUser, :datage, :debut, :fin, :coupure, :decouchage)");
                $errorDataBase = $insert->execute(array(
                    'idUser' => $_SESSION['id'],
                    'datage' => date("Y-m-d", strtotime($_POST['date'])),
                    'debut' => date("H:i:s", strtotime($_POST['HD'])),
                    'fin' => date("H:i:s", strtotime($_POST['HF'])),
                    'coupure' => $coupure,
                    'decouchage' => $decouche
                ));

                if(!$errorDataBase)
                    echo "<h1 style='font-size: 60px; margin-top: 200px; text-align: center'>ERROR DataBase</h1>";
                else
                    header("location: planning.php");
            }
            else
                header("location: updateJournee.php?modifie=ajouterJournee&errorHeure=true&date=".$_POST['date']."&HD=".$_POST['HD']."&HF=".$_POST['HF']."&coupure=".$_POST['coupure']."&decouche=".$_POST['decouche']);
        }
        else{
            header("location: updateJournee.php?modifie=ajouterJournee&errorAjout=true&date=".$_POST['date']."&HD=".$_POST['HD']."&HF=".$_POST['HF']."&coupure=".$_POST['coupure']."&decouche=".$_POST['decouche']);
        }
    }

?>
