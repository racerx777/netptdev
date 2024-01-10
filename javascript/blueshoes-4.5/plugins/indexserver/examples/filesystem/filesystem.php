<?php
/**
* @package    plugins_indexserver
* @subpackage examples
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_IndexServer.class.php');
require_once($APP['path']['core']    . 'file/Bs_Dir.class.php');

//set_time_limit(1200);

$out  = '';
$out .= "<h1>Bs_IndexServer - Filesystem Example</h1>\n";
$out .= "The example directories and its files get indexed.<br><br>\n";

$dsn = array(
	'name'   => 'bs_site_www_bs_org', 
	'host'   => 'localhost', 
	'port'   => '3306', 
	'user'   => 'root', 
	'pass'   => '', 
	'syntax' => 'mysql', 
	'type'   => 'mysql', 
);

$profileName = 'filesystem';

$doIndex = TRUE;

if ((!@$_POST['step'] !== '2') && $doIndex) {
	$out .= "<h2>creating profile...</h2>\n";
	
	$profile =& new Bs_Is_Profile();
	$status = $profile->setDbByDsn($dsn);
	if (isEx($status)) {
		$status->stackDump('die');
	}
	
	$profile->drop($profileName);
	$profile->create($profileName, $profileXml='');
	$profile->load($profileName);
	
	$is =& new Bs_Is_IndexServer();
	$is->setProfile($profile);
	
	$indexer  = &$is->getIndexer($profileName);
	if (isEx($indexer)) {
		$indexer->stackDump($indexer, 'die');
	}
	//$indexer->debug = TRUE;
	$out .= "<h2>indexing...</h2>\n";
	$dir =& new Bs_Dir();
	$pathStem = $dir->getPathStem($_SERVER['SCRIPT_FILENAME']);
	$params = array(
		'fullPath'    => $pathStem, 
		//'regFunction' => 'eregi', //'preg_match', 
		//'regEx'       => '[^/CVS/]\.[txt|pdf|doc|xls|htm|html]', 
		'regEx'       => '\.[txt|pdf|doc|xls|htm|html]', 
		'depth'       => BS_DIR_UNLIM_DEPTH, 
		'fileDirLink' => array('filelink'=>FALSE, 'dir'=>FALSE, 'dirlink'=>FALSE), 
		'returnType'  => 'subpath', 
	);
	$fileList = $dir->getFileList($params);
	//dump($fileList); exit; //4debug
	
	foreach($fileList as $fileSubPath) {
		if (substr($fileSubPath, -4) === '.php') continue; //hacky
		//if (substr($fileSubPath, -4) !== '.doc') continue; //4debug
		$status = $indexer->indexByPath($pathStem . $fileSubPath, $fileSubPath);
		//dump($indexer->debugOut);
		//$indexer->debugOut = '';
	}
} else {
	$profile =& new Bs_Is_Profile();
	$status = $profile->setDbByDsn($dsn);
	if (isEx($status)) {
		$status->stackDump('die');
	}
	$profile->load($profileName);
	
	$is =& new Bs_Is_IndexServer();
	$is->setProfile($profile);
}

$out .= "<h2>performing search...</h2>\n";
$out .= "the output is a hash where the key is the source id (line number) and ";
$out .= "the value is the number of points. it is ordered by relevance.<br><br>\n";
$query = (isSet($_POST['query'])) ? $_POST['query'] : 'document';
$searcher = &$is->getSearcher($profileName);
$out .= dump($searcher->search($query), TRUE);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Bs_IndexServer - Filesystem Example</title>
</head>

<body>

<?php
echo $out;
?>

<br><br>
perform search (without re-indexing):
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<input type="hidden" name="step" value="2">
	<input type="text" name="query" value="<?php echo $query;?>">	<input type="submit" name="submit" value="Search">
</form>

</body>
</html>
