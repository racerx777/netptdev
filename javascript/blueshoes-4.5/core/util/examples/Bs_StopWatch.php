<?php
/**
* @package     core_util
*/

require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['core']      . 'util/Bs_StopWatch.class.php');

$myStopWatch =& new Bs_StopWatch();
$myStopWatch->reset();

for ($i=0; $i<200000; $i++) {;}   // Use some CPU
$myStopWatch->takeTime("Take 1"); // Take time 

for ($i=0; $i<30000; $i++) {;}    // Use some more CPU
$myStopWatch->takeTime("Take 2"); // Take time again

for ($i=0; $i<60000; $i++) {;}
$myStopWatch->takeTime("Take 3");

for ($i=0; $i<100000; $i++) {;}  
$myStopWatch->takeTime("Take 4"); // Last time take.

// Output as HTML
echo $myStopWatch->toHtml("Test");

// Output as Text
echo "<br><hr><pre>";
echo $myStopWatch->toString("Test");
?>