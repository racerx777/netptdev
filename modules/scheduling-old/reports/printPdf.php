<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
        
 //        require_once '../../../dompdf/autoload.inc.php';
        // // reference the Dompdf namespace
        // use Dompdf\Dompdf;

         //include('pdftemplate.php');

        // //instantiate and use the dompdf class
 //        $dompdf = new Dompdf();

 //        $dompdf->loadHtml($html);

 //        //(Optional) Setup the paper size and orientation
 //        $dompdf->setPaper('A4', 'landscape');

 //        //Render the HTML as PDF
 //        $dompdf->render();

 //        $dompdf->stream("schedulingperformancereport.pdf",array("Attachment" => true));
 //        
        require_once  '../../../mpdf-development/vendor/autoload.php';
        include('pdftemplate.php');

        ini_set('max_execution_time', '300');
        ini_set("pcre.backtrack_limit", "5000000");

        
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->AddPage('L');
        $mpdf->WriteHTML($html);
        $mpdf->Output("schedulingperformancereport.pdf",'D');

?>