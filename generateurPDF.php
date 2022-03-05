<?php

use Dompdf\Dompdf;

session_start();
if(!isset($_SESSION['id']))
    header("location: index.php");
else{

    include_once 'function/fonctionMois.php';

$mois = $_GET['mois'];
$annee = $_GET['annee'];

ob_start();

$url = 'contenuPdf.php';

require_once $url;

$html = ob_get_contents();

ob_end_clean();

include_once 'dompdf/autoload.inc.php';

$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portait');

$dompdf->render();

$nomFichier = "Relevé_Heures_".mois($mois)."_".$annee.".pdf";

$dompdf->stream($nomFichier);

}
?>