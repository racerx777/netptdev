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
*/?><script language="javascript">
<!--
function cheatOver(num, xx, yy) {if (!document.getElementById) return;var col = '#F76521';var td = document.getElementById('cheatTd' + num + '_' + xx + '_' + yy);td.style.backgroundColor = col;//td.style.fontWeight = 'bold';var td = document.getElementById('cheatTd' + num + '_' + '0' + '_' + yy);td.style.backgroundColor = col;var td = document.getElementById('cheatTd' + num + '_' + xx + '_' + '0');td.style.backgroundColor = col;}
function cheatOut(num, xx, yy) {if (!document.getElementById) return;var td = document.getElementById('cheatTd' + num + '_' + xx + '_' + yy);td.style.backgroundColor = '';//td.style.fontWeight = 'normal';var td = document.getElementById('cheatTd' + num + '_' + '0' + '_' + yy);td.style.backgroundColor = '';var td = document.getElementById('cheatTd' + num + '_' + xx + '_' + '0');td.style.backgroundColor = '';}
//-->
</script>
<h1>PHP Cheat Sheet</h1><br>
<?php
function boolToStr($val) {return ($val) ? '<font color=green>true</font>' : '<font color=red>false</font>';}
$a = array(
'$x = "";'          => '', 
'$x = null;'        => null, 
'var $x; (not set)' => @$really_not_set, 
'$x = array();'     => array(), 
'$x = false;'       => false, 
'$x = 15;'      => 15, 
'$x = 1;'       => 1, 
'$x = 0;'       => 0, 
'$x = -1;'      => -1, 
'$x = "15";'    => '15', 
'$x = "1";'     => '1', 
'$x = "0";'     => '0', 
'$x = "-1";'    => '-1', 
'$x = "foo";'   => 'foo', 
'$x = "true";'  => 'true', 
'$x = "false";' => 'false', 
);echo '<h2>Empty() & Co, Special Values</h2>';echo '<table border="1" cellspacing="0" cellpadding="4">';echo '<tr bgcolor="#DDE2E6"><td id="cheatTd1_0_0">&nbsp;</td>';echo '<td id="cheatTd1_1_0">gettype()</td>';echo '<td id="cheatTd1_2_0">empty()</td>';echo '<td id="cheatTd1_3_0">is_null()</td>';echo '<td id="cheatTd1_4_0">isSet()</td>';echo '<td id="cheatTd1_5_0">(bool)</td>';echo '</tr>';reset($a);$i=1;while (list($key,$val) = each($a)) {echo '<tr>';echo '<td id="cheatTd1_0_' . $i . '" bgcolor="#DDE2E6">' . $key . '</td>';echo '<td id="cheatTd1_1_' . $i . '" onMouseOver="cheatOver(1, 1, ' . $i . ');" onMouseOut="cheatOut(1, 1, ' . $i . ');" >' . getType($val) . '</td>';echo '<td id="cheatTd1_2_' . $i . '" onMouseOver="cheatOver(1, 2, ' . $i . ');" onMouseOut="cheatOut(1, 2, ' . $i . ');" >' . boolToStr(empty($val)) . '</td>';echo '<td id="cheatTd1_3_' . $i . '" onMouseOver="cheatOver(1, 3, ' . $i . ');" onMouseOut="cheatOut(1, 3, ' . $i . ');" >' . boolToStr(is_null($val)) . '</td>';echo '<td id="cheatTd1_4_' . $i . '" onMouseOver="cheatOver(1, 4, ' . $i . ');" onMouseOut="cheatOut(1, 4, ' . $i . ');" >' . boolToStr(isSet($val)) . '</td>';echo '<td id="cheatTd1_5_' . $i . '" onMouseOver="cheatOver(1, 5, ' . $i . ');" onMouseOut="cheatOut(1, 5, ' . $i . ');" >' . boolToStr((bool)$val) . '</td>';echo "</tr>\n";$i++;}
echo '</table><br><br>';$a = array(
'true'   =>true, 
'false'  =>false, 
'1'      =>1, 
'0'      =>0, 
'-1'     =>-1, 
'"1"'    =>"1", 
'"0"'    =>"0", 
'"-1"'   =>"-1", 
'array()'=>array(), 
'"foo"'  =>"foo", 
'""'     =>"", 
);$b = $a;reset($a);echo '<h2>comparison with ==</h2>';echo '<table border="1" cellspacing="0" cellpadding="4">';echo '<tr><td bgcolor="#DDE2E6" id="cheatTd2_0_0">&nbsp;</td>';$i=1;while (list($key) = each($a)) {echo '<td bgcolor="#DDE2E6" id="cheatTd2_' . $i . '_0">' . $key . '</td>';$i++;}
echo '</tr>';reset($a);$i=1;while (list($key,$val) = each($a)) {echo '<tr>';echo '<td id="cheatTd2_0_' . $i . '" bgcolor="#DDE2E6">' . $key . '</td>';reset($b);$j=1;while (list(,$val2) = each($b)) {echo '<td id="cheatTd2_' . $j . '_' . $i . '" onMouseOver="cheatOver(2, ' . $j . ', ' . $i . ');" onMouseOut="cheatOut(2, ' . $j . ', ' . $i . ');">' . boolToStr(($val == $val2)) . '</td>';$j++;}
echo "</tr>\n";$i++;}
echo '</table><br><br>';reset($a);echo '<h2>comparison with ===</h2>';echo '<table border="1" cellspacing="0" cellpadding="4">';echo '<tr><td bgcolor="#DDE2E6" id="cheatTd3_0_0">&nbsp;</td>';$i=1;while (list($key) = each($a)) {echo '<td bgcolor="#DDE2E6" id="cheatTd3_' . $i . '_0">' . $key . '</td>';$i++;}
echo '</tr>';reset($a);$i=1;while (list($key,$val) = each($a)) {echo '<tr>';echo '<td id="cheatTd3_0_' . $i . '" bgcolor="#DDE2E6">' . $key . '</td>';reset($b);$j=1;while (list(,$val2) = each($b)) {echo '<td id="cheatTd3_' . $j . '_' . $i . '" onMouseOver="cheatOver(3, ' . $j . ', ' . $i . ');" onMouseOut="cheatOut(3, ' . $j . ', ' . $i . ');">' . boolToStr(($val === $val2)) . '</td>';$j++;}
echo "</tr>\n";$i++;}
echo '</table><br><br>';?>