<?php
/**
* @package    plugins_indexserver
* @subpackage examples_lyrics
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['plugins']   . 'indexserver/Bs_Is_IndexServer.class.php');
require_once($APP['path']['core']      . 'net/Bs_Url.class.php');

set_time_limit(600);


$props = array(
	'artist' => array(
		'type'         => 'text', 
		'weight'       => 80, 
		'lang'         => 'en', 
	),
	'title' => array(
		'type'         => 'text', 
		'weight'       => 100, 
		'lang'         => 'en', 
	),
	'lyric' => array(
		'type'         => 'text', 
		'weight'       => 60, 
		'lang'         => 'en', 
	),
);


$dsn = array(
	'name'   => 'test', 
	'host'   => 'localhost', 
	'port'   => '3306', 
	'user'   => 'root', 
	'pass'   => '', 
	'syntax' => 'mysql', 
	'type'   => 'mysql', 
);
$profileName      = 'lyricsExample';

$out = '';

$profile =& new Bs_Is_Profile();
$status = $profile->setDbByDsn($dsn);
if (isEx($status)) {
	$status->stackDump('die');
}
$profile->load($profileName);

$is =& new Bs_Is_IndexServer();
$is->setProfile($profile);

$searcher = &$is->getSearcher($profileName);
if (!empty($_REQUEST['query'])) {
  $out = "<h2>performing search...</h2>\n";
  $searchResult = $searcher->search2($_REQUEST['query']);
  $queryWordsForHighlight = array(); //$searcher->getQueryWordsForHighlight();
  //dump($queryWordsForHighlight);
  $queryWordsForHighlightQs = ''; //$Bs_Url->hashArrayToQueryString($queryWordsForHighlight, $prefix='highlight', $firstSeparator='&');
  
  $searcher->stopWatch->takeTime('userland line: ' . __LINE__);
  $tryThese = ''; //$searcher->recommendWords();
  $searcher->stopWatch->takeTime('userland line: ' . __LINE__);
  $out .= "<b>Number of matches: " . sizeOf($searchResult) . "</b><br><br>\n";
  //dump($searchResult);
  $bsDb = &getDbObject($dsn);
  
  //some optimization so we don't have to fetch all recs:
  $recsPerPage = 500;
  $recsOffset  = 0;
  $idList = array();
  for ($i=0; $i<$recsPerPage; $i++) {
    if ($i < $recsOffset) continue;
    if ($i > ($recsOffset + $recsPerPage)) break;
    
    $idList[] = key($searchResult);
    if (next($searchResult) == FALSE) break;
  }
  //$idList = array_keys($searchResult);
  
  $sql = "SELECT ID, artist, title FROM exampleLyrics WHERE ID IN (" . join(',', $idList) . ")";
  $recs = $bsDb->getAssoc($sql, FALSE, TRUE);
  //dump($recs);
  $searcher->stopWatch->takeTime('userland line: ' . __LINE__);
  $out .= '<ol>';
  $i = 0;
  foreach ($searchResult as $ID => $points) {
  	$out .= '<li>' . ' ' . $recs[$ID]['artist'] . ' - <a href="./show.php?ID=' . $ID . $queryWordsForHighlightQs . '" target="_blank">' . $recs[$ID]['title'] . '</a> <font size="1">' . $ID . ' (' . $points . ')</font></li>';
    
    $i++;
    if ($i < $recsOffset) continue;
    if ($i >= ($recsOffset + $recsPerPage)) break;
  }
  $out .= '</ol>';
}
?>

<form name="myForm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	search: <input type="text" name="query" size="50" value="<?php echo str_replace('"', "&quot;", @$_REQUEST['query']);?>"> <input type="submit" name="send" value="search">
	<?php
  if (!empty($searcher->hintString)) {
    echo '<br>' . $searcher->hintString;
  }
	if (!empty($tryThese)) {
		echo '<br>Related words: ' . join(', ', array_keys($tryThese)) . '<br>';
	}
	?>
</form>

<?php 
echo $out;
$searcher->stopWatch->takeTime('userland line: ' . __LINE__);
echo '<br><br><br><br>';
echo $searcher->stopWatch->toHtml();
?>