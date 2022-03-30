<?php session_start();

if (!isset($_SESSION['id']))
    header("location: index.php");
else {
    ?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF8"/>
        <title>Modification de la liste</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
              integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
              crossorigin="anonymous">
        <style>
            body {
                margin-top: 4%;
                text-align: center;
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

            a:hover {
                text-decoration: none;
            }
        </style>
    </head>
    <body>

    <?php

    $bdd = null;
    include_once 'function/bdd.php';
    include_once 'function/fonctionHeures.php';
    include_once 'function/fonctionMois.php';

    $SantosUser = (boolean)$bdd->query("SELECT * FROM User WHERE Id = '" . $_SESSION['id'] . "' AND Santos = 1")->fetch();

    ?>

    <h1 style="font-size: 375%">Modification des données</h1>

    <h1 style="font-size: 375%; margin-top: 10%; text-decoration: underline">Modifier une journée</h1>

    <?php

    if (isset($_GET['id'])) {
        if (isset($_GET['error'])) {
            echo "<h1 style='font-size: 312%; margin-top: 8%; color: orangered'>Veuillez saisir des heures cohérentes !</h1>";
        }
        $donnees = $bdd->query("SELECT * FROM Horaire WHERE Id = '" . $_GET['id'] . "' AND IdUser = '" . $_SESSION['id'] . "'")->fetch();
        ?>
        <form <?= "action='updateJourneePost.php?modifieJournee=" . $_GET['id'] . "'" ?> method="POST"
                                                                                         style="margin-top: 10%;">
            <div class="row">
                <div class="col-sm-6" style="text-align: right">
                    <label style="font-size: 375%">Date :</label>
                </div>
                <div class="col-sm-6" style="text-align: left">
                    <input style="font-size: 250%; margin-top: 3%" type="date"
                           name="date" <?= "value='" . date('Y-m-d', strtotime($donnees['Datage'])) . "'" ?> disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" style="text-align: right">
                    <label style="font-size: 375%">Heure de début :</label>
                </div>
                <div class="col-sm-6" style="text-align: left">
                    <input style="font-size: 250%; margin-top: 3%" type="time"
                           name="HD" <?= "value='" . $donnees['HDebut'] . "'" ?> required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" style="text-align: right">
                    <label style="font-size: 375%">Heure de fin :</label>
                </div>
                <div class="col-sm-6" style="text-align: left">
                    <input style="font-size: 250%; margin-top: 3%" type="time"
                           name="HF" <?php if ($donnees['HFin'] != null) echo "value='" . $donnees['HFin'] . "'"; else echo "value='00:00'"; ?>
                           required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" style="text-align: right">
                    <label style="font-size: 375%">Pause :</label>
                </div>
                <?php if ($SantosUser) {
                    $heureTravailleStr = differenceHeures($donnees['HDebut'], $donnees['HFin']);
                    $minutesHeureTravaille = (int)date('H', strtotime($heureTravailleStr)) * 60 + (int)date('i', strtotime($heureTravailleStr));

                    if ($minutesHeureTravaille < 390)
                        $coupure = '00:00:00';
                    elseif ($minutesHeureTravaille < 570)
                        $coupure = '00:45:00';
                    elseif ($minutesHeureTravaille < 750)
                        $coupure = '01:15:00';
                    else
                        $coupure = '01:30:00';
                    ?>
                    <div class="col-sm-6" style="text-align: left">
                        <?php
                        if ($coupure == "00:00:00") {
                        ?>
                        <select id='selecteur' style='font-size: 275%; margin-top: 4%' name='coupure'
                                oninput='loadCoupureAuto("0min")'>
                            <?php
                            } else if ($coupure == "00:45:00") {
                            ?>
                            <select id='selecteur' style='font-size: 275%; margin-top: 4%' name='coupure'
                                    oninput='loadCoupureAuto("45min")'>
                                <?php
                                } else if ($coupure == "01:15:00") {
                                ?>
                                <select id='selecteur' style='font-size: 275%; margin-top: 4%' name='coupure'
                                        oninput='loadCoupureAuto("1h15")'>
                                    <?php
                                    } else {
                                    ?>
                                    <select id='selecteur' style='font-size: 275%; margin-top: 4%' name='coupure'
                                            oninput='loadCoupureAuto("1h30")'>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        $auto = false;
                                        if ($coupure == $donnees['Coupure']) {
                                            echo "<option value='auto' selected>automatique</option>";
                                            $auto = true;
                                        } else {
                                            echo "<option value='auto'>automatique</option>";
                                        }
                                        if ($donnees['Coupure'] == "00:00:00" && !$auto) {
                                            echo "<option value='00:00:00' selected>0min</option>";
                                        } else {
                                            echo "<option value='00:00:00'>0min</option>";
                                        }
                                        if ($donnees['Coupure'] == "00:45:00" && !$auto) {
                                            echo "<option value='00:45:00' selected>45min</option>";
                                        } else {
                                            echo "<option value='00:45:00'>45min</option>";
                                        }
                                        if ($donnees['Coupure'] == "01:15:00" && !$auto) {
                                            echo "<option value='01:15:00' selected>1h15</option>";
                                        } else {
                                            echo "<option value='01:15:00'>1h15</option>";
                                        }
                                        if ($donnees['Coupure'] == "01:30:00" && !$auto) {
                                            echo "<option value='01:30:00' selected>1h30</option>";
                                        } else {
                                            echo "<option value='01:30:00'>1h30</option>";
                                        }
                                        ?>
                                    </select>
                                    <span id="printCoupure"
                                          style="font-size: 275%"></span>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="col-sm-6" style="text-align: left">
                        <input style="font-size: 250%; margin-top: 3%" type="time"
                               name="coupure" <?= "value='" . $donnees['Coupure'] . "'" ?> required>
                    </div>
                    <?php
                } ?>
            </div>
            <div class="row">
                <div class="col-sm-6" style="text-align: right">
                    <label style="font-size: 375%">Découche :</label>
                </div>
                <div class="col-sm-6" style="text-align: left">
                    <?php
                    if ($donnees['Decouchage'] == 1) {
                        echo "<label style='font-size: 300%; margin-top: 3%' for='oui'>Oui</label> <input style='width: 10%; height: 34%; margin-left: 2%' name='decouche' type='radio' id='oui' value='oui' checked>";
                        echo "<label style='font-size: 300%; margin-top: 3%; margin-left: 12%' for='non'>Non</label> <input style='width: 10%; height: 34%; margin-left: 2%' name='decouche' type='radio' id='non' value='non'>";
                    } else {
                        echo "<label style='font-size: 300%; margin-top: 3%' for='oui'>Oui</label> <input style='width: 10%; height: 34%; margin-left: 2%' name='decouche' type='radio' id='oui' value='oui'>";
                        echo "<label style='font-size: 300%; margin-top: 3%; margin-left: 12%' for='non'>Non</label> <input style='width: 10%; height: 34%; margin-left: 2%' name='decouche' type='radio' id='non' value='non' checked>";
                    }
                    ?>
                </div>
            </div>
            <input type="submit" value="Modifier la journée" style="font-size: 312%; margin-top: 8%">
        </form>
        <?php
        if (isset($_GET['supprimer'])) {
            ?>
            <div style="margin-top: 14%">
                <a class="button"
                   style="background-color: #F85050" <?= "href='updateJourneePost.php?supprimer=true&IdHoraire=" . $donnees['Id'] . "'" ?>>Êtes-vous
                    sûr ?</a>
            </div>
            <?php
        } else {
            ?>
            <div style="margin-top: 14%">
                <a class="button"
                   style="background-color: #F85050" <?= "href='modifieJournee.php?id=" . $donnees['Id'] . "&supprimer'" ?>>Supprimer
                    la journée</a>
            </div>
            <?php
        }
        ?>
        <div style="bottom: 14%; left: 0; right: 0; margin-left: auto; margin-right: auto; width: auto;  position: absolute">
            <a class="button" <?= "href='modifieJournee.php?mois=" . date('m', strtotime($donnees['Datage'])) . "&annee=" . date('Y', strtotime($donnees['Datage'])) . "'" ?>>Retour</a>
        </div>
        <?php
    } else {

        ?>

        <form action="modifieJournee.php" method="GET" id="myform">
            <label style="font-size: 375%; margin-top: 5%">Mois :
                <select style="font-size: 84%" name="mois" oninput="loadForm()">
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                        if ($i < 10) {
                            if ($_GET['mois'] == ("0" . $i))
                                echo "<option value='" . ("0" . $i) . "' selected>" . mois("0" . $i) . "</option>";
                            else
                                echo "<option value='" . ("0" . $i) . "'>" . mois("0" . $i) . "</option>";
                        } else {
                            if ($_GET['mois'] == $i)
                                echo "<option value='" . $i . "' selected>" . mois($i) . "</option>";
                            else
                                echo "<option value='" . $i . "'>" . mois($i) . "</option>";
                        }
                    }
                    ?>
                </select>
            </label>
            <label style="font-size: 375%; margin-top: 5%; margin-left: 5%">Année :
                <select style="font-size: 84%" name="annee" oninput="loadForm()">
                    <?php
                    $request = $bdd->query('SELECT DISTINCT YEAR(Datage) AS Annee FROM Horaire WHERE IdUser = "' . $_SESSION['id'] . '"');
                    $rien = true;
                    while ($donnees = $request->fetch()) {
                        $rien = false;
                        if ($_GET['annee'] == $donnees['Annee'])
                            echo "<option value='" . $donnees['Annee'] . "' selected>" . $donnees['Annee'] . "</option>";
                        else
                            echo "<option value='" . $donnees['Annee'] . "'>" . $donnees['Annee'] . "</option>";
                    }
                    if ($rien)
                        echo "<option value='" . date('Y') . "'>" . date('Y') . "</option>";
                    ?>
                </select>
            </label>
        </form>

        <div style="border: 1px solid black; top: 30%; margin: 0 6%; width: 88%; bottom: 24%; overflow: auto; position: absolute; border-radius: 30px">

            <?php
            $request = $bdd->query("SELECT * FROM Horaire WHERE IdUSer = '" . $_SESSION['id'] . "' AND MONTH(Datage) = '" . $_GET['mois'] . "' AND YEAR(datage) = '" . $_GET['annee'] . "' ORDER BY DAY(Datage)");
            $none = true;
            while ($donnees = $request->fetch()) {
                $none = false;
                ?>
                <a <?= "href='modifieJournee.php?id=" . $donnees['Id'] . "'" ?> style="color: black">
                    <div class="row"
                         style="border: 1px solid black; width: 96%; margin: 30px auto; border-radius: 20px; background-color: #666666; color: white; padding: 1% 0">
                        <div class="col-sm-6"><h1
                                    style="font-size: 312%"><?= date('d/m/Y', strtotime($donnees['Datage'])) ?></h1>
                        </div>
                        <div class="col-sm-6"><h1
                                    style="font-size: 312%"><?= date('H:i', strtotime($donnees['HDebut'])) ?>
                                - <?php if ($donnees['HFin'] != null) echo date('H:i', strtotime($donnees['HFin'])); else echo "ERROR"; ?></h1>
                        </div>
                    </div>
                </a>
                <?php
            }

            if ($none) {
                ?>
                <h1 style="font-size: 375%; font-style: italic; width: auto; margin: 40% auto">Aucune donnée pour cette
                    période</h1>
                <?php
            }
            ?>

        </div>
        <div style="bottom: 14%; left: 0; right: 0; margin-left: auto; margin-right: auto; width: auto;  position: absolute">
            <a class="button" href="updateJournee.php?modifie">Retour</a>
        </div>
    <?php } ?>


    <script type="text/javascript">
        function loadForm() {
            document.forms["myform"].submit();
        }

        function loadCoupureAuto(time) {
            if (document.getElementById("selecteur").options[document.getElementById("selecteur").selectedIndex].value === "auto")
                document.getElementById("printCoupure").innerHTML = " ( " + time + " )";
            else
                document.getElementById("printCoupure").innerHTML = "";
        }
    </script>
    </body>
    </html>
<?php } ?>