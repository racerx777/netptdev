<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
if(isset($_GET['clear'])) {
	unlink('new patients.esd.csv');
	exit();
}
if(isset($_POST['Upload'])) {
	$target_path = "/home/wsptn/public_html/development/conversion/";
	$target_path = $target_path . basename( $_FILES['file']['name']); 
	if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
    	echo "The file ".  basename( $_FILES['file']['name']). " has been uploaded";
	}
	else 
    	echo "There was an error uploading the file $target_path, please try again!";
}
else {
?>
Upload CSV.
<form name="upload" method="post" enctype="multipart/form-data">
Filename:
<input type="file" name="file" />
<input type="submit" name="Upload" value="Upload" />
</form>
<?php
}
?>
</body>
</html>