<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
?>
<?php
// print_r($_POST);
if (isset($_POST['newInsert'])) {
	$query = mysqli_query($dbhandle, 'SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id ORDER BY id DESC LIMIT 10');
	$result = array();
	while ($row = mysqli_fetch_assoc($query)) {
		// $result['row'][] = $row;
		$querybyid = "SELECT * FROM cases WHERE raid='" . $row['id'] . "'";
		$check = mysqli_query($dbhandle, $querybyid);
		if (mysqli_num_rows($check) == 0) {
			$btn_txt = 'Delete';
		} else {
			$btn_txt = 'Reactivate';
		}
		$row['btnTxt'] = $btn_txt;
		$result['row'][] = $row;
	}
	echo json_encode($result);
}



if (isset($_POST['showall'])) {
	if (isset($_POST['pageno'])) {
		$pageno = $_POST['pageno'];
	} else {
		$pageno = 1;
	}
	$no_of_records_per_page = 100;
	$offset = ($pageno - 1) * $no_of_records_per_page;
	$tmp = mysqli_query($dbhandle, 'SELECT COUNT(*) FROM attorney');
	$total_rows = mysqli_fetch_array($tmp)[0];
	$total_pages = ceil($total_rows / $no_of_records_per_page);
	$result = mysqli_query($dbhandle, 'SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id ORDER BY attorney_firm.firm_name ASC LIMIT ' . $offset . ',' . $no_of_records_per_page);
	?>


	<div style="height: 30px;float: right;width: 50px;">

		<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)"
			style="position: absolute;cursor: pointer;">&nbsp;&nbsp;<img src="/img/icon-xls.png"
			style="position: absolute;margin-left: 25px;cursor: pointer;" onClick="return printPDFXLS()">
	</div>
	<?php echo $total_rows; ?> Attorneys(s) found.
	<table border="1" id="attorney-table" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th>Firm Name</th>
			<th>First Name</th>
			<th>Middle Name</th>
			<th>Last Name</th>
			<th>Address</th>
			<th>Suite #</th>
			<th>city</th>
			<th>State</th>
			<th>Zip</th>
			<th>Phone</th>
			<th>Email</th>
			<th>Functions</th>
		</tr>
		<?php
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$query = "SELECT * FROM cases WHERE raid='" . $row['id'] . "'";
			$check = mysqli_query($dbhandle, $query);
			if (mysqli_num_rows($check) == 0) {
				$btn_txt = 'Delete';
			} else {
				$btn_txt = 'Reactivate';
			}
			?>
			<tr<?php echo $rowstyle; ?>>
				<td>
					<?php echo $row["firm_name"]; ?>&nbsp;
				</td>
				<td>
					<?php echo $row["name_first"]; ?>&nbsp;
				</td>
				<td>
					<?php echo $row["name_middle"]; ?>&nbsp;
				</td>
				<td>
					<?php echo $row["name_last"]; ?>&nbsp;
				</td>
				<td>
					<?php echo $row["address"]; ?>&nbsp;
				</td>
				<td>
					<?php echo $row["address2"]; ?>&nbsp;
				</td>
				<td>
					<?php echo $row["city"]; ?>&nbsp;
				</td>
				<td>
					<?php echo $row["state"]; ?>&nbsp;
				</td>
				<td>
					<?php echo $row["zip"]; ?>&nbsp;
				</td>
				<td>
					<?php echo $row["phone"]; ?>&nbsp;
				</td>
				<td>
					<?php echo $row["email"]; ?>&nbsp;
				</td>
				<input type="hidden" name="editid" value="<?php echo $row["id"] ?>">
				<td><input name="button[<?php echo $row["id"] ?>]" type="submit" value="Edit" />
					<input name="btn-delete" type="button" data-id="<?php echo $row["id"] ?>" class="btn-delete"
						value="<?php echo $btn_txt ?>" />
				</td>
				</tr>
			<?php
		}
		?>
	</table>
	<ul class="pagination">
		<li class="page-item disabled"><a class="page-link" data-pageid="<?php if ($pageno <= 1) {
			echo '#';
		} else {
			echo ($pageno - 1);
		} ?>" href="#">Previous</a></li>
		<?php
		$outOfRange = false;
		for ($i = 1; $i <= $total_pages; $i++) {

			if ($i <= 2 || $i >= $total_pages - 2 || abs($i - $pageno) <= 2) {

				// page number should be echoed so do as you did before
	
				$outOfRange = false;

				if ($i == $pageno) {
					echo '<li class="page-item"><a data-pageid="' . $i . '" href="#" class="page-link">' . $i . '</a></li>';
				} else {
					echo '<li class="page-item 132"><a data-pageid="' . $i . '" href="#" class="page-link">' . $i . '</a></li>';
				}
			} else {

				// if (!$outOfRange) {
				//     echo ' ... ';
				// }
	
				$outOfRange = true;

			}
		}



		// $show = 0;
		// 	for ($i = $pageno; $i <= $total_pages; $i++) {
		// 	  $show++;
		// 	  if ($page == $i) 
		// 	    echo '<li class="page-item"><a data-pageid="'.$i.'" href="#" class="page-link">'.$i.'</a></li>';
		// 	  else if (($show < 3) || ($total_pages == $i))
		// 	    echo '<li class="page-item"><a data-pageid="'.$i.'" href="#" class="page-link">'.$i.'</a></li>';
		// 	}
		?>
		<?php
		// for ($i=1; $i<=ceil($total_pages); $i++)
		// { 
		?>
		<!-- <li class="page-item"><a data-pageid="<?php echo $i; ?>" href="#" class="page-link"><?php echo $i; ?></a></li> -->
		<?php
		// }
		?>
		<li class="page-item"><a class="page-link" data-pageid="<?php if ($pageno >= $total_pages) {
			echo '#';
		} else {
			echo ($pageno + 1);
		} ?>" href="#">Next</a></li>
	</ul>
<?php
}



if (isset($_POST['main'])) {
	$sql = array();
	if (!empty($_POST['fname'])) {
		$sql[] = "attorney.name_first LIKE '%" . $_POST['fname'] . "%'";
	}
	if (!empty($_POST['lname'])) {
		$sql[] = "attorney.name_last LIKE '%" . $_POST['lname'] . "%'";
	}
	if (!empty($_POST['city'])) {
		$sql[] = "attorney.city LIKE '%" . $_POST['city'] . "%'";
	}
	if (!empty($_POST['zip'])) {
		$sql[] = "attorney.zip LIKE '%" . $_POST['zip'] . "%'";
	}
	if (!empty($_POST['firmname'])) {
		$sql[] = "attorney_firm.firm_name LIKE '%" . $_POST['firmname'] . "%'";
	}

	if (isset($_POST['pageno'])) {
		$pageno = $_POST['pageno'];
	} else {
		$pageno = 1;
	}


	if (isset($_POST['sorting'])) {
		$sorting = $_POST['sorting'];
	} else {
		$sorting = "ASC";
	}
	if (isset($_POST['allValue'])) {
		$allValue = $_POST['allValue'];
	} else {
		$allValue = "firmName";
	}

	
	$count = 0;
	$query = "";
	$table_arr = array();
	if (!empty($sql)) {
		foreach ($sql as $keyword) {
			if ($count == 0) {
				$query .= $keyword;
			} else {
				$query .= ' AND ' . $keyword;
			}
			$count++;
		}



		// $no_of_records_per_page = 100;
		// $offset = ($pageno - 1) * $no_of_records_per_page;
		// $tmp = mysqli_query($dbhandle, 'SELECT COUNT(*) FROM attorney');
		// $total_rows = mysqli_fetch_array($tmp)[0];
		// $total_pages = ceil($total_rows / $no_of_records_per_page);


		$no_of_records_per_page = 100;
		$offset = ($pageno - 1) * $no_of_records_per_page;
		$tmp = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id WHERE $query";
		$newResult = mysqli_query($dbhandle, $tmp);
		$total_rows = mysqli_num_rows($newResult);
		$total_pages = ceil($total_rows / $no_of_records_per_page);
		// $total_rows = mysqli_fetch_array($tmp)[0];
		// $total_pages = ceil($total_rows / $no_of_records_per_page);

		// echo json_encode($rowsCount);
		// $total_rows = mysqli_fetch_array($tmp)[0];
		// $total_pages = ceil($total_rows / $no_of_records_per_page);

		$newPrepare = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id WHERE $query";

		if (isset($_POST['query'])) {
			$sortingBy = $_POST['sortingBy'];
			if ($sorting != "" && $sorting == "ASC") {
				$prepare = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id WHERE $query ORDER BY $sortingBy ASC LIMIT $offset ,  $no_of_records_per_page";
			}if($sorting != "" && $sorting == "DSCE"){
				$prepare = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id WHERE $query ORDER BY $sortingBy DESC LIMIT $offset ,  $no_of_records_per_page";
			}
			// $prepare = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id WHERE $query ORDER BY $sortingBy ASC LIMIT $offset ,  $no_of_records_per_page";
		} else {
			$prepare = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id WHERE $query ORDER BY attorney_firm.firm_name ASC LIMIT $offset ,  $no_of_records_per_page";
			$_POST['ascDesc'] = "firmName";

		}

	}
	$result = mysqli_query($dbhandle, $prepare);

	?>


	<div style="height: 30px;float: right;width: 50px;">

		<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)"
			style="position: absolute;cursor: pointer;">&nbsp;&nbsp;<img src="/img/icon-xls.png"
			style="position: absolute;margin-left: 25px;cursor: pointer;" onClick="return printPDFXLS()">
	</div>
	<input type="hidden" value="<?php echo $newPrepare; ?>" id="prepareQuery" />

	<input type="hidden" value="<?php echo $pageno; ?>" id="pagenoss" />

	<input type="hidden" value="<?php echo $sorting; ?>" id="sorting" />
	<input type="hidden" value="<?php echo $total_rows; ?>" id="total_rows" />




	

	<!-- <i class="fa fa-sort-desc" aria-hidden="true"></i> -->
	<?php echo $total_rows; ?> Attorneys(s) found.
	<table border="1" id="attorney-table" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th id="firmName" style="cursor: pointer;">Firm Name
				<?php if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "firmName" && $sorting == "ASC") {
					echo
						"<i class='fa fa-sort-asc' aria-hidden='true'></i>";
				} 
				
				if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "firmName" && $sorting == "DSCE") {
					echo
						"<i class='fa fa-sort-desc' aria-hidden='true'></i>";
				}
				?>
			</th>
			<th id="firstName" style="cursor: pointer;">First Name
				<?php if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "firstName" && $sorting == "ASC") {
					echo
						"<i class='fa fa-sort-asc' aria-hidden='true'></i>";
				} 

				if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "firstName" && $sorting == "DSCE") {
					echo
						"<i class='fa fa-sort-desc' aria-hidden='true'></i>";
				} 
				
				
				?>
			</th>
			<th style="cursor: pointer;" id="middleName">Middle Name
				<?php if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "middleName" && $sorting == "ASC") {
					echo
						"<i class='fa fa-sort-asc' aria-hidden='true'></i>";
				} 
				
				if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "middleName" && $sorting == "DSCE") {
					echo
						"<i class='fa fa-sort-desc' aria-hidden='true'></i>";
				} 
				
				?>
			</th>
			<th style="cursor: pointer;" id="lastName">Last Name
				<?php if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "lastName" && $sorting == "ASC") {
					echo
						"<i class='fa fa-sort-asc' aria-hidden='true'></i>";
				}
				
				if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "lastName" && $sorting == "DSCE") {
					echo
						"<i class='fa fa-sort-desc' aria-hidden='true'></i>";
				}
				?>
			</th>
			<th style="cursor: pointer;" id="address">Address
				<?php if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "Address" && $sorting == "ASC") {
					echo
						"<i class='fa fa-sort-asc' aria-hidden='true'></i>";
				}

				if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "Address" && $sorting == "DSCE") {
					echo
						"<i class='fa fa-sort-desc' aria-hidden='true'></i>";
				}
				
				?>
			</th>
			<th>Suite #</th>
			<th style="cursor: pointer;" id="city">city
				<?php if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "city" && $sorting == "ASC") {
					echo
						"<i class='fa fa-sort-asc' aria-hidden='true'></i>";
				}
				
				if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "city" && $sorting == "DSCE") {
					echo
						"<i class='fa fa-sort-desc' aria-hidden='true'></i>";
				}
				
				?>
			</th>
			<th style="cursor: pointer;" id="state"> State
				<?php if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "state" && $sorting == "ASC") {
					echo
						"<i class='fa fa-sort-asc' aria-hidden='true'></i>";
				}
				
				
				if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "state" && $sorting == "DSCE") {
					echo
						"<i class='fa fa-sort-desc' aria-hidden='true'></i>";
				}
				?>
			</th>
			<th style="cursor: pointer;" id="zip">Zip
				<?php if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "zip" && $sorting == "ASC") {
					echo
						"<i class='fa fa-sort-asc' aria-hidden='true'></i>";
				} 
				
				if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "zip" && $sorting == "DSCE") {
					echo
						"<i class='fa fa-sort-desc' aria-hidden='true'></i>";
				}
				
				?>
			</th>
			<th style="cursor: pointer;" id="phone">Phone
				<?php if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "phone" && $sorting == "ASC") {
					echo
						"<i class='fa fa-sort-asc' aria-hidden='true'></i>";
				}
				
				if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "phone" && $sorting == "DSCE") {
					echo
						"<i class='fa fa-sort-desc' aria-hidden='true'></i>";
				}
				?>
			</th>
			<th style="cursor: pointer;" id="email">Email
				<?php if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "email" && $sorting == "ASC") {
					echo
						"<i class='fa fa-sort-asc' aria-hidden='true'></i>";
				}
				
				if (isset($_POST['ascDesc']) && $_POST['ascDesc'] == "email" && $sorting == "DSCE") {
					echo
						"<i class='fa fa-sort-desc' aria-hidden='true'></i>";
				}
				?>
			</th>
			<th>Functions</th>
		</tr>

		<?php



		if ($result) {
			if (mysqli_num_rows($result) == 0) {
				$table_arr['numRows'] = 0;
			} else {
				while ($row = mysqli_fetch_assoc($result)) {
					$query = "SELECT * FROM cases WHERE raid='" . $row['id'] . "'";
					$check = mysqli_query($dbhandle, $query);
					if (mysqli_num_rows($check) == 0) {
						$btn_txt = 'Delete';
					} else {
						$btn_txt = 'Reactivate';
					}
					?>


					<tr<?php echo $rowstyle; ?>>
						<td>
							<?php echo $row["firm_name"]; ?>&nbsp;
						</td>
						<td>
							<?php echo $row["name_first"]; ?>&nbsp;
						</td>
						<td>
							<?php echo $row["name_middle"]; ?>&nbsp;
						</td>
						<td>
							<?php echo $row["name_last"]; ?>&nbsp;
						</td>
						<td>
							<?php echo $row["address"]; ?>&nbsp;
						</td>
						<td>
							<?php echo $row["address2"]; ?>&nbsp;
						</td>
						<td>
							<?php echo $row["city"]; ?>&nbsp;
						</td>
						<td>
							<?php echo $row["state"]; ?>&nbsp;
						</td>
						<td>
							<?php echo $row["zip"]; ?>&nbsp;
						</td>
						<td>
							<?php echo $row["phone"]; ?>&nbsp;
						</td>
						<td>
							<?php echo $row["email"]; ?>&nbsp;
						</td>
						<input type="hidden" name="editid" value="<?php echo $row["id"] ?>">
						<td><input name="button[<?php echo $row["id"] ?>]" type="submit" value="Edit" />
							<input name="btn-delete" type="button" data-id="<?php echo $row["id"] ?>" class="btn-delete"
								value="<?php echo $btn_txt ?>" />
						</td>
						</tr>



					<?php
				}
			}
			$table_arr['rowsCount'] = $rowsCount;

		} else {
			$table_arr['r'] = 0;
			// echo json_encode($table_arr);
		}

		?>
	</table>

	<ul class="pagination">

	<input type="hidden" value="<?php echo $allValue; ?>" id="allValue" />
	<input type="hidden" value="<?php echo $sorting; ?>" id="sortingsss" />

		<!-- <li class="page-item disabled"><a class="page-link-main" data-pageid="<//?php if ($pageno <= 1) {
				echo '#';
			} else {
				echo ($pageno - 1);
			} ?>" href="#">Previous</a></li> -->
		<?php
		$outOfRange = false;
		for ($i = 1; $i <= $total_pages; $i++) {
			if ($i <= 2 || $i >= $total_pages - 2 || abs($i - $pageno) <= 2) {
				$outOfRange = false;
				if ($i == $pageno) {
					echo '<li class="page-item"><a data-pageid="' . $i . '" href="#" class="page-link-main">' . $i . '</a></li>';
				} else {
					echo '<li class="page-item 132"><a data-pageid="' . $i . '" href="#" class="page-link-main">' . $i . '</a></li>';
				}
			} else {
				$outOfRange = true;
			}
		}
		?>
		<!-- <li class="page-item"><a class="page-link-main" data-pageid="<//?php if ($pageno >= $total_pages) {
				echo '#';
			} else {
				echo ($pageno + 1);
			} ?>" href="#">Next</a></li> -->
	</ul>








<?php } ?>


















<?php

if (isset($_POST['mainss'])) {
	$sql = array();
	if (!empty($_POST['fname'])) {
		$sql[] = "attorney.name_first LIKE '%" . $_POST['fname'] . "%'";
	}
	if (!empty($_POST['lname'])) {
		$sql[] = "attorney.name_last LIKE '%" . $_POST['lname'] . "%'";
	}
	if (!empty($_POST['city'])) {
		$sql[] = "attorney.city LIKE '%" . $_POST['city'] . "%'";
	}
	if (!empty($_POST['zip'])) {
		$sql[] = "attorney.zip LIKE '%" . $_POST['zip'] . "%'";
	}
	if (!empty($_POST['firmname'])) {
		$sql[] = "attorney_firm.firm_name LIKE '%" . $_POST['firmname'] . "%'";
	}




	if (isset($_POST['pageno'])) {
		$pageno = $_POST['pageno'];
	} else {
		$pageno = 1;
	}




	$count = 0;
	$query = "";
	$table_arr = array();
	if (!empty($sql)) {
		foreach ($sql as $keyword) {
			if ($count == 0) {
				$query .= $keyword;
			} else {
				$query .= ' AND ' . $keyword;
			}
			$count++;
		}


		$no_of_records_per_page = 50;
		$offset = ($pageno - 1) * $no_of_records_per_page;
		$tmp = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON
attorney.id=attorney_firm.firm_id WHERE $query";
		$newResult = mysqli_query($dbhandle, $tmp);
		$rowsCount = mysqli_num_rows($newResult);
		echo json_encode($rowsCount);
		$total_rows = mysqli_fetch_array($tmp)[0];
		$total_pages = ceil($total_rows / $no_of_records_per_page);

		$prepare = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON
attorney.id=attorney_firm.firm_id WHERE $query LIMIT $offset , $no_of_records_per_page";
	}
	$result = mysqli_query($dbhandle, $prepare);
	if ($result) {
		if (mysqli_num_rows($result) == 0) {
			$table_arr['numRows'] = 0;
		} else {
			while ($row = mysqli_fetch_assoc($result)) {
				$query = "SELECT * FROM cases WHERE raid='" . $row['id'] . "'";
				$check = mysqli_query($dbhandle, $query);
				if (mysqli_num_rows($check) == 0) {
					$btn_txt = 'Delete';
				} else {
					$btn_txt = 'Reactivate';
				}
				$row['btnTxt'] = $btn_txt;
				$table_arr['row'][] = $row;
				$table_arr['numRows'] = count($table_arr['row']);
			}
		}
		$table_arr['rowsCount'] = $rowsCount;
		print_r($table_arr);
	} else {
		$table_arr['r'] = 0;
	}

	echo json_encode($table_arr);

}

?>
<?php
if (isset($_POST['checkfirm'])) {
	$key = $_POST['checkfirm'];
	$query = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON
attorney.id=attorney_firm.firm_id WHERE attorney_firm.firm_name= '$key'";
	$result = mysqli_query($dbhandle, $query);
	if (mysqli_num_rows($result) == 0) {
		$check = array();
		$check['res'] = 0;
		$check['firm'] = $key;
		echo json_encode($check);
	} else {
		echo json_encode(1);
	}
}

if (isset($_POST['attorney_form'])) {
	if (isset($_POST['add-firm'])) {
		$query = "INSERT INTO attorney_firm (firm_name) VALUES ('" . $_POST['add-firm'] . "')";
		$insertresult = mysqli_query($dbhandle, $query);
	}
	if ($insertresult) {
		$selectid = "SELECT LAST_INSERT_ID() FROM attorney_firm";
		$selectidres = mysqli_query($dbhandle, $selectid);
		if ($selectrow = mysqli_fetch_assoc($selectidres)) {
			$insertquery = "INSERT INTO attorney (firm, name_first, name_middle, name_last, address, address2, city, state, zip,
phone, email) VALUES ('" . $selectrow['LAST_INSERT_ID()'] . "', '" . $_POST['add-name_first'] . "',
'" . $_POST['add-name_last'] . "', '" . $_POST['add-name_last'] . "', '" . $_POST['add-address'] . "',
'" . $_POST['add-address2'] . "', '" . $_POST['add-city'] . "', '" . $_POST['add-state'] . "', '" . $_POST['add-zip'] . "',
'" . $_POST['add-phone'] . "', '" . $_POST['add-email'] . "')";
			$result = mysqli_query($dbhandle, $insertquery);
			echo json_encode($result);
		}
	}
}

if (isset($_POST['delete'])) {
	$id = $_POST['delete'];
	$query = "SELECT * FROM cases WHERE raid='$id'";
	$check = mysqli_query($dbhandle, $query);
	if (mysqli_num_rows($check) == 0) {
		$query = "SELECT firm FROM attorney WHERE id='$id'";
		$querycheck = mysqli_query($dbhandle, $query);
		if ($querycheck) {
			$firmid = mysqli_fetch_assoc($querycheck);
			$delid = $firmid['firm'];
			$delfirm = "DELETE FROM attorney_firm WHERE firm_id = '$delid'";
			$firmres = mysqli_query($dbhandle, $delfirm);
			$delatt = "DELETE FROM attorney WHERE id = '$id'";
			$atto = mysqli_query($dbhandle, $delatt);
			if ($firmres and $atto) {
				echo 'true';
			} else {
				echo 'false';
			}
		}

	} else {
		$rec = array();
		$rec_val = '';
		$i = 0;
		while ($row = mysqli_fetch_assoc($check)) {
			$rec_val .= 'Case [' . $row['crid'] . '] ' . PHP_EOL;
			$i++;
		}
		$rec_text = 'It can not be deleted because there are currently [' . $i . '] cases tied to the attorney record.' . PHP_EOL;
		$rec_text .= $rec_val;
		echo $rec_text;
	}
	die;
}
if (isset($_POST['pause'])) {
	print_r('test');
	die;
}
?>