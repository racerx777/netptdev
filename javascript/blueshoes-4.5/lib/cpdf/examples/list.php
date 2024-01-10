<?php
//require_once('../class.pdf.php');
require_once('../class.ezpdf.php');


$smallBulletList = array(
	'element one', 
	'element two', 
	'element three', 
);

$smallBulletProps = array(
	'indentLeftBullets' => 100, 
	'indentTopTextDiff' => 1, 
	'bulletType'        => 'alpha', 
);


$bulletListWithProps = array(
	'values' => array(
		'element one', 
		'element two' => array(
			'values' => array(
				'element a', 
				'element b', 
			), 
			'props' => array(
				'indentTopTextDiff' => 1, 
				'bulletType'        => 'alpha', 
				'bulletProps'       => array(
					'separator'         => '.', 
				), 
			), 
		), 
		'element three', 
	), 
	'props' => array(
		'indentLeftBullets' => 100, 
		'indentTopTextDiff' => 1, 
		'bulletType'        => 'numarab', 
	), 
);


$bulletListLongText = array(
	'here we use long lines for list elements.', 
	'here we use long lines for list elements. some are shorter and some are longer.', 
	'here we use long lines for list elements.', 
	'here we use long lines for list elements. some are shorter and some are longer. some are very long. and some even need more than one line, but thanks to ezText() this is a nap.', 
	'here we use long lines for list elements. some are shorter and some are longer.', 
	'here we use long lines for list elements.', 
	array(
		'this is an element of a sub-array (vector)', 
		'this is an element of a sub-array (vector)', 
	), 
	'key' => array(
		'this is an element of a sub-array (hash, with a key)', 
		'this is an element of a sub-array (hash, with a key)', 
	), 
);



$startLeft = 50;
$startTop  = 50;


$pdf = new Cezpdf();
$fontDir = '../fonts/';
$status = $pdf->selectFont($fontDir . 'Helvetica');
$pdf->setPreferences('FitWindow', 1);
$yNow = $startTop;

$pdf->addText($startLeft, $pdf->invertY($yNow), 20, 'Page 1');
$yNow += 50;

$pdf->addText($startLeft, $pdf->invertY($yNow), 14, 'Printing a simple list:');
$yNow += 20;
$pdf->y = $pdf->invertY($yNow);
$yNow = $pdf->ezList($smallBulletList, $smallBulletProps);
$yNow = $yNow + 50;

$pdf->addText($startLeft, $pdf->invertY($yNow), 14, 'Printing a list with children:');
$yNow += 20;
$pdf->y = $pdf->invertY($yNow);
$yNow = $pdf->ezList($bulletListWithProps);
$yNow = $yNow + 50;

$pdf->addText($startLeft, $pdf->invertY($yNow), 14, 'Printing a list with long text strings:');
$yNow += 20;
$pdf->y = $pdf->invertY($yNow);
$yNow = $pdf->ezList($bulletListLongText);



$pdf->ezNewPage();
$yNow = $startTop;

$pdf->addText($startLeft, $pdf->invertY($yNow), 20, 'Page 2');
$yNow += 50;

$pdf->addText($startLeft, $pdf->invertY($yNow), 14, 'Printing a small customized list using squares:');
$yNow += 20;

$bulletPropsSquare = array(
	'fontSize'          => 14, 
	'fontColor'         => '00FF00', 
	'lineHeight'        => 30, 
	'indentLeftBullets' => 100, 
	'indentTopBullets'  => $yNow, 
	'indentTopTextDiff' => -5, 
	'bulletType'        => 'square', 
	'bulletProps'       => array(
		'color'             => 'FF0000', 
		'width'             => 8, 
		'height'            => 8, 
	), 
);
$yNow = $pdf->ezList($smallBulletList, $bulletPropsSquare);
$yNow = $yNow + 50;

$pdf->addText($startLeft, $pdf->invertY($yNow), 14, 'Printing a small customized list using discs:');
$yNow += 20;

$bulletPropsSquare = array(
	'indentLeftBullets' => 100, 
	'indentTopBullets'  => $yNow, 
	'indentTopTextDiff' => 0, 
	'bulletType'        => 'disc', 
);
$yNow = $pdf->ezList($smallBulletList, $bulletPropsSquare);
$yNow = $yNow + 50;



$pdf->ezStream();



?>