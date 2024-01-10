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
.pathInput {background-color:    white;border:              "1 solid";border-top-color:    "#505050";border-left-color:   "#505050";border-right-color:  "#EFEFEF";border-bottom-color: "#EFEFEF";height: 22;}
.dropdownInput {width:500;}
.dropdownSelect {width:520;}
body {font-family: tahoma, verdana, arial, helvetica;font-size: 10pt;color: #000000;background-color:#D4D0C8;border-width:0px;}
</style>
<script type="text/javascript" src='<?php echo $fileManagerSettings['jsBasePath'];?>core/lang/Bs_Misc.lib.js'></script>
<script type="text/javascript" src="<?php echo $fileManagerSettings['jsBasePath'];?>components/dropdown/Bs_Dropdown.class.js"></script>
<script type="text/javascript" src="<?php echo $fileManagerSettings['jsBasePath'];?>core/form/Bs_FormUtil.lib.js"></script>
<script type="text/javascript" src="<?php echo $fileManagerSettings['jsBasePath'];?>core/form/Bs_FormFieldSelect.class.js"></script>
<script type="text/javascript" src="<?php echo $fileManagerSettings['jsBasePath'];?>core/lang/Bs_Array.class.js"></script>
<script>
function bs_isGecko() {//rather poor implementation.
if (navigator.appName == "Microsoft Internet Explorer") return false;return (navigator.userAgent.match(/gecko/i));}
if (bs_isGecko()) {document.writeln("<link rel='stylesheet' href='<?php echo $fileManagerSettings['jsBasePath'];?>components/toolbar/win2k_mz.css'>");} else {document.writeln("<link rel='stylesheet' href='<?php echo $fileManagerSettings['jsBasePath'];?>components/toolbar/win2k_ie.css'>");}
</script>
<script language="JavaScript" src="<?php echo $fileManagerSettings['jsBasePath'];?>components/toolbar/Bs_ButtonBar.class.js" type="text/javascript"></script>
<script language="JavaScript" src="<?php echo $fileManagerSettings['jsBasePath'];?>components/toolbar/Bs_Button.class.js" type="text/javascript"></script>
<script type="text/javascript">
var basePath = "<?php echo $basePath;?>";var fullPath = "<?php echo $path;?>";var virtualClipboard = new Array(); //for cut/paste operations
function buttonGoHome() {setCwd('/');mainGoTo();}
function buttonDoRefresh() {parent.location.href = "<?php echo $_SERVER['PHP_SELF'];?>";}
function buttonDoEdit() {if (parent.fileSelected["0"]) {parent.frameFile.editFile(parent.fileSelected["0"]);}
//alert('button not implemented. right-click on a file to do that.');}
function buttonNewFolder() {var folderName = prompt("<?php echo $langHash['misc']['askFolderName'];?>", "<?php echo $langHash['misc']['newFolder'];?>"); //What's the name for the new folder?       //New Folder
if ((folderName != "") && (folderName != null)) {parent.frameCommand.location.href = encodeURI(parent.frameFile.php_self + "?showPage=command&do=createFolder&path=" + parent.frameFile.relPath + "&folderName=" + folderName);}
}
function buttonNewFile() {var fileName = prompt("<?php echo $langHash['misc']['askFileName'];?>", "<?php echo $langHash['misc']['newfiletxt'];?>"); //What's the name for the new file?    //newfile.txt
if ((fileName != "") && (fileName != null)) {parent.frameCommand.location.href = encodeURI(parent.frameFile.php_self + "?showPage=command&do=createFile&path=" + parent.frameFile.relPath + "&fileName=" + fileName);}
}
function initToolbar() {bar = new Bs_ButtonBar();bar.useHelpBar = true;bar.imgPath = '<?php echo $fileManagerSettings['imgBasePath'];?>buttons/';btnHome = new Bs_Button();btnHome.objectName = 'btnHome';btnHome.title = "<?php echo $langHash['buttons']['homeTitle'];?>"; //'Home';btnHome.imgName = 'bs_home';btnHome.attachEvent(buttonGoHome);bar.addButton(btnHome, "<?php echo $langHash['buttons']['homeDesc'];?>"); //'Go back to the start.');btnRefresh = new Bs_Button();btnRefresh.objectName = 'btnRefresh';btnRefresh.title = "<?php echo $langHash['buttons']['refreshTitle'];?>"; //'Refresh';btnRefresh.imgName = 'bs_refresh';btnRefresh.attachEvent(buttonDoRefresh);bar.addButton(btnRefresh, "<?php echo $langHash['buttons']['refreshDesc'];?>"); //'Refresh the whole file manager.');btnGoDirUp = new Bs_Button();btnGoDirUp.objectName = 'btnGoDirUp';btnGoDirUp.title = "<?php echo $langHash['buttons']['goDirUpTitle'];?>"; //'Up';btnGoDirUp.imgName = 'bs_goDirUp';btnGoDirUp.attachEvent(goUp);bar.addButton(btnGoDirUp, "<?php echo $langHash['buttons']['goDirUpDesc'];?>"); //'Go one dir up.');bar.newGroup();btnFolders = new Bs_Button();btnFolders.objectName = 'btnFolders';btnFolders.group      = 'leftContent';btnFolders.title      = "<?php echo $langHash['buttons']['foldersTitle'];?>"; //'Folders';btnFolders.imgName    = 'bs_folder';btnFolders.setStatus(2);btnFolders.attachEvent(loadDir);bar.addButton(btnFolders, "<?php echo $langHash['buttons']['foldersDesc'];?>"); //'Show the folders view.');btnSearch = new Bs_Button();btnSearch.objectName = 'btnSearch';btnSearch.group      = 'leftContent';btnSearch.title      = "<?php echo $langHash['buttons']['searchTitle'];?>"; //'Search';btnSearch.imgName    = 'bs_fileManagerSearch';btnSearch.attachEvent(loadSearch);bar.addButton(btnSearch, "<?php echo $langHash['buttons']['searchDesc'];?>"); //'Show the search view.');bar.newGroup();btnDelete = new Bs_Button();btnDelete.objectName = 'btnDelete';btnDelete.title      = "<?php echo $langHash['buttons']['deleteTitle'];?>"; //'Delete';btnDelete.imgName    = 'bs_delete';btnDelete.setStatus(0);btnDelete.attachEvent("parent.frameFile.deleteFile();");bar.addButton(btnDelete, "<?php echo $langHash['buttons']['deleteDesc'];?>"); //'Delete the selected files/folders.');btnCut = new Bs_Button();btnCut.objectName = 'btnCut';btnCut.title      = "<?php echo $langHash['buttons']['cutTitle'];?>"; //'Cut';btnCut.imgName    = 'bs_cut';btnCut.setStatus(0);btnCut.attachEvent("parent.frameFile.cut();");bar.addButton(btnCut, "<?php echo $langHash['buttons']['cutDesc'];?>"); //'Cut the selected files/folders into the clipboard.');btnCopy = new Bs_Button();btnCopy.objectName = 'btnCopy';btnCopy.title      = "<?php echo $langHash['buttons']['copyTitle'];?>"; //'Copy';btnCopy.imgName    = 'bs_copy';btnCopy.setStatus(0);btnCopy.attachEvent("parent.frameFile.copy();");bar.addButton(btnCopy, "<?php echo $langHash['buttons']['copyDesc'];?>"); //'Copy the selected files/folders into the clipboard.');btnPaste = new Bs_Button();btnPaste.objectName = 'btnPaste';btnPaste.title      = "<?php echo $langHash['buttons']['pasteTitle'];?>"; //'Paste';btnPaste.imgName    = 'bs_paste';//btnPaste.setStatus(0);btnPaste.attachEvent("parent.frameFile.paste();");bar.addButton(btnPaste, "<?php echo $langHash['buttons']['pasteDesc'];?>"); //'Paste the files/folders from the clipboard here.');bar.newGroup();btnNewFolder = new Bs_Button();btnNewFolder.objectName = 'btnNewFolder';btnNewFolder.title      = "<?php echo $langHash['buttons']['newFolderTitle'];?>"; //'New Folder';btnNewFolder.imgName    = 'bs_folder2';btnNewFolder.attachEvent(buttonNewFolder);bar.addButton(btnNewFolder, "<?php echo $langHash['buttons']['newFolderDesc'];?>"); //'Create a new folder in the current folder.');btnNewFile = new Bs_Button();btnNewFile.objectName = 'btnNewFile';btnNewFile.title      = "<?php echo $langHash['buttons']['newFileTitle'];?>"; //'New File';btnNewFile.imgName    = 'bs_textFile';btnNewFile.attachEvent(buttonNewFile);bar.addButton(btnNewFile, "<?php echo $langHash['buttons']['newFileDesc'];?>"); //'Create a new folder in the current folder.');btnEdit = new Bs_Button();btnEdit.objectName = 'btnEdit';btnEdit.title = "<?php echo $langHash['buttons']['editTitle'];?>"; //'Edit';btnEdit.imgName = 'bs_webEdit';btnEdit.attachEvent(buttonDoEdit);btnEdit.setStatus(0);bar.addButton(btnEdit, "<?php echo $langHash['buttons']['editDesc'];?>"); //'Edit the content of the selected file.');bar.drawInto('buttonBar');}
function enableButtonsForFiles() {btnEdit.setStatus(1);btnDelete.setStatus(1);btnCut.setStatus(1);btnCopy.setStatus(1);//btnPaste.setStatus(1);}
function disableButtonsForFiles(){btnEdit.setStatus(0);btnDelete.setStatus(0);btnCut.setStatus(0);btnCopy.setStatus(0);//btnPaste.setStatus(0);}
function initCombobox() {cwdDropdown = new Bs_Dropdown();cwdDropdown.imgDir = '<?php echo $fileManagerSettings['jsBasePath'];?>components/dropdown/img/win2k/';cwdDropdown.objectName  = 'cwdDropdown';cwdDropdown.classInput  = 'dropdownInput';cwdDropdown.classSelect = 'dropdownSelect';cwdDropdown.drawInto('cwdShow');cwdDropdown.attachEvent('onChange', mainGoTo);}
function init() {initCombobox();initToolbar();}
/**
* 'goto' is a reserved identifier in mozilla. had to change it. --andrej
*/
function mainGoTo() {parent.frameFile.location.href = "<?php echo $_SERVER['PHP_SELF'];?>?showPage=file&fullPath=" + getCwd();}
function goUp() {parent.frameFile.location.href = "<?php echo $_SERVER['PHP_SELF'];?>?showPage=file&fullPath=" + getCwd() + '../';}
function inputCheckKey() {if (window.event.keyCode == 13) {mainGoTo();event.returnValue=false;}
}
function loadSearch() {//parent.framesetLeft.cols = "16,*";//alert(parent.framesetLeft.cols);//<frameset cols="300,*" ID="framesetLeft">
parent.framesetLeft.cols = "250,*";parent.frameLeft.location.href = "<?php echo $_SERVER['PHP_SELF'];?>?showPage=searchForm";}
function loadDir() {parent.framesetLeft.cols = "300,*";parent.frameLeft.location.href = "<?php echo $_SERVER['PHP_SELF'];?>?showPage=dir";}
/**
* updates the current working dir. does not touch other frames.
* @param string cwd
*/
function setCwd(cwd) {//alert('setting to: ' + cwd); //4debug
if (<?php echo boolToString($fileManagerSettings['showRelative']);?>) {cwd = '/' + cwd.substr(basePath.length);}
cwdDropdown.setValue(cwd);}
function getCwd() {if (<?php echo boolToString($fileManagerSettings['showRelative']);?>) {return basePath + cwdDropdown.getValue().substr(1);} else {return cwdDropdown.getValue();}
}
</script>
</head>
<body leftmargin=0 topmargin=1 bgcolor="#D4D0C8" onLoad="init();">
<table border=0 cellpadding=0 cellspacing=0>
<tr>
<td>&nbsp;</td>
<td colspan="100%" height="26"><div id="buttonBar"></div></td>
</tr>
<tr><td colspan="100%" height="1" bgcolor="#848284"></td></tr>
<tr><td colspan="100%" height="1" bgcolor="white"></td></tr>
<tr>
<td>&nbsp;</td>
<td align="left" width="40" height="24"><?php echo $langHash['misc']['path'];?>: </td>
<td align="left"><div id="cwdShow"></div></td>
<td align="right" width="47"><img src="<?php echo $fileManagerSettings['imgPath'];?>goto.gif" width="43" height="22" border="0" align="middle" alt="Goto" onClick="mainGoTo();"></td>
</tr>
<tr><td colspan="100%" height="1" bgcolor="#848284"></td></tr>
<tr><td colspan="100%" height="1" bgcolor="white"></td></tr>
</table>
</body>
</html>