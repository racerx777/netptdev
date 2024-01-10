<?php


	/*require_once '../../../../dompdf/autoload.inc.php';
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

        $dompdf->stream("touchedreport.pdf",array("Attachment" => true));*/

        require_once  '../../../../mpdf-development/vendor/autoload.php';
        include('pdftemplate.php');

        
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->AddPage('L');
        $mpdf->WriteHTML($html);
        $mpdf->Output("touchedreport.pdf",'D');

?>