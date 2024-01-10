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
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_IndexServer.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_WebSearchEngine.class.php');bs_lazyLoadClass('html/Bs_HtmlUtil.class.php');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Unbenannt</title>
</head>
<body>
<form name="search" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
<?php
$sql = "SELECT caption FROM bs_is_indexes";$profiles = $bsDb->getCol($sql);echo "<select name='profile'>";foreach ($profiles as $profile) {echo "<option>{$profile}</option>";}
echo "</select>";?>
Search for: <input type="text" name="query" value="<?php if (!empty($_POST['query'])) echo $Bs_HtmlUtil->filterForHtml($_POST['query']);?>"> <input type="submit" name="submit" value="Search">
</form>
<?php
if (!empty($_POST['query'])) {$is =& new Bs_IndexServer();$wse =& new Bs_Is_WebSearchEngine();$wse->init($_REQUEST['profile']);echo $wse->search($_POST['query']); }
?>
</body>
</html>
