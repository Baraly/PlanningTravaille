<?php
function mois($numMois) {
    switch ($numMois) {
        case "01":
            $mois = "Janvier";
            break;
        case "02":
            $mois = "Février";
            break;
        case "03":
            $mois = "Mars";
            break;
        case "04":
            $mois = "Avril";
            break;
        case "05":
            $mois = "Mai";
            break;
        case "06":
            $mois = "Juin";
            break;
        case "07":
            $mois = "Juillet";
            break;
        case "08":
            $mois = "Août";
            break;
        case "09":
            $mois = "Septembre";
            break;
        case "10":
            $mois = "Octobre";
            break;
        case "11":
            $mois = "Novembre";
            break;
        case "12":
            $mois = "Décembre";
            break;
        default:
            return false;
    }
    return $mois;
}
?>
