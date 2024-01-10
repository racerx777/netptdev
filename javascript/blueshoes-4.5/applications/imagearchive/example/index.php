<?php
/**
* @package    applications_imagearchive
* @subpackage examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
require_once($APP['path']['core']         . 'auth/cug/Bs_CugDb.class.php');
require_once($APP['path']['applications'] . 'imagearchive/Bs_ImageArchive.class.php');

$cug =& new Bs_CugDb('imageArchive');
$cug->userTableName = 'ExampleCugImageArchive';
$cug->logTableName  = 'ExampleCugImageArchiveLoginLog';
$cug->redirectPage  = 'index.php';
$cug->language      = 'en';
if (!$cug->letMeIn()) {
  if (!is_object($bsDb)) {
    include_once($APP['path']['core'] . 'db/Bs_MySql.class.php');
    $bsDb =& new Bs_MySql();
    $connId = $bsDb->connect($APP['db']['main']);
    if (isEx($connId)) {
      //crap.
      $connId->stackTrace('', __FILE__, __LINE__, 'fatal');
      $connId->stackDump('alert');
      die('check your db, honey!');
    } else {
      $GLOBALS['bsDb'] = &$bsDb;
    }
    $cug->setDbObject($bsDb);
  }
  $status = $cug->treatLoginForm();
  if ($status === TRUE) {
    //oki doki, register some vars.
    $t        = $cug->form->getFieldValue('username');
    $username = $t[0];
    $cug->bsSession->register('CugImageArchiveUser', $username);
  } else {
    //display login form
    echo $status;
    exit;
  }
}

$iArch =& new Bs_ImageArchive();
$iArch->serverBasePath   = $_SERVER['DOCUMENT_ROOT'] . 'examples/ImageArchive/images/';
$iArch->webBasePath      = '/examples/ImageArchive/images/';
$iArch->thumbSettings = array(
  'prefix' => 'thumb_', 
  'width'  => 60, 
  'height' => 60, 
);
$iArch->overviewSettings = array(
  'tdWidth'   => 65, 
  'tdHeight'  => 65, 
  'tdColor'   => 'silver', 
  'cols'      => 7, 
  'rows'      => 4, 
);
$iArch->sessionUser = $bsSession->getVar('CugImageArchiveUser');
$iArch->currentDirectory = (isSet($_REQUEST['currentDirectory'])) ? $_REQUEST['currentDirectory'] : '';
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Example Image Archive</title>
  <link rel="stylesheet" href="main.css">
</head>

<body>
<div style="position:absolute; left:18; top:52;  width:126; height:386; background: #E7E7E7; z-index: 5;">&nbsp;</div>
<div style="position:absolute; left:82; top:111; width:126; height:386; background: #EFEFEF; z-index: 4;">&nbsp;</div>

<div style="position:absolute; left:264; top:52; z-index: 1000;">
<img src="titleWebcamart.gif" width="226" height="56" border="0">
</div>

<div style="position:absolute; top:111; z-index:1000;">
<table>
  <tr>
    <td width="20">&nbsp;</td>
    <td valign="top" width="228">
<?php
echo $iArch->getDirectoryControl();
?>
    </td>
    <td valign="top">
      <br><br><br><br><br><br>
<?php
$iArch->treatUploadedFile();
echo $iArch->getBreadcrumbNav() . '<br><br>';
echo $iArch->getOverview();
echo '<br><br><br>';
echo "<div style='position:absolute; FILTER: progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=40);'>";
echo $iArch->getFileUploadForm();
echo "</div>";
?>
    </td>
  </tr>
</table>
</div>

</body>
</html>
