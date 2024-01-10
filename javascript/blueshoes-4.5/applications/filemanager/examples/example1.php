<?php
/**
* @package    applications_filemanager
* @subpackage examples
* 
* 
* to get this example working, do the following:
* 1) extract the blueshoes filemanager package on the destination machine.
*    for this example we have put the blueshoes-4.4 folder into the webroot directly.
*    if you place it somewhere else, configure the 'jsBasePath', 'imgBasePath' and 
*    the include().
*    (in case you already have the full blueshoes package installed, you don't need 
*    that blueshoes filemanager package.)
* 2) copy this example file into your document root, wherever you please.
* 3) comment out the die() line that comes next.
* 4) configure the fileManagerSettings below.
* 4) call the file from your web browser.
* 
* for further information read the readme.txt
*/

die('This example is disabled by default because of security reasons. Switch it on yourself. File: ' . __FILE__ . ' Line: ' . __LINE__);

$fileManagerSettings = array(
  'basePath'                => $_SERVER['DOCUMENT_ROOT'], //your document root will be browsable. name any path you like.
  'showRelative'            => TRUE, 
  'maxFileUploadSize'       => '1000000', 
  'jsBasePath'              => '/blueshoes-4.4/javascript/', 
  'imgBasePath'             => '/blueshoes-4.4/images/', 
  'language'                => 'en', 
);

include('./blueshoes-4.4/applications/filemanager/index.php');

?>