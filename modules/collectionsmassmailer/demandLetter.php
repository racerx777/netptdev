<?php

require_once '../../classes/tcpdf/tcpdf.php';

header('Content-type: application/pdf');

$page1 = render('demandLetter.template.php');

$pdf = new TCPDF();
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->writeHTML($page1);

$filename = "test.pdf";
echo $pdf->Output($filename, 'S');

function render($template){
   ob_start();
   include($template);
   $ret = ob_get_contents();
   ob_end_clean();
   return $ret;
}

?>
