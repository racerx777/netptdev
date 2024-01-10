<?php
/**
* @package    plugins_indexserver
* @subpackage examples
*/



/*
* to run this example, at first you need to have the text file indexed. 
* 
* 1) check the dsn for the db settings a few lines down from here.
* 2) maybe you want to specify a number for $indexLimitLines, a 
*    2ghz cpu takes about 190 seconds for the full 20k lines file. 
* 3) the shakespere.txt has to be in the same directory as this script 
*    file. that should already be the case. 
* 4) then call the script url with ?reindex=1 attached.
* 
* after that, your index is ready, and you can use the form to search.
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['plugins']   . 'indexserver/Bs_Is_IndexServer.class.php');
require_once($APP['path']['core']      . 'html/Bs_HtmlUtil.class.php');

set_time_limit(1200);

$dsn = array(
	'name'   => 'bs_site_www_bs_org', 
	'host'   => 'localhost', 
	'port'   => '3306', 
	'user'   => 'root', 
	'pass'   => '', 
	'syntax' => 'mysql', 
	'type'   => 'mysql', 
);
$profileName      = 'shakespere';
$indexLimitLines  = 0;  //0 = no limit
$maxSearchResults = 20; //0 = no limit

$out  = '';
$out .= "<h1>Bs_IndexServer - Shakespere Example</h1>\n";
$out .= "The shakespere.txt file is indexed line by line.<br><br>\n";


if (@$_REQUEST['reindex'] == 1) {
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
	$out .= "<h2>indexing file...</h2>\n";
	//$fileContent = file($_SERVER['DOCUMENT_ROOT'] . 'indexserver/examples/shakespere/shakespere.txt');
	$fileContent = file(substr($_SERVER['SCRIPT_FILENAME'], 0, -3) . 'txt');
	foreach ($fileContent as $lineNumber => $lineString) {
		$status = $indexer->index($lineNumber +1, $lineString);
		//dump($indexer->debugOut);
		//$indexer->debugOut = '';
		if (($indexLimitLines > 0) && ($lineNumber >= $indexLimitLines)) break;
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

if (!empty($_POST['query'])) {
	$outResult = "<h2>performing search...</h2>\n";
	//$outResult .= "the output is a hash where the key is the source id (line number) and ";
	//$outResult .= "the value is the number of points. it is ordered by relevance.<br><br>\n";
	$searcher = &$is->getSearcher($profileName);
	//$outResult .= dump($searcher->search($_POST['query']), TRUE);
	$searchResult = $searcher->search($_POST['query']);
	$outResult .= "<b>Number of matches: " . sizeOf($searchResult) . "</b><br><br>\n";
	$textFileContent = file(substr($_SERVER['SCRIPT_FILENAME'], 0, -3) . 'txt');
	$outResult .= '<table border="1" cellspacing="2" cellpadding="2">';
	$outResult .= '<tr><td>#</td><td>Line</td><td>Points</td><td>Line content</td></tr>';
	$i=0;
	foreach ($searchResult as $line => $points) {
		$outResult .= '<tr>';
		$outResult .= "<td>" . ($i +1) . "</td>";
		$outResult .= "<td>{$line}</td><td>{$points}</td>";
		$outResult .= "<td>" . $textFileContent[$line -1] . "</td>";
		$outResult .= "</tr>\n";
		$i++;
		if (($maxSearchResults > 0) && ($i >= $maxSearchResults)) break;
	}
	$outResult .= '</table>';
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Bs_IndexServer - Shakespere Example</title>
<style>
body {
	font-family: arial, helvetica;
	font-size:   12px;
}
td {
	font-size:   12px;
}
.ex {
	cursor: hand;
	cursor: pointer;
	text-decoration: underline;
}
</style>
<script language="javascript">
	<!--
	function setExampleQuery(q) {
		document.getElementById('query').value = q;
	}
	// -->
</script>
</head>

<body bgcolor="#FFFFFF">

<?php
echo $out;
?>

<br><br>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<input type="hidden" name="step" value="2">
	<table border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td valign="top">
				Search for: <input type="text" name="query" id="query" value="<?php echo $Bs_HtmlUtil->filterForHtml(@$_POST['query']);?>" size="40">	<input type="submit" name="submit" value="Search">
			</td>
			<td width="20">&nbsp;</td>
			<td valign="top">
				Try these example queries: <br>
				simple: 
				<span class="ex" onclick="setExampleQuery(this.innerHTML);">England</span>, 
				<span class="ex" onclick="setExampleQuery(this.innerHTML);">kisses</span>, 
				<span class="ex" onclick="setExampleQuery(this.innerHTML);">tongue</span>, 
				<span class="ex" onclick="setExampleQuery(this.innerHTML);">king</span>
				<br>
				phrase searching:
				<span class="ex" onclick="setExampleQuery(this.innerHTML);">"king richard"</span>, 
				<br>
				must and exclude words:
				<span class="ex" onclick="setExampleQuery(this.innerHTML);">+king +richard</span>, 
				<span class="ex" onclick="setExampleQuery(this.innerHTML);">+king +richard -henry</span>
				<br>
				part word searching: 
				<span class="ex" onclick="setExampleQuery(this.innerHTML);">ambi*</span>, 
				<span class="ex" onclick="setExampleQuery(this.innerHTML);">*father</span>, 
			</td>
		</tr>
	</table>
</form>

<?php
echo @$outResult;
?>

</body>
</html>
