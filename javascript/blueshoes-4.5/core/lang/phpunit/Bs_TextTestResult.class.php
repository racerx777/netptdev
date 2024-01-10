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
define('BS_TEXTTESTRESULT_VERSION',      '4.5.$Revision: 1.2 $');class Bs_TextTestResult extends Bs_TestResult {function Bs_TextTestResult() {$this->Bs_TestResult();  }
function report() {$nRun = $this->countTests();$nFailures = $this->countFailures();printf("<p>%s test%s run<br>", $nRun, ($nRun == 1) ? '' : 's');printf("%s failure%s.<br>\n", $nFailures, ($nFailures == 1) ? '' : 's');if ($nFailures == 0) return;print("<ol>\n");$failures = $this->getFailures();while (list($i, $failure) = each($failures)) {$failedTestName = $failure->getTestName();printf("<li>%s\n", $failedTestName);$exceptions = $failure->getExceptions();print("<ul>");while (list($na, $exception) = each($exceptions))
printf("<li>%s\n", $exception->stackDump('return'));print("</ul>");}
print("</ol>\n");}
function _startTest($test) {printf("%s ", $test->name());flush();}
function _endTest($test) {$outcome = $test->failed()
? "<font color=\"red\">FAIL</font>"
: "<font color=\"green\">ok</font>";printf("$outcome<br>\n");flush();}
}
?>