<?php
// 	ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);
        require_once '../../../dompdf/autoload.inc.php';
	// reference the Dompdf namespace
	use Dompdf\Dompdf;

        include('pdftemplate.php');

	//instantiate and use the dompdf class
        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);

        //(Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        //Render the HTML as PDF
        $dompdf->render();

        $dompdf->stream("treatmentreport.pdf",array("Attachment" => true));

?>