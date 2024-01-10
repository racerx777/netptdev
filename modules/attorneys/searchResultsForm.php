<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
$numRows = 0;
?>
<!-- Loader Css -->
<style>
	.loader {
		border: 8px solid #f3f3f3;
		border-radius: 50%;
		border-top: 8px solid #4682B4;
		width: 60px;
		height: 60px;
		-webkit-animation: spin 2s linear infinite; /* Safari */
		animation: spin 2s linear infinite;
		display:none;
		/* margin:300px 0px 500px 53rem;
		 */
margin:auto;
		/* position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		background: url('//upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Phi_fenomeni.gif/50px-Phi_fenomeni.gif') 50% 50% no-repeat rgb(249, 249, 249); */

	}

	/* Safari */
	@-webkit-keyframes spin {
		0% {
			-webkit-transform: rotate(0deg);
		}

		100% {
			-webkit-transform: rotate(360deg);
		}
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}
</style>
<fieldset id='searchField'>
	<legend style="font-size:large;">Search Results</legend>
	<?php
	if ($numRows > 0) {
		echo "<span id='records-no'>" . $numRows . "</span> Record(s) found.";
	}
	?>
	<form method="post" name="searchResults">
		<div class="loader"></div>
		<div id="append-table"></div>
	</form>
</fieldset>

<style type="text/css">
	.page-item {
		float: left;
		margin-right: 10px;
		list-style: none;
	}

	.pagination {
		padding: 0;
		float: right;
	}
</style>