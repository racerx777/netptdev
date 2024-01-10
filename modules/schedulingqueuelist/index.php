<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php require_once('scheduledqueuelist.php'); ?>