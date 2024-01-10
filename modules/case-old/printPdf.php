<?php
	require_once '../../dompdf/autoload.inc.php';
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

        $dompdf->stream("patientstatusreport.pdf",array("Attachment" => true));

?>