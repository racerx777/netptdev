<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$territories = array();
$territorySQL = "SELECT mstnum, mstname FROM master_sales_territory;";
$territoryResult = mysqli_query($dbhandle,$territorySQL);
while($territoryRow = mysqli_fetch_assoc($territoryResult)) {
    $territories[$territoryRow['mstnum']] = $territoryRow['mstname'];
}

$userSQL = 'SELECT * FROM master_user WHERE umrole = 65 ORDER BY umuser';
$userResult = mysqli_query($dbhandle,$userSQL);

$userAssign = array();

if(isset($_POST['saveassign'])) {
    //@todo This could be done better
    $savesql = 'TRUNCATE TABLE master_sales_territory_assign; ';
    mysqli_query($dbhandle,$savesql);
    if(isset($_POST['user'])) {
        foreach($_POST['user'] as $user => $userTerritories) {
            foreach($userTerritories as $territory => $value) {
                if($value == "on") {
                    $userAssign[$user][$territory] = true;
                    $savesql = "INSERT INTO master_sales_territory_assign (mstauser, mstaterritory) VALUES ('$user', $territory); ";
                    mysqli_query($dbhandle,$savesql);
                }
            }
        }
    }
} else {
    $findsql = 'SELECT mstauser, mstaterritory FROM master_sales_territory_assign';
    $findResult = mysqli_query($dbhandle,$findsql);
    while($findRow = mysqli_fetch_assoc($findResult)) {
        $userAssign[$findRow['mstauser']][$findRow['mstaterritory']] = true;
    }
}

?>

<div class="containedBox">
    <form name="reportNavigation" method="POST">
        <fieldset>
            <legend>Territory Assignment</legend>
            <?php while($userRow = mysqli_fetch_assoc($userResult)): ?>
            <div>
                <b><?php echo $userRow['umuser']; ?></b> <br />
                <?php foreach($territories as $mstnum => $mstname): ?>
                <input type="checkbox" 
                       name="user<?php echo "[".$userRow['umuser']."][$mstnum]";  ?>"
                       <?php if (isset($userAssign[$userRow['umuser']][$mstnum])): ?>checked="on"<? endif; ?>
                       />
                <?php echo $mstname ?>
                <?php endforeach; ?>
                <hr />
            </div>
            <?php endwhile; ?>
            <input type="submit" name="saveassign" value="Save Assignments" />
        </fieldset>
    </form>
</div>
<?php



?>