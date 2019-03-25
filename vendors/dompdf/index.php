<?php
include_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$dompdf->loadHtml("<h1>Hello Word</h1>");

$dompdf->setPaper('A4','landspace');

$dompdf->render();

$dompdf->stream("codexworld",array("Attachment" => 0));
?>