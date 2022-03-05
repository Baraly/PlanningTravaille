<?php

function differenceHeures($heure1, $heure2){
    $debut = new \DateTime("$heure1");
    $fin = new \DateTime("$heure2");
    $heureTravaille = $debut->diff($fin);

    return $heureTravaille->format('%H:%i:%s');
}

?>