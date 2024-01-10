<?php
/**
* @package     core_text
*/

if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';
require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . '/global.conf.php');
require_once($APP['path']['core'] . 'text/Bs_Ocr.class.php');

$ocr =& new Bs_Ocr;
$ocr->imageMagickPath = '/Program Files/ImageMagick-5.5.7-Q16/'; //available from everywhere in my case. installed in the 'path'.
$ocr->gocrPath        = 'C:/usr/local/lib/util/gocr/'; //update this for your machine.
echo 'Converting ...<br><hr><br>';
$jpgSource = substr($_SERVER['SCRIPT_FILENAME'], 0, -3) . 'jpg';
echo $ocr->fileToString($jpgSource);
echo '<br><hr><br>Done!';
?>