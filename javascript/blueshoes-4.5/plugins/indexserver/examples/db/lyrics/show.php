<?php
/**
* @package    plugins_indexserver
* @subpackage examples_lyrics
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
//require_once($APP['path']['plugins']   . 'indexserver/Bs_Is_IndexServer.class.php');

require_once($APP['path']['core']   . 'db/Bs_Db.class.php');

set_time_limit(600);

$dsn = array(
	'name'   => 'test', 
	'host'   => 'localhost', 
	'port'   => '3306', 
	'user'   => 'root', 
	'pass'   => '', 
	'syntax' => 'mysql', 
	'type'   => 'mysql', 
);
$bsDb = &getDbObject($dsn);

$sql = "SELECT * FROM exampleLyrics WHERE ID={$_REQUEST['ID']}";
$row = $bsDb->getRow($sql);

echo '<h2>Artist: ' . $row['artist'] . '</h2>';
echo '<h3>Title: ' . $row['title'] . '</h3><br>';

/*
$highlight = array(
  'has'          => TRUE, 
  'alrig*'       => TRUE, 
  'all the same' => TRUE, 
  '~sane'        => array('gho*t', 'chance'), 
);
$row['lyric'] = "everybody hâs a ghost everybody hàs a ghost who sings like you do yours is not like mine but it's alright, keep it up boy loses rib in new orleans he can't help eyein' up the whores under the bridge boy loses rib and lets a hellified cry into the dark where did i go wrong? where did i go wrong? i never needed this before i need a woman to help me feel everybody häs the dream everybody hasthe dream like a world tattoo yours is not like mine, it's alright, keep it up the scalped dives into the skin good doctors never leave a scar no proof again i'll taake the myth, you take the blood it's all the same to the world dreamer it's all the same in the end boy loses rib in new orleans he trades some ether for a chance under the bridge boy loses rib as he's summoned to the mud flat on his back cryin' where did i go wrong? ";
*/


$lyric = $row['lyric'];

$umlautOrig   = array('/ä/','/ö/','/ü/','/å/');
$umlautTrans  = array('ae', 'oe', 'ue', 'aa', );

$specialOrig  = array("/ß/","/[à-å]/","/æ/","/ç/","/[è-ë]/","/[ì-ï]/","/ð/","/ñ/","/[ò-öø]/","/÷/","/[ù-ü]/","/[ý-ÿ]/");
$specialTrans = array("ss", "a",      "ae", "c",  "e",      "i",      "d",  "n",  "o",       "x",  "u",      "y");

$extendedCharSet = 'a-zA-Z0-9';
foreach ($umlautOrig as $umlautChar) {
  $extendedCharSet .= str_replace('/', '', $umlautChar);
}
foreach ($specialOrig as $specialChar) {
  $extendedCharSet .= str_replace(array('/','[',']'), array('','',''), $specialChar);
}


if (@is_array($_REQUEST['highlight'])) {
  foreach ($_REQUEST['highlight'] as $phrase => $val) {
    if (!is_array($val)) {
      $val = array($phrase);
    }
    foreach ($val as $phrase) {
      for ($i=0; $i<sizeOf($umlautOrig); $i++) {
        $umlaut = str_replace('/', '', $umlautOrig[$i]);
        $phrase = str_replace($umlautTrans[$i], '[' . $umlautTrans[$i] . '|' . $umlaut . ']', $phrase);
      }
      
      for ($i=0; $i<sizeOf($specialOrig); $i++) {
        $special = str_replace(array('/','[',']'), array('','',''), $specialOrig[$i]);
        $phrase  = str_replace($specialTrans[$i], '[' . $specialTrans[$i] . '|' . $special . ']', $phrase);
      }
      
      if (strpos($phrase, '*') !== FALSE) {
        //part search
        $phrase = str_replace('*', "[{$extendedCharSet}]*", $phrase);
      }
      
      $phrase = "/([^{$extendedCharSet}]" . $phrase . "[^{$extendedCharSet}])/i";
      //dump($phrase);
      $lyric = preg_replace($phrase, " <b>\${1}</b> ", $lyric);
    }
  }
}
echo nl2br($lyric);

/*
$string = "April 15, 2003";
$pattern = "/(\w+) (\d+), (\d+)/i";
$replacement = "\${1}1,\$3";
print preg_replace($pattern, $replacement, $string);
//April1,2003
*/

//echo '<pre>' . $row['lyric'] . '</pre>';
?>

