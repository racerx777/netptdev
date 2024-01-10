<div class="heading2">Body Part <?php echo $bodypart; ?></div>
<div class="note">This is the note that can be added to the <?php echo $bodypart; ?> section.</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/reportmanageradm/templates/objective/'.$bodypart.'/arom/index.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/reportmanageradm/templates/objective/'.$bodypart.'/prom/index.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/reportmanageradm/templates/objective/'.$bodypart.'/muscletesting/index.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/reportmanageradm/templates/objective/'.$bodypart.'/specialtests/index.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/reportmanageradm/templates/objective/'.$bodypart.'/jointmobility/index.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/reportmanageradm/templates/objective/'.$bodypart.'/myotomes/index.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/reportmanageradm/templates/objective/'.$bodypart.'/dermatomes/index.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/reportmanageradm/templates/objective/'.$bodypart.'/reflexes/index.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/modules/reportmanageradm/templates/objective/'.$bodypart.'/palpation/index.php');
?>