<?php
session_start();

if (!isset($_SESSION['id']))
    header("location: index.php");
else {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF8"/>
        <title>Gestion Horaire</title>
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <script src="function/timeout.js"></script>
        <style>
            body {
                margin-top: 4%;
                text-align: center;
            }

            table {
                font-size: 175%;
                border-collapse: collapse;
                text-align: center;
                margin: 0 2%;
            }

            td {
                border: 1px solid black;
                padding: 0 8px;
            }

            a {
                text-decoration: none;
            }

            a.button {
                font-size: 375%;
                border: 1px solid gray;
                border-radius: 30px;
                background-color: #464646;
                color: white;
                padding: 2% 2%;
            }
        </style>
    </head>
    <body>
    <h1 style="font-size: 375%">Gestion des horaires</h1>

    <?php
    include_once 'function/fonctionHeures.php';

    $bdd = null;
    include_once 'function/bdd.php';

    $nbJoursBDD = $bdd->query("SELECT COUNT(*) AS nb FROM Horaire WHERE MONTH(Datage) = '" . date('m') . "' AND YEAR(Datage) = '" . date('Y') . "' AND IdUser = '" . $_SESSION['id'] . "'")->fetch();

    $nbJour = 23;

    if ((int)$nbJoursBDD['nb'] > $nbJour) {
        $nbJour = (int)$nbJoursBDD['nb'];
    }

    $request = $bdd->query("SELECT * FROM Horaire WHERE MONTH(Datage) = '" . date('m') . "' AND YEAR(Datage) = '" . date('Y') . "' AND IdUser = '" . $_SESSION['id'] . "' ORDER BY Datage");
    $nbHeureTotale = 0;
    $nbDecouchage = 0;
    ?>

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
        for ($i = 1; $i <= $nbJour; $i++) {
            ?>
            <tr>
                <td><?= $i ?></td>
                <?php
                if ($donnees = $request->fetch()) {
                    ?>
                    <td><?= date('d/m/Y', strtotime($donnees['Datage'])) ?></td>
                    <td><?= date('H:i', strtotime($donnees['HDebut'])) ?></td>
                    <td><?php
                        if ($donnees['HFin'] == null)
                            echo "";
                        else
                            echo date('H:i', strtotime($donnees['HFin']));
                        ?>
                    </td>
                    <td><?php
                        if ((int)date('H', strtotime($donnees['Coupure'])) > 0)
                            echo date('H', strtotime($donnees['Coupure'])) . "h" . date('i', strtotime($donnees['Coupure']));
                        elseif ((int)date('i', strtotime($donnees['Coupure'])) > 0)
                            echo date('i', strtotime($donnees['Coupure'])) . 'min';
                        ?>
                    </td>
                    <?php
                    if ($donnees['HFin'] == null)
                        echo "<td>En cours</td>";
                    else {
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
                    <?php
                } else {
                    for ($j = 0; $j < 6; $j++) {
                        echo "<td></td>";
                    }
                }
                ?>
            </tr>
        <?php }
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
    <div style="margin-top: 14%">
        <a class="button" style="position: relative; padding-right: 10%" href="updateJournee.php">Menu <i
                    style="font-size: 120%; position: absolute; right: 5%" class='bx bxs-truck'></i></a>
    </div>
    <div style="margin-top: 8%">
        <a class="button" href="historique.php">Historique des données</a>
    </div>
    </body>
    </html>

<?php } ?>