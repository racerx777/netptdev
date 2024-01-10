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
define('BS_STOPWATCH_VERSION',      '4.5.$Revision: 1.2 $');define ('BS_STOPWATCH_SW_SEC' , 1);define ('BS_STOPWATCH_SW_MSEC', 0);class Bs_StopWatch {var $_startTime     = NULL;   var $_stops         = NULL;   var $_lastTakeTime  = NULL;   var $_lastDeltaTime = NULL;   function Bs_StopWatch() {$this->reset();}
function reset() {$this->_lastTakeTime = $this->_lastDeltaTime = $this->_startTime = explode(' ', microtime());$this->_stops = array();}
function takeTime($info='') {$now   = explode(' ', microtime());$tot   = (round( (($now[BS_STOPWATCH_SW_SEC] - $this->_startTime[BS_STOPWATCH_SW_SEC]) + ($now[BS_STOPWATCH_SW_MSEC] - $this->_startTime[BS_STOPWATCH_SW_MSEC]))*1000 ));$delta = (round( (($now[BS_STOPWATCH_SW_SEC] - $this->_lastTakeTime[BS_STOPWATCH_SW_SEC]) + ($now[BS_STOPWATCH_SW_MSEC] - $this->_lastTakeTime[BS_STOPWATCH_SW_MSEC]))*1000 ));$this->_lastTakeTime = $now;$this->_stops[] = array('INFO'=>$info, 'TOT'=>$tot, 'DELTA'=>$delta);}
function getTime() {$now   = explode(' ', microtime());return (round( (($now[BS_STOPWATCH_SW_SEC] - $this->_startTime[BS_STOPWATCH_SW_SEC]) + ($now[BS_STOPWATCH_SW_MSEC] - $this->_startTime[BS_STOPWATCH_SW_MSEC]))*1000 ));}
function getDelta() {$now   = explode(' ', microtime());$delta = (round( (($now[BS_STOPWATCH_SW_SEC] - $this->_lastDeltaTime[BS_STOPWATCH_SW_SEC]) + ($now[BS_STOPWATCH_SW_MSEC] - $this->_lastDeltaTime[BS_STOPWATCH_SW_MSEC]))*1000 ));$this->_lastDeltaTime = $now;return $delta;}
function toHtml($title='') {$ret = '';if ($title != '') $ret .= "<B>{$title}</B><br>";$this->_weightIt(); $ret .= <<< EDO
      <table cellspacing="0" cellpadding="2">
      <tr>
          <th bgcolor="Aqua">Nr.</th>
          <th bgcolor="Silver">INFO</th>
          <th bgcolor="Aqua">DELTA<br>(ms)</th>
          <th bgcolor="Silver">TOT<br>(ms)</th>
          <th bgcolor="Aqua">-</th>
      </td>
EDO;
$stopSize = sizeOf($this->_stops);for ($i=0; $i<$stopSize; $i++) {$stop = $this->_stops[$i];$weight = str_pad('', $stop['weight'], '*');$ret .= <<< EDO
      <tr>
          <td align="center" bgcolor="Aqua">{$i}</td>
          <td bgcolor="Silver">{$stop['INFO']}</td>
          <td align="right" bgcolor="Aqua">{$stop['DELTA']}</td>
          <td align="right" bgcolor="Silver">{$stop['TOT']}</td>
          <td align="left" bgcolor="Aqua">{$weight}</td>
      </tr>
EDO;
}
$ret .= "</table>";return $ret;}
function toString($title='') {$this->_weightIt(); $padInfo = $padDelta = $padTot = 0;$stopSize = sizeOf($this->_stops);for ($i=0; $i<$stopSize; $i++) {$stop = $this->_stops[$i];$padInfo  = max($padInfo,  strlen($stop['INFO']));$padDelta = max($padDelta, strlen($stop['DELTA']));$padTot   = max($padTot,   strlen($stop['TOT']));}
$padDelta++; $padTot++;$ret = '';$ret .= $title . "\n" . str_pad('', $padInfo, ' ', STR_PAD_LEFT);$ret .= '|' . str_pad('d', $padDelta, ' ', STR_PAD_BOTH);$ret .= '|' . str_pad('tot [ms]', $padTot, ' ', STR_PAD_BOTH);$ret .= "\n";$ret .= str_pad('', strlen($ret), '-') . "\n";for ($i=0; $i<$stopSize; $i++) {$stop = $this->_stops[$i];$ret .= str_pad($stop['INFO'], $padInfo, ' ', STR_PAD_LEFT);$ret .= '|' . str_pad($stop['DELTA'], $padDelta, ' ', STR_PAD_LEFT);$ret .= '|' . str_pad($stop['TOT'], $padTot, ' ', STR_PAD_LEFT);$ret .= ' |' . str_pad('', $stop['weight'], '*');$ret .= "\n";}
return $ret;}
function _weightIt() {$stopSize = sizeOf($this->_stops);if ($stopSize<=0) return; $totalTime = $this->_stops[$stopSize-1]['TOT'];$totalTime = empty($totalTime) ? 1 : $totalTime;for ($i=0; $i<$stopSize; $i++) {$this->_stops[$i]['weight'] = round(60 * $this->_stops[$i]['DELTA'] / $totalTime);} 
}
}
$GLOBALS['Bs_StopWatch'] =& new Bs_StopWatch(); if (basename($_SERVER['PHP_SELF']) == 'Bs_StopWatch.class.php') {$myStopWatch =& new Bs_StopWatch();$myStopWatch->reset();for ($i=0; $i<200000; $i++) {;}   $myStopWatch->takeTime("Take 1"); for ($i=0; $i<30000; $i++) {;}    $myStopWatch->takeTime("Take 2"); for ($i=0; $i<60000; $i++) {;}
$myStopWatch->takeTime("Take 3");for ($i=0; $i<100000; $i++) {;}  
$myStopWatch->takeTime("Take 4"); echo $myStopWatch->toHtml("Test");echo "<br><hr><pre>";echo $myStopWatch->toString("Test");}
?>