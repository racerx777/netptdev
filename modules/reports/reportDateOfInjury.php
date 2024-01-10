<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


if (isset($_POST['run']) && $_POST['year']) {

    $sql = "SELECT SUBSTRING( injury, 1, 4 ) AS year, SUBSTRING( injury, 5, 2 ) AS month , COUNT( * ) as injuryCount, SUM(t120) as t120sum, SUM(t90) as t90sum, SUM(t60) as t60sum, SUM(t30) as t30sum, AVG(tbal) as avgBalanace
    FROM  `PTOS_Patients`
    WHERE injury like '".$_POST['year']."%'
    GROUP BY SUBSTRING( injury, 1, 4 ) , SUBSTRING( injury, 5, 2 )
    ORDER BY year, month";

    $sql = "SELECT SUBSTRING( injury, 1, 4 ) AS year, SUBSTRING( injury, 5, 2 ) AS month , COUNT( * ) as injuryCount, SUM(t120) as t120sum, SUM(t90) as t90sum, SUM(t60) as t60sum, SUM(t30) as t30sum, AVG(tbal) as avgBalanace
    FROM  `PTOS_Patients`
    WHERE injury like '".$_POST['year']."%'
    GROUP BY SUBSTRING( injury, 1, 4 ) , SUBSTRING( injury, 5, 2 )
    ORDER BY year, month";

//$sql = "SELECT mc.cmname, pnum, fname, lname, fvisit, lvisit, acctype, ptosp.attorney, c.crrefdoc, injury, ptosp.tbal,
//        adjust, payments, charges,
//        (SELECT COUNT(*) FROM netpt.treatment_header th WHERE th.thpnum = ptosp.pnum) as treatmentCount
//		FROM cases c
//		JOIN patients p ON c.crpaid = p.paid
//		JOIN PTOS_Patients ptosp ON c.crpnum = ptosp.pnum COLLATE latin1_swedish_ci
//        LEFT JOIN master_clinics mc ON mc.cmcnum = ptosp.cnum
//        LIMIT 100";

    $result = mysqli_query($dbhandle,$sql);

}

?>

<fieldset id="doi-report-controls" class="donotprintthis">
    <legend>Date of Injury Controls</legend>
    <form name="reportForm" method="POST">
        <div style="margin-top: 15px; margin-bottom: 15px;">
            <select name="year">
                <?php for($i = date('Y'); $i >= 1990; $i--): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <input type="submit" value="Run Report" name="run" />
            <input type="hidden" name="report" value="Date of Injury" />
        </div>
    </form>
</fieldset>

<?php if (isset($_POST['run']) && $_POST['year']) :?>
<table id="doi" class="report-table" cellspacing="0" cellpadding="3" border="1">
    <thead>
        <tr>
            <th>Year</th>
            <th>Month</th>
            <th>Count</th>
            <th>t120</th>
            <th>t90</th>
            <th>t60</th>
            <th>t30</th>
            <th>AVG Balance</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?php echo $row['year']; ?></td>
            <td><?php echo $row['month']; ?></td>
            <td><?php echo $row['injuryCount']; ?></td>
            <td><?php echo $row['t120sum']; ?></td>
            <td><?php echo $row['t90sum']; ?></td>
            <td><?php echo $row['t60sum']; ?></td>
            <td><?php echo $row['t30sum']; ?></td>
            <td><?php echo number_format($row['avgBalanace'],2); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<script>
$(document).ready(function(){
    console.log($('#doi'));
    var oTable = $('#doi').dataTable({
        "bPaginate": false,
        "bInfo": false,
        "bFilter": false,
        "aaSorting": [[0, 'asc'],[1,'asc']]
    });
});
</script>
<?php endif; ?>

<?php /*
<table id="doi" cellspacing="0" cellpadding="3" border="1">
    <thead>
        <tr>
            <th>Date of Injury</th>
            <th>First Visit</th>
            <th>Last Visit</th>
            <th>PNum</th>
            <th>Name</th>
            <th>Clinic</th>
            <th>Acct Type</th>
            <th># of Visits</th>
            <th>Charges</th>
            <th>Payments</th>
            <th>Adjustments</th>
            <th>Balance</th>
            <th>Doctor</th>
            <th>Attorney</th>
            <th>$ per Treatment</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?php echo $row['injury']; ?></td>
            <td><?php echo $row['fvisit']; ?></td>
            <td><?php echo $row['lvisit']; ?></td>
            <td><?php echo $row['pnum']; ?></td>
            <td><?php echo $row['fname'] . " " . $row['lname']; ?></td>
            <td><?php echo $row['cmname']; ?></td>
            <td><?php echo $row['acctype']; ?></td>
            <td><?php echo $row['treatmentCount']; ?></td>
            <td><?php echo $row['charges']; ?></td>
            <td><?php echo $row['payments']; ?></td>
            <td><?php echo $row['adjust']; ?></td>
            <td><?php echo $row['tbal']; ?></td>
            <td><?php echo $row['crrefdoc']; ?></td>
            <td><?php echo $row['attorney']; ?></td>
            <td><?php echo number_format($row['charges'] / $row['treatmentCount'], 2); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
 *
 */