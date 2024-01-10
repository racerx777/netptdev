<style>
.DetailSubHeading {
	font-size:large;
}
.Paragraph {
	text-indent:10px;
}
</style>
<div class="DetailSubHeading">Medical Conditions</div>
<div class="Paragraph">This is the note that can be added to the Medical Conditions section.</div>
<?php
require_once("$templates/subjective/medicalhistory/medicalconditions/condition/index.php");
require_once("$templates/subjective/medicalhistory/medicalconditions/surgeries/index.php");
require_once("$templates/subjective/medicalhistory/medicalconditions/medications/index.php");
?>