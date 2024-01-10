<?php
/**
* @package    applications_filebrowser
* @subpackage examples
*/


require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");

//check some session (login) if you want to.

$settings = array(
	'basePath'             => $_SERVER['DOCUMENT_ROOT'], 
	'fileExtensionsFilter' => array('jpg'=>TRUE, 'jpeg'=>TRUE, 'gif'=>TRUE, 'png'=>TRUE), 
	'ignoreDirs'           => array('CVS'=>TRUE), 
);

//now include the correct file.
if (@$_REQUEST['page'] == 'inner') {
  include $APP['path']['applications'] . 'filebrowser/fileBrowserInner.php';
} else {
  include $APP['path']['applications'] . 'filebrowser/fileBrowser.php';
}
?>