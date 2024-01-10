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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel='stylesheet' href='<?php echo $fileManagerSettings['imgPath'];?>fileManager.css'>
<style type="text/css">
.inputField {width: 220px;}
</style>
</head>
<body>
<form action="<?php echo $PHP_SELF;?>?showPage=file" method="post" target="frameFile">
<input type="hidden" name="task" value="search">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="smallBold"><img src="<?php echo $fileManagerSettings['imgPath'];?>search.gif" width="14" height="15" border="0" align="texttop" alt="">&nbsp;&nbsp;<?php echo $langHash['search']['title'];?></td>
</tr>
<tr>
<td><hr size="2" color="menu"></td>
</tr>
<tr>
<td><?php echo $langHash['search']['named'];?>:</td>
</tr>
<tr>
<td><input type="text" name="containingName" class="inputField"></td>
</tr>
<!--
<tr>
<td><?php echo $langHash['search']['containing'];?>:</td>
</tr>
<tr>
<td><input type="text" name="containingText" class="inputField"></td>
</tr>
-->
<tr>
<td><?php echo $langHash['search']['lookIn'];?>:</td>
</tr>
<tr>
<td><input type="text" name="path" class="inputField"></td>
</tr>
<tr>
<td>
<input type="submit" name="submit" value="<?php echo $langHash['search']['searchNow'];?>" class="button">&nbsp;&nbsp;&nbsp;<input type="reset"  name="reset"  value="<?php echo $langHash['search']['newSearch'];?>" class="button">
</td>
</tr>
<tr>
<td>
<input type=checkbox name="subfolder" value="1" checked> <?php echo $langHash['search']['subfolders'];?><br>
</td>
</tr>
</table>
</form>
</body>
</html>
