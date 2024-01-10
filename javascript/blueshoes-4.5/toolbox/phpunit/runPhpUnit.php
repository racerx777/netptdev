<?php/********************************************************************************************
* BlueShoes Framework; This file is part of the php application framework.
* NOTE: This code is stripped (obfuscated). To get the clean documented code goto 
*       www.blueshoes.org and register for the free open source *DEVELOPER* version or 
*       buy the commercial version.
*       
*       In case you've already got the developer version, then this is one of the few 
*       packages/classes that is only available to *PAYING* customers.
*       To get it go to www.blueshoes.org and buy a commercial version.
* 
* @copyright www.blueshoes.org
* @author    Samuel Blume <sam at blueshoes dot org>
* @author    Andrej Arn <andrej at blueshoes dot org>
*/?><?php
set_time_limit(60);require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");require_once($APP['path']['core'] . 'util/Bs_String.class.php');require_once($APP['path']['core'] . 'util/Bs_StopWatch.class.php');require_once($APP['path']['core'] . 'date/Bs_Date.class.php');require_once($APP['path']['core'] . 'file/Bs_FileSystem.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'lang/phpunit/Bs_Assert.class.php');require_once($APP['path']['core'] . 'lang/phpunit/Bs_TestCase.class.php');require_once($APP['path']['core'] . 'lang/phpunit/Bs_TestFailure.class.php');require_once($APP['path']['core'] . 'lang/phpunit/Bs_TestResult.class.php');require_once($APP['path']['core'] . 'lang/phpunit/Bs_TestSuite.class.php');require_once($APP['path']['core'] . 'lang/phpunit/Bs_TextTestResult.class.php');$aObject = new Bs_Object();$htOut = '';if (!isSet($_REQUEST['step'])) {$script = 'script'; $htOut .=<<<EOD
    <html><head>
    <script language='javascript'>
    <!--
    function toggleCheckboxes(theForm) {
      cbSize = theForm.elements['testClasses[]'].length;
      for (i=0; i<cbSize; i++) {
        theForm.elements['testClasses[]'][i].checked = true;
      }
    }
    -->
    </{$script} >
    </head><body>
    Which classes do you want to test?<br><br>
    <form action='{$_SERVER['PHP_SELF']}' method='post' name='ecg'>
      <input type=hidden name=step value='2'>\n
EOD;
$myStopWatch =& new Bs_StopWatch();$myStopWatch->reset();$Dir = new Bs_Dir($APP['path']['core'] . '../');$array = array('regEx' => '/ecg/[^/csv/].*PhpUnit.class.php$', 'regFunction' => 'eregi', 'regWhere' => 'dir', 'fileDirLink' => array('dir' => FALSE, 'filelink' => FALSE, 'dirlink' => FALSE));$fileList = $Dir->getFileList($array);$myStopWatch->takeTime("Finished collection Unit-tests"); if (isEx($fileList)) {$fileList->stackDump('echo');}
$myStopWatch->takeTime("Finished collection Unit-tests"); $counter = 0;foreach($fileList as $k => $v) {if (! $Bs_String->instr($v, '/CVS/')) {$counter++;$htOut .= "{$counter}) <input type=checkbox name=testClasses[] value=\"{$v}\"> $v<br>\n";}
}
$htOut .= "<br><input type=checkbox name=toggle onClick='javascript:toggleCheckboxes(ecg);'> select all<br><br>\n";$htOut .= "<input type=submit value=\"let's go\"></body></html>\n";echo $htOut;echo "<hr>" . $myStopWatch->toHtml("Time Used");die();}
class Bs_TestRunner {function run($suite) {$result = new Bs_TextTestResult;$suite->run($result);$result->report();}
}
$suite = new Bs_TestSuite;echo '<b>include test classes:</b><br>';foreach($_REQUEST['testClasses'] as $k => $v) {echo "$k => $v <br>";include_once($v);}
echo '<br><b>add test suites:</b><br>';$declaredClasses = get_declared_classes();foreach($declaredClasses as $k => $v) {if ($Bs_String->endsWithI($v, '_PhpUnit')) {if (isSet($GLOBALS[$v]) && ($GLOBALS[$v] == 'object')) {$suite->addTest(new $v($v));echo "$k => $v (runtest)<br>";} else {$suite->addTest(new Bs_TestSuite($v));echo "$k => $v <br>";}
}
}
?>
<html>
<head><title>PhpUnit test run</title></head>
<body>
<h1>PhpUnit test run</h1>
<p>
<?php
$myStopWatch =& new Bs_StopWatch();$myStopWatch->reset();if (isset($only)) $suite = new Bs_TestSuite($only);$testRunner = new Bs_TestRunner();$myStopWatch->takeTime("After init of Bs_TestRunner"); $testRunner->run($suite);$myStopWatch->takeTime("After Bs_TestRunner->run"); echo "<hr>" . $myStopWatch->toHtml("Time Used");?>
</body>
</html>