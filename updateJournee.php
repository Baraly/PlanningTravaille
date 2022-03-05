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
    <style>
        body{
            margin-top: 40px;
            text-align: center;
        }
        a{
            text-decoration: none;
        }
        a.button{
            font-size: 60px;
            border: 1px solid gray;
            border-radius: 30px;
            background-color: #464646;
            color: white;
            padding: 20px 20px
        }
    </style>
</head>
<body>
    <h1 style="font-size: 60px">Modification des données</h1>

    <?php
    $bdd = null;
    include_once 'function/bdd.php';
    include_once 'function/fonctionHeures.php';
    include_once 'function/fonctionMois.php';


    date_default_timezone_set('Europe/Paris');
    $date = date('H:i');

    $SantosUser = (boolean)$bdd->query("SELECT * FROM User WHERE Id = '".$_SESSION['id']."' AND Santos = 1")->fetch();

        if($donnees = $bdd->query("SELECT Id, HDebut FROM Horaire WHERE Datage = '".date('Y-m-d')."' AND IdUser = '".$_SESSION['id']."' AND HFin IS NULL")->fetch()){
            ?>
            <h1 style="font-size: 60px; margin-top: 200px">La journée a commencé à <span style="font-weight: bold"><?= date("H:i", strtotime($donnees['HDebut'])) ?></span></h1>
            <?php
            if($pause = $bdd->query("SELECT * FROM Pause WHERE IdHoraire = ".$donnees['Id']." AND HFin IS NULL")->fetch()){
                ?>
                <h1 style="font-size: 50px; margin-top: 80px; font-style: italic">Vous êtes en pause depuis <?= date('H:i', strtotime($pause['HDebut'])) ?></h1>
                <div style="margin-top: 200px">
                    <a class="button" href="updateJourneePost.php?pauseFin">Quitter ma pause</a>
                </div>
                <?php
            }
            else{
                if(!$SantosUser){
                    ?>
                    <div style="margin-top: 140px">
                        <a class="button" href="updateJourneePost.php?pause">Je me mets en pause</a>
                    </div>
                    <?php
                }
                if(isset($_GET['finitMaybe'])){
                    ?>
                    <div style="margin-top: 140px">
                        <a class="button" href="updateJourneePost.php?finitNow">On a vraiment finit la journée ?!</a>
                    </div>
                    <?php
                }
                else{
                    ?>
                    <div style="margin-top: 140px">
                        <a class="button" href="updateJournee.php?finitMaybe">Je finis ma journée</a>
                    </div>
                    <?php
                }
            }
            ?>
            <div style="margin-top: 400px">
                <a class="button" href="planning.php">Retour à la liste</a>
            </div>
            <?php
        }
        elseif (isset($_GET['modifie'])){
            if(!empty($_GET['modifie']) and $_GET['modifie'] == "ajouterJournee"){
                ?>
                <h1 style="font-size: 60px; margin-top: 120px; text-decoration: underline">Ajouter une journée</h1>
                <?php
                if(isset($_GET['errorAjout'])){
                    echo "<h1 style='font-size: 50px; margin-top: 80px; color: orangered'>Ce jour existe déjà !</h1>";
                }
                elseif(isset($_GET['errorHeure'])){
                    echo "<h1 style='font-size: 50px; margin-top: 80px; color: orangered'>Veuillez saisir des heures cohérentes !</h1>";
                }
                elseif(isset($_GET['errorCoupure'])){
                    echo "<h1 style='font-size: 50px; margin-top: 80px; color: orangered'>Veuillez saisir une coupure cohérente !</h1>";
                }
                else{
                    echo "<h1 style='font-size: 50px; margin-top: 80px; color: white'>TEXTE</h1>";
                }

                if(isset($_GET['errorAjout']) or isset($_GET['errorHeure']) or isset($_GET['errorCoupure'])){
                    ?>
                    <form action="modificationDonneesPost.php?ajout" method="POST" style="margin-top: 50px">
                        <label style="font-size: 60px"><span style="margin-left: 170px">Date</span> : <input style="font-size: 40px" type="date" name="date" <?= "value='".$_GET['date']."'" ?> required></label><br>
                        <label style="font-size: 60px">Heure de début : <input style="font-size: 40px; margin-right: 230px" type="time" name="HD" <?= "value='".$_GET['HD']."'" ?> required></label><br>
                        <label style="font-size: 60px">Heure de fin : <input style="font-size: 40px; margin-right: 166px" type="time" name="HF" <?= "value='".$_GET['HF']."'" ?> required></label><br>
                        <?php
                        if(!$SantosUser){
                            ?>
                            <label style="font-size: 60px; margin-left: 10px">Pause : <input style="font-size: 40px; margin-right: 20px" type="time" name="coupure" <?= "value='".$_GET['coupure']."'" ?> required></label><br>
                            <?php
                        }
                        ?>
                        <label style="font-size: 60px; margin-left: 60px">Découche :
                            <?php
                            if($_GET['decouche'] == "oui"){
                                echo "<label style='font-size: 48px' for='oui'>Oui </label> <input style='width: 40px; height: 40px' name='decouche' type='radio' id='oui' value='oui' checked>";
                                echo "<label style='font-size: 48px; margin-left: 30px' for='non'>Non </label> <input style='width: 40px; height: 40px' name='decouche' type='radio' id='non' value='non'>";
                            }
                            else{
                                echo "<label style='font-size: 48px' for='oui'>Oui </label> <input style='width: 40px; height: 40px' name='decouche' type='radio' id='oui' value='oui'>";
                                echo "<label style='font-size: 48px; margin-left: 30px' for='non'>Non </label> <input style='width: 40px; height: 40px' name='decouche' type='radio' id='non' value='non' checked>";
                            }
                            ?>
                        </label>
                        <input type="submit" value="Ajouter la journée" style="font-size: 50px; margin-top: 60px">
                    </form>
                    <?php
                }
                else{
                    ?>
                    <form action="modificationDonneesPost.php?ajout" method="POST" style="margin-top: 100px">
                        <label style="font-size: 60px"><span style="margin-left: 170px">Date</span> : <input style="font-size: 40px" type="date" name="date" required></label><br>
                                <label style="font-size: 60px">Heure de début : <input style="font-size: 40px; margin-right: 230px" type="time" name="HD" required></label><br>
                                <label style="font-size: 60px">Heure de fin : <input style="font-size: 40px; margin-right: 166px" type="time" name="HF" required></label><br>
                                <?php
                                if(!$SantosUser){
                                    ?>
                                    <label style="font-size: 60px; margin-left: 10px">Pause : <input style="font-size: 40px; margin-right: 20px" type="time" name="coupure" required></label><br>
                                    <?php
                                } ?>
                                <label style="font-size: 60px; margin-left: 60px">Découche :
                                    <label style="font-size: 48px">Oui <input style="width: 40px; height: 40px" name="decouche" type="radio" value="oui"></label>
                                    <label style="font-size: 48px; margin-left: 30px">Non <input style="width: 40px; height: 40px" name="decouche" type="radio" value="non" checked></label>
                                </label>
                        <input type="submit" value="Ajouter la journée" style="font-size: 50px; margin-top: 60px">
                    </form>
                    <?php
                }
                ?>
                <div style="margin-top: 200px">
                    <a class="button" href="updateJournee.php?modifie">Retour</a>
                </div>
                <?php
            }
            else{
                ?>
                <h1 style="font-size: 60px; margin-top: 200px">Souhaitez-vous :</h1>
                <div style="margin-top: 200px">
                    <a class="button" href="updateJournee.php?modifie=ajouterJournee">Ajouter une journée</a>
                </div>
                <div style="margin-top: 100px">
                    <a class="button" <?= "href='modifieJournee.php?mois=".date('m')."&annee=".date('Y')."'" ?> href="updateJournee.php?modifie=modifieJournee&mois=&annee=">Modifier une journée</a>
                </div>
                <div style="margin-top: 350px">
                    <a class="button" href="updateJournee.php">Retour</a>
                </div>
                <?php
            }
        }
        elseif ($bdd->query("SELECT * FROM Horaire WHERE Datage = '".date('Y-m-d')."' AND Decouchage = 1 AND IdUser = '".$_SESSION['id']."'")->fetch()){
            ?>
            <h1 style="font-size: 60px; margin-top: 200px">La journée est finie !</h1>
            <h1 style="font-size: 60px; margin-top: -30px">Bonne nuit dans ton camion 🚛</h1>
            <div style="margin-top: 180px">
                <a class="button" href="updateJourneePost.php?pasDodoCamion">Oups je ne suis pas en découchage</a>
            </div>
            <div style="margin-top: 110px">
                <a class="button" href="updateJournee.php?modifie">Modifier une donnée</a>
            </div>
            <div style="margin-top: 300px">
                <a class="button" href="planning.php">Retourner à la liste</a>
            </div>
            <?php
        }
        elseif ($donnees = $bdd->query("SELECT HFin FROM Horaire WHERE Datage = '".date('Y-m-d')."' AND HFin IS NOT NULL AND IdUser = '".$_SESSION['id']."'")->fetch()){

            ?>
            <h1 style="font-size: 60px; margin-top: 200px">La journée s'est finie à <span style="font-weight: bold"><?= date("H:i", strtotime($donnees['HFin'])) ?></span></h1>
            <h1 style="font-size: 60px; margin-top: -20px">Un peu de repos maintenant 😌</h1>
            <div style="margin-top: 180px">
                <a class="button" href="updateJourneePost.php?dodoCamion">Je suis en découchage</a>
            </div>
            <div style="margin-top: 110px">
                <a class="button" href="updateJournee.php?modifie">Modifier une donnée</a>
            </div>
            <div style="margin-top: 300px">
                <a class="button" href="planning.php">Retourner à la liste</a>
            </div>
            <?php
        }
        else{
            ?>
            <h1 style="font-size: 50px; margin-top: 200px">La journée n'a pas encore commencé 🌙</h1>
            <div style="margin-top: 140px">
                <a class="button" href="updateJourneePost.php?ajoutNow">Je commence ma journée</a>
            </div>
            <div style="margin-top: 140px">
                <a class="button" href="updateJournee.php?modifie">Modifier une donnée</a>
            </div>
            <div style="margin-top: 200px">
                <a class="button" href="planning.php">Retourner à la liste</a>
            </div>
            <?php
        }
        ?>
</body>
</html>
<?php } ?>