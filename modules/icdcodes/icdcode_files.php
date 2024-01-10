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
    /* Style for the pagination container */
.pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
}

/* Style for individual page items */
.page-item {
    margin: 0 5px;
}

/* Style for the "Previous" and "Next" links */
.page-item.disabled a,
.page-item.disabled a:hover {
    color: #999;
    pointer-events: none;
    cursor: not-allowed;
}
/* Style for the active page */
.page-item.active .page-link {
    background-color: #007bff; /* Change to the desired color, e.g., blue */
    color: #fff; /* Text color on the active page */
    pointer-events: none;
    cursor: default;
}

.page-link {
    display: block;
    padding: 10px 15px;
    background-color: #f2f2f2;
    border: 1px solid #ccc;
    text-align: center;
    text-decoration: none;
    color: #333;
    border-radius: 5px;
    transition: background-color 0.2s, color 0.2s;
}

/* Hover styles for the links */
.page-link:hover {
    background-color: #333;
    color: #fff;
}

/* Style for the active page */
.page-item.active .page-link {
    background-color: #333;
    color: #fff;
    pointer-events: none;
    cursor: default;
}

	.loader {
		border: 8px solid #f3f3f3;
		border-radius: 50%;
		border-top: 8px solid #4682B4;
		width: 60px;
		height: 60px;
		-webkit-animation: spin 2s linear infinite; /* Safari */
		animation: spin 2s linear infinite;
		/* margin-left: 49rem; */
		display:none;
    margin:300px 0px 500px 49rem;
	}

	/* Safari */
	@-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
</style>
<fieldset id='searchField'>
<legend style="font-size:large;">Search Results</legend>
<?php
	if($numRows>0) {
		echo "<span id='records-no'>".$numRows . "</span> Record(s) found.";
	}
?>
<!-- <span id='records-no'></span> Record(s) found. -->
<span id='records-no'> </span> ICD10 codes(s) found.

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
