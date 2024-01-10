<?php
/**
* @package    plugins_onomastics
* @subpackage examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
require_once($APP['path']['plugins']      . 'onomastics/Bs_Om_OnomasticsServer.class.php');
$o = &$GLOBALS['Bs_Om_OnomasticsServer'];

//grab the data
echo "<h1>getting the content</h1>";
$fContent = file('http://www.google.com/jobs/britney.html');
//dump($fContent); //4debug

$nameArray = array();
$started = FALSE;
while (list(,$line) = each($fContent)) {
  $line = trim($line);
  if (!$started) {
    if ($line == '<table><tr><td valign=top><font size=-1><tt>') {
      $started = TRUE;
    }
    continue;
  } else {
    if ($line == '</tt></font></td></tr></table>') {
      break;
    } elseif ($line == '<nobr>') {
      continue;
    } else {
      $pos = strpos($line, ' ');
      if ($pos === FALSE) continue;
      $name = trim(substr($line, $pos+1));
      $pos2 = strpos($name, '</nobr>');
      if ($pos2 === FALSE) continue;
      $name = trim(substr($name, 0, $pos2));
      $nameArray[] = explode(' ', $name);
    }
  }
}
//dump($nameArray);
echo "<h1>starting the ono stuff</h1>";

while (list(,$name) = each($nameArray)) {
  $firstname = $name[0];
  $lastname  = $name[1];
  echo "<h3>new name: first={$firstname}, last={$lastname}</h3>";
  echo "gender: " . $o->getGender($firstname, FALSE, TRUE) . "<br>";
  $list = $o->findFirstname($firstname);
  if ($list) {
    $sql = "select captionHtml from BsOnomastics.firstname where id in (" . join(',',$list) . ")";
    /*
    $x = $bsDb->getRow($sql);
    if (isEx($x)) {
      $x->stackDump('die');
    }*/
    $foundNameList = $bsDb->getRow($sql);
    while (list($foundKey, $foundName) = each($foundNameList)) {
      $foundNameList[$foundKey] = "<a href='/_plugins/onomastics/Bs_Om_OnoGraphHtml.class.php?name={$foundName}'>{$foundName}</a>";
    }
    //$listStr = join(', ', $bsDb->getRow($sql));
    $listStr = join(', ', $foundNameList);
    echo "list: {$listStr}<br>";
  }
  echo "firstname similarity: " . $o->calcSimilarityFirstname($firstname, 'britney') . "<br>";
  echo "lastname similarity: " . $o->calcSimilarityLastname($lastname, 'spears') . "<br>";
}


?>