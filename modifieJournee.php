<?php session_start();

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
        a:hover{
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

    $SantosUser = (boolean)$bdd->query("SELECT * FROM User WHERE Id = '".$_SESSION['id']."' AND Santos = 1")->fetch();

?>

<h1 style="font-size: 60px">Modification des données</h1>

<h1 style="font-size: 60px; margin-top: 120px; text-decoration: underline">Modifier une journée</h1>

<?php

    if(isset($_GET['id'])){
        $donnees = $bdd->query("SELECT * FROM Horaire WHERE Id = '".$_GET['id']."'")->fetch();
        ?>
        <form <?= "action='updateJourneePost.php?modifieJournee=".$_GET['id']."'" ?> method="POST" style="margin-top: 100px">
            <label style="font-size: 60px"><span style="margin-left: 170px">Date</span> : <input style="font-size: 40px" type="date" name="date" <?= "value='".date('Y-m-d' ,strtotime($donnees['Datage']))."'" ?> disabled></label><br>
            <label style="font-size: 60px">Heure de début : <input style="font-size: 40px; margin-right: 230px" type="time" name="HD" <?= "value='".$donnees['HDebut']."'" ?> required></label><br>
            <label style="font-size: 60px">Heure de fin : <input style="font-size: 40px; margin-right: 166px" type="time" name="HF" <?= "value='".$donnees['HFin']."'" ?> required></label><br>
            <?php
            if(!$SantosUser){
                ?>
                <label style="font-size: 60px; margin-left: 10px">Pause : <input style="font-size: 40px; margin-right: 20px" type="time" name="coupure" <?= "value='".$donnees['Coupure']."'" ?> required></label><br>
                <?php
            }
            ?>
            <label style="font-size: 60px; margin-left: 60px">Découche :
                <?php
                if($donnees['Decouchage'] == 1){
                    echo "<label style='font-size: 48px' for='oui'>Oui </label> <input style='width: 40px; height: 40px' name='decouche' type='radio' id='oui' value='oui' checked>";
                    echo "<label style='font-size: 48px; margin-left: 30px' for='non'>Non </label> <input style='width: 40px; height: 40px' name='decouche' type='radio' id='non' value='non'>";
                }
                else{
                    echo "<label style='font-size: 48px' for='oui'>Oui </label> <input style='width: 40px; height: 40px' name='decouche' type='radio' id='oui' value='oui'>";
                    echo "<label style='font-size: 48px; margin-left: 30px' for='non'>Non </label> <input style='width: 40px; height: 40px' name='decouche' type='radio' id='non' value='non' checked>";
                }
                ?>
            </label>
            <input type="submit" value="Modifier la journée" style="font-size: 50px; margin-top: 60px">
        </form>
        <?php
            if(isset($_GET['supprimer'])){
                ?>
                <div style="margin-top: 140px">
                    <a class="button" style="background-color: #F85050" <?= "href='updateJourneePost.php?supprimer=true&IdHoraire=".$donnees['Id']."'" ?>>Êtes-vous sûr ?</a>
                </div>
                <?php
            }
            else{
                ?>
                <div style="margin-top: 140px">
                    <a class="button" style="background-color: #F85050" <?= "href='modifieJournee.php?id=".$donnees['Id']."&supprimer'" ?>>Supprimer la journée</a>
                </div>
                <?php
            }
        ?>
        <div style="margin-top: 140px">
            <a class="button" <?= "href='modifieJournee.php?mois=".date('m', strtotime($donnees['Datage']))."&annee=".date('Y', strtotime($donnees['Datage']))."'" ?>>Retour</a>
        </div>
        <?php
    }
    else{

?>

<form action="modifieJournee.php" method="GET" id="myform">
    <label style="font-size: 60px; margin-top: 30px;">Mois :
        <select style="font-size: 50px" name="mois" oninput="loadForm()">
            <?php
            for($i = 1; $i < 13; $i++){
                if($i < 10){
                    if($_GET['mois'] == ("0".$i))
                        echo "<option value='".("0".$i)."' selected>".mois("0".$i)."</option>";
                    else
                        echo "<option value='".("0".$i)."'>".mois("0".$i)."</option>";
                }
                else{
                    if($_GET['mois'] == $i)
                        echo "<option value='".$i."' selected>".mois($i)."</option>";
                    else
                        echo "<option value='".$i."'>".mois($i)."</option>";
                }
            }
            ?>
        </select>
    </label>
    <label style="font-size: 60px; margin-top: 30px; margin-left: 30px">Année :
        <select style="font-size: 50px" name="annee" oninput="loadForm()">
            <?php
            $request = $bdd->query('SELECT DISTINCT YEAR(Datage) AS Annee FROM Horaire WHERE IdUser = "'.$_SESSION['id'].'"');
            $rien = true;
            while($donnees = $request->fetch()){
                $rien = false;
                if($_GET['annee'] == $donnees['Annee'])
                    echo "<option value='".$donnees['Annee']."' selected>".$donnees['Annee']."</option>";
                else
                    echo "<option value='".$donnees['Annee']."'>".$donnees['Annee']."</option>";
            }
            if($rien)
                echo "<option value='".date('Y')."'>".date('Y')."</option>";
            ?>
        </select>
    </label>
</form>

<div style="border: 1px solid black; margin: 0 auto; margin-top: 80px;height: 900px; width: 90%; border-radius: 20px; overflow: auto;">

    <?php
        $request = $bdd->query("SELECT * FROM Horaire WHERE IdUSer = '".$_SESSION['id']."' AND MONTH(Datage) = '".$_GET['mois']."' AND YEAR(datage) = '".$_GET['annee']."'");
        $none = true;
        while($donnees = $request->fetch()){
            $none = false;
            ?>
            <a <?= "href='modifieJournee.php?id=".$donnees['Id']."'" ?> style="color: black">
            <div class="row" style="border: 1px solid black; width: 96%; margin: 30px auto; border-radius: 20px; background-color: #666666; color: white; padding: 10px 0">
                <div class="col-sm-6"><h1 style="font-size: 50px"><?= date('d/m/Y', strtotime($donnees['Datage'])) ?></h1></div>
                <div class="col-sm-6"><h1 style="font-size: 50px"><?= date('H:i', strtotime($donnees['HDebut'])) ?> - <?= date('H:i', strtotime($donnees['HFin'])) ?></h1></div>
            </div>
            </a>
            <?php
        }

        if($none){
            ?>
            <h1 style="font-size: 60px; font-style: italic; width: auto; margin: 300px auto">Aucune donnée pour cette période</h1>
            <?php
        }
    ?>

</div>
        <div style="margin-top: 60px">
            <a class="button" href="updateJournee.php">Retour</a>
        </div>
    <?php } ?>


<script type="text/javascript">
    function loadForm(){
        document.forms["myform"].submit();
    }
</script>
</body>
</html>
<?php } ?>