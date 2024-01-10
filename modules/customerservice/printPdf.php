<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
	require_once '../../dompdf/autoload.inc.php';
	// reference the Dompdf namespace
	use Dompdf\Dompdf;

        // $_REQUEST['from'] = $argv[1];
        // $_REQUEST['to'] = $argv[2];
        // $_REQUEST['summary'] = $argv[3];
        // $_REQUEST['detail'] = $argv[4];
        // $_REQUEST['refdmid'] = $argv[5];
        // $_REQUEST['casestatuscode'] = $argv[6];


        include('pdftemplate.php');

        

		//instantiate and use the dompdf class
                $dompdf = new Dompdf();

                $dompdf->loadHtml($html);

                //(Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'landscape');

                //Render the HTML as PDF
                $dompdf->render();

                $dompdf->stream("patientstatusreport.pdf",array("Attachment" => true));
                // $pdf_gen = $dompdf->output();

                // if(!file_put_contents('patientstatusreport.pdf', $pdf_gen)){
                //     echo 'Not OK!';
                // }else{
                //     echo 'OK';
                // }
	

?>