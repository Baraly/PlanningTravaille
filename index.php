<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF8" />
    <title>Connexion planning</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <style>
        body{
            margin-top: 200px;
            text-align: center;
        }
        form{
            margin-top: 600px;
        }
        input{
            height: 110px;
            width: 110px;
            margin: 0 16px;
            border: none;
            border-bottom: 1px solid black;
            font-size: 100px;
            text-align: center;
            outline-width: 0;
            box-shadow: none;
            padding: 0 0;
            border-radius: 0;
        }
    </style>
</head>
<body>

<?php

$bdd = null;
include_once 'function/bdd.php';

    if((!empty($_POST['n1']) or (isset($_POST['n1']) and $_POST['n1'] == "0")) and (!empty($_POST['n2']) or (isset($_POST['n2']) and $_POST['n2'] == "0")) and (!empty($_POST['n3']) or (isset($_POST['n3']) and $_POST['n3'] == "0")) and (!empty($_POST['n4']) or (isset($_POST['n4']) and $_POST['n4'] == "0")) and (!empty($_POST['n5']) or (isset($_POST['n5']) and $_POST['n5'] == "0")) and (!empty($_POST['n6']) or (isset($_POST['n6']) and $_POST['n6'] == "0"))){
        $code = $_POST['n1'].$_POST['n2'].$_POST['n3'].$_POST['n4'].$_POST['n5'].$_POST['n6'];
        if($donnees = $bdd->query("SELECT * FROM User WHERE Code = '$code'")->fetch()){
            $_SESSION['id'] = $donnees['Id'];
            $_SESSION['prenom'] = $donnees['Prenom'];
            header("location: planning.php");
        }
    }

?>

    <h1 style="font-size: 60px">Authentification au planning</h1>
    <form id="myform" action="index.php" method="post">
        <input name="n1" id="n1" type="tel" required min="0" max="9" maxlength="1" placeholder="0"/>
        <input name="n2" id="n2" type="tel" required min="0" max="9" maxlength="1" placeholder="0" onkeydown="myFunction(event, 'n1')" onclick="precedent(2)"/>
        <input name="n3" id="n3" type="tel" required min="0" max="9" maxlength="1" placeholder="0" onkeydown="myFunction(event, 'n2')" onclick="precedent(3)"/>
        <input name="n4" id="n4" type="tel" required min="0" max="9" maxlength="1" placeholder="0" onkeydown="myFunction(event, 'n3')" onclick="precedent(4)"/>
        <input name="n5" id="n5" type="tel" required min="0" max="9" maxlength="1" placeholder="0" onkeydown="myFunction(event, 'n4')" onclick="precedent(5)"/>
        <input name="n6" id="n6" type="tel" required min="0" max="9" maxlength="1" placeholder="0" onkeydown="myFunction(event, 'n5')" onclick="precedent(6)" oninput="loadForm()"/>
    </form>

<script type="text/javascript">
    function myFunction(event, number){
        if(event.which === 8){
            let e = document.getElementById(number);
            setTimeout(function() {
                e.focus();
            },0);
        }
    }

    function precedent(number){
        let e = document.getElementById('n' + number);
        if(number > 1 && e.value === '' && document.getElementById('n' + (number - 1)).value === '')
            precedent(number - 1);
        else{
            setTimeout(function() {
                e.focus();
            },0);
        }
    }

    $("input").bind("input", function() {
        var $this = $(this);
        setTimeout(function() {
            if ( $this.val().length >= parseInt($this.attr("maxlength"),10) )
                $this.next("input").focus();
        },0);
    });

    function loadForm(){
        document.forms["myform"].submit();
    }
</script>
</body>
</html>