<?php
session_start();

if(!isset($_SESSION['id']))
    header("location: index.php");
else{
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF8" />
    <title>Modification de la liste</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body{
            margin-top: 4%;
            text-align: center;
        }
        a{
            text-decoration: none;
        }
        a.button{
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
    <h1 style="font-size: 375%">Modification des données</h1>

    <?php
    $bdd = null;
    include_once 'function/bdd.php';
    include_once 'function/fonctionHeures.php';
    include_once 'function/fonctionMois.php';


    date_default_timezone_set('Europe/Paris');
    $date = date('H:i');

    $SantosUser = (boolean)$bdd->query("SELECT * FROM User WHERE Id = '".$_SESSION['id']."' AND Santos = 1")->fetch();


        if (isset($_GET['modifie'])){
            if(!empty($_GET['modifie']) and $_GET['modifie'] == "ajouterJournee"){
                ?>
            <h1 style="font-size: 375%; margin-top: 12%; text-decoration: underline">Ajouter une journée</h1>
            <?php
            if(isset($_GET['errorAjout'])){
                echo "<h1 style='font-size: 312%; margin-top: 8%; color: orangered'>Ce jour existe déjà !</h1>";
            }
            elseif(isset($_GET['errorHeure'])){
                echo "<h1 style='font-size: 312%; margin-top: 8%; color: orangered'>Veuillez saisir des heures cohérentes !</h1>";
            }
            elseif(isset($_GET['errorCoupure'])){
                echo "<h1 style='font-size: 312%; margin-top: 8%; color: orangered'>Veuillez saisir une coupure cohérente !</h1>";
            }
            else{
                echo "<h1 style='font-size: 312%; margin-top: 8%; color: white'>TEXTE</h1>";
            }

            if(isset($_GET['errorAjout']) or isset($_GET['errorHeure']) or isset($_GET['errorCoupure'])){
            ?>
            <form action="modificationDonneesPost.php?ajout" method="POST" style="margin-top: 10%;">
                <div class="row">
                    <div class="col-sm-6" style="text-align: right">
                        <label style="font-size: 375%">Date :</label>
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <input style="font-size: 250%; margin-top: 3%" type="date" name="date" <?= "value='".$_GET['date']."'" ?> required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6" style="text-align: right">
                        <label style="font-size: 375%">Heure de début :</label>
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <input style="font-size: 250%; margin-top: 3%" type="time" name="HD" <?= "value='".$_GET['HD']."'" ?> required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6" style="text-align: right">
                        <label style="font-size: 375%">Heure de fin :</label>
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <input style="font-size: 250%; margin-top: 3%" type="time" name="HF" <?= "value='".$_GET['HF']."'" ?> required>
                    </div>
                </div>
                <?php
                if(!$SantosUser){
                    ?>
                    <div class="row">
                        <div class="col-sm-6" style="text-align: right">
                            <label style="font-size: 375%">Pause :</label>
                        </div>
                        <div class="col-sm-6" style="text-align: left">
                            <input style="font-size: 250%; margin-top: 3%" type="time" name="coupure" <?= "value='".$_GET['coupure']."'" ?> required>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="row">
                    <div class="col-sm-6" style="text-align: right">
                        <label style="font-size: 375%">Découche :</label>
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <?php
                        if($_GET['decouche'] == "oui"){
                            echo "<label style='font-size: 300%; margin-top: 3%' for='oui'>Oui </label> <input style='width: 34%; height: 34%; margin-left: 2%' name='decouche' type='radio' id='oui' value='oui' checked>";
                            echo "<label style='font-size: 300%; margin-top: 3%; margin-left: -16%' for='non'>Non </label> <input style='width: 34%; height: 34%; margin-left: 2%' name='decouche' type='radio' id='non' value='non'>";
                        }
                        else{
                            echo "<label style='font-size: 300%; margin-top: 3%' for='oui'>Oui </label> <input style='width: 34%; height: 34%; margin-left: 2%' name='decouche' type='radio' id='oui' value='oui'>";
                            echo "<label style='font-size: 300%; margin-top: 3%; margin-left: -16%' for='non'>Non </label> <input style='width: 34%; height: 34%; margin-left: 2%' name='decouche' type='radio' id='non' value='non' checked>";
                        }
                        ?>
                    </div>
                </div>
                <input type="submit" value="Ajouter la journée" style="font-size: 312%; margin-top: 8%">
            </form>
            <?php
            }
            else{
            ?>
            <form action="modificationDonneesPost.php?ajout" method="POST" style="margin-top: 10%;">
                <div class="row">
                    <div class="col-sm-6" style="text-align: right">
                        <label style="font-size: 375%">Date :</label>
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <input style="font-size: 250%; margin-top: 3%" type="date" name="date" <?= "value='".date("Y-m-d")."'" ?> required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6" style="text-align: right">
                        <label style="font-size: 375%">Heure de début :</label>
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <input style="font-size: 250%; margin-top: 3%" type="time" name="HD" <?= "value='".date("H:i")."'" ?> required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6" style="text-align: right">
                        <label style="font-size: 375%">Heure de fin :</label>
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <input style="font-size: 250%; margin-top: 3%" type="time" name="HF" <?= "value='".date("H:i")."'" ?> required>
                    </div>
                </div>
                <?php
                if(!$SantosUser){
                    ?>
                    <div class="row">
                        <div class="col-sm-6" style="text-align: right">
                            <label style="font-size: 375%">Pause :</label>
                        </div>
                        <div class="col-sm-6" style="text-align: left">
                            <input style="font-size: 250%; margin-top: 3%" type="time" name="coupure" value="01:00:00" required>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="row">
                    <div class="col-sm-6" style="text-align: right">
                        <label style="font-size: 375%">Découche :</label>
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <label style='font-size: 300%; margin-top: 3%' for='oui'>Oui </label> <input style='width: 34%; height: 34%; margin-left: 2%' name='decouche' type='radio' id='oui' value='oui'>";
                        <label style='font-size: 300%; margin-top: 3%; margin-left: -16%' for='non'>Non </label> <input style='width: 34%; height: 34%; margin-left: 2%' name='decouche' type='radio' id='non' value='non' checked>
                    </div>
                </div>
                <input type="submit" value="Ajouter la journée" style="font-size: 312%; margin-top: 10%">
            </form>
            <?php
            }
            ?>
            <div style="bottom: 14%; left: 0; right: 0; margin-left: auto; margin-right: auto; width: auto;  position: absolute">
                <a class="button" href="updateJournee.php?modifie">Retour</a>
            </div>
            <?php
            }
            else{
            ?>
            <h1 style="font-size: 375%; margin-top: 20%">Souhaitez-vous :</h1>
            <div style="margin-top: 20%">
                <a class="button" href="updateJournee.php?modifie=ajouterJournee">Ajouter une journée</a>
            </div>
            <div style="margin-top: 8%">
                <a class="button" <?= "href='modifieJournee.php?mois=".date('m')."&annee=".date('Y')."'" ?> href="updateJournee.php?modifie=modifieJournee&mois=&annee=">Modifier une journée</a>
            </div>
            <div style="bottom: 14%; left: 0; right: 0; margin-left: auto; margin-right: auto; width: auto;  position: absolute">
                <a class="button" href="updateJournee.php">Retour</a>
            </div>
            <?php
            }
        }
        elseif($donnees = $bdd->query("SELECT Id, HDebut FROM Horaire WHERE Datage = '".date('Y-m-d')."' AND IdUser = '".$_SESSION['id']."' AND HFin IS NULL")->fetch()){
            ?>
            <h1 style="font-size: 375%; margin-top: 21%">La journée a commencé à <span style="font-weight: bold"><?= date("H:i", strtotime($donnees['HDebut'])) ?></span></h1>
            <?php
            if($pause = $bdd->query("SELECT * FROM Pause WHERE IdHoraire = ".$donnees['Id']." AND HFin IS NULL")->fetch()){
                ?>
                <h1 style="font-size: 312%; margin-top: 14%; font-style: italic">Vous êtes en pause depuis <?= date('H:i', strtotime($pause['HDebut'])) ?></h1>
                <div style="margin-top: 21%">
                    <a class="button" href="updateJourneePost.php?pauseFin">Quitter ma pause</a>
                </div>
                <?php
            }
            else{
                if(!$SantosUser){
                    ?>
                    <div style="margin-top: 16%">
                        <a class="button" href="updateJourneePost.php?pause">Je me mets en pause</a>
                    </div>
                    <?php
                }
                if(isset($_GET['finitMaybe'])){
                    ?>
                    <div style="margin-top: 15%">
                        <a class="button" href="updateJourneePost.php?finitNow">On a vraiment finit la journée ?!</a>
                    </div>
                    <?php
                }
                else{
                    ?>
                    <div style="margin-top: 15%">
                        <a class="button" href="updateJournee.php?finitMaybe">Je finis ma journée</a>
                    </div>
                    <?php
                }
                ?>
                <div style="margin-top: 8%">
                    <a class="button" href="updateJournee.php?modifie">Ajouter / Modifier une journée</a>
                </div>
                <?php
            }
            ?>
            <div style="bottom: 14%; left: 0; right: 0; margin-left: auto; margin-right: auto; width: auto;  position: absolute">
                <a class="button" href="planning.php">Retourner à la liste</a>
            </div>
            <?php
        }
        elseif ($bdd->query("SELECT * FROM Horaire WHERE Datage = '".date('Y-m-d')."' AND Decouchage = 1 AND IdUser = '".$_SESSION['id']."'")->fetch()){
            ?>
            <h1 style="font-size: 375%; margin-top: 20%">La journée est finie !</h1>
            <h1 style="font-size: 375%; margin-top: 2%">Bonne nuit dans ton camion 🚛</h1>
            <div style="margin-top: 19%">
                <a class="button" href="updateJourneePost.php?pasDodoCamion">Oups je ne suis pas en découchage</a>
            </div>
            <div style="margin-top: 8%">
                <a class="button" href="updateJournee.php?modifie">Ajouter / Modifier une journée</a>
            </div>
            <div style="bottom: 14%; left: 0; right: 0; margin-left: auto; margin-right: auto; width: auto;  position: absolute">
                <a class="button" href="planning.php">Retourner à la liste</a>
            </div>
            <?php
        }
        elseif ($donnees = $bdd->query("SELECT HFin FROM Horaire WHERE Datage = '".date('Y-m-d')."' AND HFin IS NOT NULL AND IdUser = '".$_SESSION['id']."'")->fetch()){

            ?>
            <h1 style="font-size: 375%; margin-top: 20%">La journée s'est finie à <span style="font-weight: bold"><?= date("H:i", strtotime($donnees['HFin'])) ?></span></h1>
            <h1 style="font-size: 375%; margin-top: -20px">Un peu de repos maintenant 😌</h1>
            <div style="margin-top: 20%">
                <a class="button" href="updateJourneePost.php?dodoCamion">Je suis en découchage</a>
            </div>
            <div style="margin-top: 10%">
                <a class="button" href="updateJournee.php?modifie">Ajouter / Modifier une journée</a>
            </div>
            <div style="bottom: 14%; left: 0; right: 0; margin-left: auto; margin-right: auto; width: auto;  position: absolute">
                <a class="button" href="planning.php">Retourner à la liste</a>
            </div>
            <?php
        }
        else{
            ?>
            <h1 style="font-size: 375%; margin-top: 22%">La journée n'a pas encore commencé 🌙</h1>
            <div style="margin-top: 18%">
                <a class="button" href="updateJourneePost.php?ajoutNow">Je commence ma journée</a>
            </div>
            <div style="margin-top: 10%">
                <a class="button" href="updateJournee.php?modifie">Ajouter / Modifier une journée</a>
            </div>
            <div style="bottom: 14%; left: 0; right: 0; margin-left: auto; margin-right: auto; width: auto;  position: absolute">
                <a class="button" href="planning.php">Retourner à la liste</a>
            </div>
            <?php
        }
        ?>
</body>
</html>
<?php } ?>