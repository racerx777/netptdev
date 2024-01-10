<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"] . '../global.conf.php');
require_once($APP['path']['core']			. 'db/Bs_Db.class.php');
require_once($APP['path']['core']			. 'html/form/Bs_FormItAble.class.php');
require_once($APP['path']['core']			. 'storage/objectpersister/Bs_SimpleObjPersister.class.php');

/**
* simple person class.
*/
class PersonRecord {
	
	var $ID           = 0;
	var $gender       = 0; //field: 0 = unknown, 1 = male, 2 = female
	var $givenName    = '';
	var $familyName   = '';
	var $emailAddress = '';
	var $comments     = '';
	
	var $theFiaHints; //hints for the formitable class.
	
	/**
  * Constructor
  * - inits the FIA hint hash with default values
  */
  function PersonRecord () {
    $this->initFiaHints();
  }
  
  /**
	* callback function for the object persister.
	*/
	function bs_sop_getHints() {
		$hint_hash = array (
			'primary' => array (
				'ID' => array('name'=>'id', 'type'=>'auto_increment'),
			), 
			'fields' => array (
				'gender'       => array('metaType'=>'integer'), 
				'givenName'    => array('metaType'=>'string', 'size'=>40), 
				'familyName'   => array('metaType'=>'string', 'size'=>40), 
				'emailAddress' => array('metaType'=>'string', 'size'=>120), 
				'comments'     => array('metaType'=>'blob',   'size'=>10000), 
			), 
		);
		return $hint_hash;
	}
	
	/**
	* callback function of Bs_FormItAble.
	* Return the hints used to build the form.
	*/
	function bs_fia_getHints($fiaAgent) {
		return $this->theFiaHints;
	}
	
	/**
	* by having the load in a separate function, we can load the [default] 
	* hints, and then modify them from outside so that the callback function 
	* bs_fia_getHints() will fetch customized settings.
	*/
	function initFiaHints() {
    // First define an access rights array (used below)
		$accessOmit = array(
			'guest'			=> 'omit', 
			'admin'			=> 'normal', 
		);
		
		$this->theFiaHints = array(
			'props' => array(
				'internalName'       => 'fiaForm', 
				'name'               => 'fiaForm', 
				'mode'               => 'add', 
				'language'           => 'en', 
				'user'               => 'guest',  // set guest user as default
				'useAccessKeys'      => TRUE,
				'useTemplate'        => FALSE,
				'templatePath'       => $_SERVER['DOCUMENT_ROOT'] . './templates/',
				'jumpToFirstError'   => TRUE,
				'buttons'            => 'default', 
				'advancedStyles'     => array(
					'captionMustWrong' => 'formError', // 'formError' is a CSS style
					'captionMayWrong'  => 'formError', 
				),
			),
			'groups' => array(
				'grpData' => array(
					'caption'   => 'Data', 
					'mayToggle' => FALSE, 
				),
			),
			'fields' => array(
				'ID' => array(
					'name'            => 'ID',
					'group'           => 'grpData', 
					'fieldType'       => 'Bs_FormFieldHidden',
					'must'            => FALSE, 
					'editability'     => 'always',
				),
				'gender' => array(
					'name'            => 'gender',
					'caption'         => array('en'=>'Gender', 'de'=>'Geschlecht'), 
					'group'           => 'grpData', 
					'fieldType'       => 'Bs_FormFieldRadio',
					'optionsHard'     => array(
						'en'=>array('0'=>'unknown',   '1'=>'male',     '2'=>'female'), 
						'de'=>array('0'=>'unbekannt', '1'=>'männlich', '2'=>'weiblich'), 
					), 
					'must'            => TRUE, 
					'editability'     => 'always',
				),
				'givenName' => array(
					'name'            => 'givenName',
					'caption'         => array('en'=>'Given name', 'de'=>'Vorname'), 
					'group'           => 'grpData', 
					'fieldType'       => 'Bs_FormFieldText',
					'must'            => TRUE, 
					'minLength'       => 2, 
					'editability'     => 'always',
				),
				'familyName' => array(
					'name'            => 'familyName',
					'caption'         => array('en'=>'Family name', 'de'=>'Nachname'), 
					'group'           => 'grpData', 
					'fieldType'       => 'Bs_FormFieldText',
					'must'            => TRUE, 
					'minLength'       => 2, 
					'editability'     => 'always',
				),
				'emailAddress' => array(
					'name'            => 'emailAddress',
					'caption'         => array('en'=>'Email address', 'de'=>'E-Mail Adresse'), 
					'group'           => 'grpData', 
					'fieldType'       => 'Bs_FormFieldText',
					'must'            => FALSE, 
					'editability'     => 'always',
				),
				'comments' => array(
					'name'            => 'comments',
					'caption'         => array('en'=>'Comments', 'de'=>'Bemerkungen'), 
					'accessRights'    => $accessOmit, // accessRights setting
					'group'           => 'grpData', 
					'fieldType'       => 'Bs_FormFieldTextarea',
					'must'            => FALSE, 
					'editability'     => 'always',
					'cols'            => 30, 
				),
			), 
    );
		return $this->theFiaHints;
	}
} //end of class


//if the cancel button, and not the submit button has been pushed, we ignore the submission of the form.
if (!empty($_REQUEST['bs_form']['btnCancel'])) {
	unset($_REQUEST);
	unset($_GET);
	unset($_POST);
}


$dsn = array ('name'=>'test', 'host'=>'localhost', 'port'=>'3306', 'socket'=>'',
							'user'=>'root', 'pass'=>'',
							'syntax'=>'mysql', 'type'=>'mysql' 
);
if (isEx($dbAgent =& getDbObject($dsn))) {
	$dbAgent->stackDump('echo');
	die();
}
$objPersister = new Bs_SimpleObjPersister();
$objPersister->setDbObject($dbAgent);


$outHead = '';
$outBody = '';

$personRecord =& new PersonRecord();
if (!empty($_REQUEST['ID'])) {
	//let's load it
	$personRecord->ID = $_REQUEST['ID'];
	$objPersister->load($personRecord);
}

if (!empty($_REQUEST['ID'])) {
	$personRecord->theFiaHints['props']['mode'] = 'edit';
}
if (!empty($_REQUEST['user'])) {
	$personRecord->theFiaHints['props']['user'] = $_REQUEST['user'];
}

$fia	 =& new Bs_FormItAble();
$fiaOut = $fia->doItYourself($personRecord);
if ($fiaOut === TRUE) {
	//successful submit, let's store it.
	$objPersister->store($personRecord);
	$outBody .= "<b>Successfully stored as ID: " . $personRecord->ID . '</b>';
} else {
	$form =& new Bs_Form();
	$outHead .= $form->includeOnceToHtml($fiaOut['include']);
	$outHead .= $form->onLoadCodeToHtml($fiaOut['onLoad']);
	if (!empty($fiaOut['errors'])) $outBody .= $fiaOut['errors'];
	$outBody .= $fiaOut['form'];
}

$outBody .= "<br><br>";
$outBody .= "<a href='{$_SERVER['PHP_SELF']}'>add new record as guest</a><br>\n";
$outBody .= "<a href='{$_SERVER['PHP_SELF']}?user=admin'>add new record as admin</a><br>\n";
$objs = $objPersister->loadAll('PersonRecord');
if (is_array($objs)) {
	$outBody .= "<h4>Stored records:</h4>";
	foreach ($objs as $obj) {
		$outBody .= "ID {$obj->ID}, {$obj->givenName} {$obj->familyName} - <a href='{$_SERVER['PHP_SELF']}?ID={$obj->ID}&user=admin'>edit</a><br>\n";
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>FormItAble Example</title>
	<?php echo $outHead; ?>
	<style>
		body			 { font-family: arial, helvetica; font-size: 12px; }
		td				 { font-size: 12px; }
		.formError { color: red; }
	</style>
</head>

<body bgcolor="#E6E6E6">
<h1>FormItAble Example</h1>

<ul>
	<li>Demonstration of the FormItAble way of making objects editable.</li>
	<li>Read the HOWTO to understand what's going on here.</li>
</ul>
<br>

<?php echo $outBody; ?>

</body>
</html>