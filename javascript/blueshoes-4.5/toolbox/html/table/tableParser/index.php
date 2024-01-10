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
require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");require_once($APP['path']['core'] . 'util/Bs_StopWatch.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core'] . 'html/table/Bs_HtmlTableParser.class.php');$sampleData =<<<EOD
<!-- Sample XHTML Table code (table in table) -->
<TABLE CELLSPACING="0" CELLPADDING="0" BORDER="8" BORDERCOLOR="red">
  <TR>
  <TD BGCOLOR="black">
    <TABLE BORDER="0" CELLPADDING="2" CELLSPACING="1">
      <TR ALIGN="right">
        <TD CLASS="header" COLSPAN="3"><SPAN STYLE="color: red; font: italic 18pt sans-serif;">apple<BR/>pie</SPAN></TD>
      </TR>
      <TR>
        <TD WIDTH="100" BGCOLOR="#cccccc" STYLE="font: bold 9pt monospace;">grape</TD>
        <TD WIDTH="100" BGCOLOR="#cccccc" ><SPAN STYLE="font: 18pt monospace;">orange</SPAN></TD>
        <TD WIDTH="100" BGCOLOR="#cccccc" ROWSPAN="2" STYLE="background: blue"><SPAN STYLE="background: red">pear</SPAN></TD>
      </TR>
      <TR>
        <TD ALIGN="right" bgcolor="blue" COLSPAN="2"><SPAN STYLE="color: white; font: bold 18pt serif;">plum</SPAN></TD>
      </TR>
    </TABLE>
  </TD></TR>
</TABLE>
EOD;
$stopWatch = $GLOBALS['Bs_StopWatch'];$stopWatch->reset();$phpCode = '';$tblParser = new Bs_HtmlTableParser();$htmlTableCode = $sampleData;$action = $_SERVER['PHP_SELF'];if (isSet($input)) {$data = $GLOBALS['Bs_HtmlUtil']->htmlEntitiesUndo($input['data']);$data = stripslashes($data);$htmlTableCode = htmlEntities($data);$tblParser->parse($data);$phpCode = '<'."?php\n";$phpCode .= "require_once(\$_SERVER['DOCUMENT_ROOT'] . '../global.conf.php'); \n";$phpCode .= "require_once(\$APP['path']['core'] . 'html/Bs_HtmlTable.class.php'); \n\n";$phpCode .=  $tblParser->generateCode() . "\n?".'>';ob_start();highlight_string($phpCode);$phpCode = ob_get_contents();$phpCodeTable = <<< EOD
          <table width="100%" border="1" bgcolor="#F0F0F0">
            <tr><td><SPAN STYLE="font-size:16px; font-family:arial">The Code for sample above could look like this:</SPAN>
            </td></tr>
            <tr><td>
              <SPAN STYLE="font-size:11px;">
              {$phpCode}
              </SPAN>
            </td></tr>
          </TABLE>
EOD;
ob_end_clean();}
$stopWatch->takeTime('Time');$html = <<< EOD
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
    <head>
        <title>Sample of Bs_HtmlTableWindrose.class.php use</title>
      <style>
      .bsHead {
        font-family: arial;
        color:white;
      }
      </style>
    </head>
      <body bgcolor="#c0d0d0">
        
        <table width="100%" height="50" border="0" cellspacing="2" cellpadding="0" align="center" bgcolor="#182842">
          <tr>
            <td valign="middle"><h2 style="margin:0px; padding:0px;" class="bsHead">HTML Table Parser</h2></td>
            <td valign="middle" aligh="right"><a href="http://www.blueshoes.org/" target="_blank" class="bsHead"><font color="white">BlueShoes PHP Framework - blueshoes.org</font></a></td>
          </tr>
        </table>
        <br><br>
        
        <SPAN STYLE="font-family:Arial">
          Parses well formed <b>XHTML</b> TABLE (incl. cascaded TABLEs) and generates 
          the PHP-code unsing Bs_HtmlTable.class.php.<br>
          A very good utility if you already have the HTML Table code (or youre 
          using a HTML Table generator) and want to make a Bs_HtmlTable.class.php 
          of it.<br>
          <strong>NOTE:</strong>
          <ul compact>
          <li>Use XHTML (instead of normal HTML). HomeSite can transform HTML to XHTML with the built in CodeSweeper</li>
          <li>Only one HTML table can be parsed, but it may have any number of cascading HTML-Tables.</li>
          </ul>
        </SPAN>
        <CENTER>
          <form action="{$action}" method="post">  
            <input type="hidden" name="input[todo]" value="readForm">
            <textarea cols="100" rows="20" name="input[data]">{$htmlTableCode}</textarea>
            <input type="submit" name="input[submit]" value="parse">
          </form>
          {$phpCodeTable}
        </CENTER>
      {$time}
    </body>
    </html>
EOD;
echo $html;?>