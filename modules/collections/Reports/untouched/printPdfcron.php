<?php
error_reporting(E_ALL);
ini_set('display_errors',true);
try {
	$output = exec( '/modules/collections/Reports/untouched/printPdf.php?days='.$_REQUEST['days'].'&userid='.$_REQUEST['userid'].'&printnotes='.$_REQUEST['printnotes'].'&mintbal='.$_REQUEST['mintbal'].'&printpdf='.$_REQUEST['printpdf']);
	echo $output;
} catch(EXCEPTION $e) {
	echo  $e->get_messages();
}
