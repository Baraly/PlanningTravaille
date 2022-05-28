<!DOCTYPE html>
<html lang="fr">
<head>
    <style>
        table {
            font-size: 20px;
            border-collapse: collapse;
            text-align: center;
        }

        td {
            border: 1px solid black;
            padding: 0 8px;
        }
    </style>
</head>
<body>
<?php
$bdd = null;
include_once '../function/bdd.php';

include_once '../function/fonctionMois.php';
include_once '../function/fonctionHeures.php';

$mois = $_GET['mois'];
$annee = $_GET['annee'];

$donnees = $bdd->query("SELECT * FROM User WHERE Id = '" . $_SESSION['id'] . "'")->fetch();

?>
<h1>Relevé des heures de travail : <?= mois($mois) ?> <?= $annee ?></h1>
<hr style="width: 90%">
<h2>Employé : <?= $donnees['Genre'] ?> <?= $donnees['Nom'] ?> <?= $donnees['Prenom'] ?></h2>

<?php


$request = $bdd->query("SELECT * FROM Horaire WHERE MONTH(Datage) = '$mois' AND YEAR(Datage) = '$annee' AND IdUser = '" . $_SESSION['id'] . "' ORDER BY Datage");

?>
<div style="width: 100%; text-align: center; margin-top: 50px">
    <table>
        <tr>
            <td></td>
            <td>Date</td>
            <td>Heure de début</td>
            <td>Heure de fin</td>
            <td>Pause</td>
            <td>Heures travaillées</td>
            <td>Découchage</td>
        </tr>
        <?php
        $i = 1;
        $nbHeureTotale = 0;
        $nbDecouchage = 0;

        while ($donnees = $request->fetch()) {
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= date('d/m/Y', strtotime($donnees['Datage'])) ?></td>
                <td><?= date('H:i', strtotime($donnees['HDebut'])) ?></td>
                <?php
                if ($donnees['HFin'] == null)
                    echo "<td> / </td>";
                else
                    echo "<td>" . date('H:i', strtotime($donnees['HFin'])) . "</td>";
                ?>
                <td><?php
                    if ((int)date('H', strtotime($donnees['Coupure'])) > 0)
                        echo date('H', strtotime($donnees['Coupure'])) . "h" . date('i', strtotime($donnees['Coupure']));
                    elseif ((int)date('i', strtotime($donnees['Coupure'])) > 0)
                        echo date('i', strtotime($donnees['Coupure'])) . 'min';
                    else
                        echo " / ";
                    ?>
                </td>
                <?php
                if ($donnees['HFin'] == null) {
                    echo "<td> Non Calculé </td>";
                    $nbHeureTotale += 0;
                } else {
                    $heureTravailleStr = differenceHeures($donnees['HDebut'], $donnees['HFin']);

                    $diffDate = new DateTime("{$heureTravailleStr}");
                    $pause = new DateTime("{$donnees['Coupure']}");

                    $resul = $pause->diff($diffDate);
                    echo "<td>" . $resul->format("%Hh%I") . "</td>";

                    $nbHeureTotale += ((int)$resul->format("%H") * 60 + (int)$resul->format("%I"));
                }
                ?>
                <td><?php if ($donnees['Decouchage']) {
                        echo "+1";
                        $nbDecouchage++;
                    }
                    ?>
                </td>
            </tr>
            <?php
            $i++;
        }
        $heures = (int)($nbHeureTotale / 60);
        $minutes = $nbHeureTotale - $heures * 60;
        if ($heures < 10)
            $strNbHeureTotale = "0" . $heures;
        else
            $strNbHeureTotale = $heures;
        if ($minutes < 10)
            $strNbHeureTotale .= "h0" . $minutes;
        else
            $strNbHeureTotale .= "h" . $minutes;
        ?>
        <tr>
            <td style="color: white">f</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>TOTAL</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?= $strNbHeureTotale ?></td>
            <td><?= $nbDecouchage ?></td>
        </tr>
    </table>
</div>
</body>
</html>