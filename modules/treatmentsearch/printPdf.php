<?php
require_once '../../dompdf/autoload.inc.php';
// reference the Dompdf namespace
use Dompdf\Dompdf;

include('pdftemplate.php');

if (isset($_GET['printpdf'])) {

    //instantiate and use the dompdf class
    $dompdf = new Dompdf();

    $dompdf->loadHtml($html);

    //(Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');

    //Render the HTML as PDF
    $dompdf->render();

    $dompdf->stream("attorneysreport.pdf",array("Attachment" => true));

    // $dompdf->stream("ProgressReports.pdf");
    // $dompdf->output();
    // header('Pragma:');
    // header('Cache-Control: private,no-cache');
    // return $dompdf->stream("Structure Report - ProgressReports.pdf", array('Attachment' => 1));


    
}

?>