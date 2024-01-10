<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33); 
Function listdir($start_dir='.') {
  $files = array();
  if (is_dir($start_dir)) {
    $fh = opendir($start_dir);
    while (($file = readdir($fh)) !== false) {
      # loop through the files, skipping . and .., and recursing if necessary
      if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue;
      $filepath = $start_dir . '/' . $file;
      if ( is_dir($filepath) )
        $files = array_merge($files, listdir($filepath));
      else
        array_push($files, $filepath);
    }
    closedir($fh);
  } else {
    # false if the function was called with an invalid non-directory argument
    $files = false;
  }

  return $files;

}
?>
<p>Verify Transferfiles (verify date/time/size):</p>
<?php
$array=listdir($_SERVER['DOCUMENT_ROOT'].'/collections');
foreach($array as $index=>$filepath) {
	$filesize1 = filesize($filepath);

	$changedate["$index"] = date("Y/m/d H:i:s", filectime($filepath));
	$path_parts = pathinfo($filepath);

	$basename["$index"] = $path_parts['basename'];

	$dirpath = $path_parts['dirname'];
	$slashpos = strrpos($dirpath, "/");
	$dirname["$index"] = substr($dirpath, $slashpos+1);

	clearstatcache();
	sleep(1);

	$filesize2 = filesize($filepath);
	if($filesize1 == $filesize2)
		$style["$index"]='  bgcolor="lightgreen"';
	else
		$style["$index"]='  bgcolor="yellow"';

	$filesize["$index"] = round(filesize($filepath)/1024000,1);
}

echo '<table cellpadding="5" cellspacing="0" border="1" >';
echo("<tr><th>Dir</th><th>File</th><th>Date</th><th>Size(MB)</th><tr>");
foreach($array as $index=>$filepath) 
	echo('<tr '.$style["$index"].'>
	<td>'.$dirname["$index"].'</td><td>'.$basename["$index"].'</td><td>'.$changedate["$index"].'</td><td align=\"right\">'.$filesize["$index"].'</td><tr>');
echo "</table>";
// list files and attributes in the collections directory
?>
<a href="./collectionsUngzip.php" target="_blank"><p>Uncompress Files</p></a>
<a href="./collectionsImportPat1.php" target="_blank"><p>Import pat1</p></a>
<a href="./collectionsImportTransvA.php" target="_blank"><p>Import transvA</p></a>
<a href="./collectionsImportTransv.php" target="_blank"><p>Import transv</p></a>
<a href="./collectionsCreateNewAccount.php" target="_blank"><p>Create Accounts</p></a>
<a href="./collectionsCleanAccounts.php" target="_blank"><p>Clean Accounts</p></a>
<a href="./collectionsCleanQueue.php" target="_blank"><p>Clean Queue</p></a>
<a href="./collectionsQueueAssignment.php" target="_blank"><p>Queue Assignment</p></a>