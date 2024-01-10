<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$cutoff = '19900701';

$threeYearsAgo = date('m/d/Y', strtotime('-3 years'));
$eighteenMonthsAgo = date('m/d/Y', strtotime('-18 months'));

if (isset($_POST['run'])) {

    $where = array();
    $where[] = 'tbal > 0';
    $where[] = "(ca.calienstatus = 'NON' or ca.calienstatus = '')";

    if ($_POST['toDate'] && $_POST['fromDate']) {

        $toDateTimeStamp = strtotime($_POST['toDate']);
        $fromDateTimeStamp = strtotime($_POST['fromDate']);

        $toDatePreCutoff  = date('Ymd', strtotime('-3 years', $toDateTimeStamp));
        $toDatePostCutoff = date('Ymd', strtotime('-18 months', $toDateTimeStamp));

        $fromDatePreCutoff  = date('Ymd', strtotime('-3 years', $fromDateTimeStamp));
        $fromDatePostCutoff = date('Ymd', strtotime('-18 months', $fromDateTimeStamp));

        $preCutoff = "(lvisit < '$cutoff' and lvisit >= '$fromDatePreCutoff' and lvisit <= '$toDatePreCutoff')";
        $postCutoff = "(lvisit >= '$cutoff' and lvisit >= '$fromDatePostCutoff' and lvisit <= '$toDatePostCutoff')";

        $where[] = "($preCutoff or $postCutoff)";
    }

    if (isset($_POST['upperLimit']) && $_POST['upperLimit']) {
        $where[] = "tbal <= ".intval($_POST['upperLimit']);
    }

    if (isset($_POST['lowerLimit']) && $_POST['lowerLimit']) {
        $where[] = "tbal >= ".intval($_POST['lowerLimit']);
    }
    
    $where[] = "ca.caaccttype != 'PI'";

    $sql = "SELECT p.bnum, p.pnum, p.fname, p.lname, p.lvisit, p.tbal, p.ssn, p.birth, mclc.clsmdescription, ca.caliendate, ca.calienuser, ca.caaccttype, ca.cawcab1
            FROM PTOS_Patients p
            LEFT JOIN collection_accounts ca ON (cabnum = bnum COLLATE latin1_swedish_ci and capnum = pnum COLLATE latin1_swedish_ci)
            LEFT JOIN master_collections_lienstatus_codes mclc ON (ca.caaccttype = mclc.clsmaccttype COLLATE latin1_swedish_ci and ca.calienstatus = mclc.clsmcode COLLATE latin1_swedish_ci)
            WHERE  ".implode(" and ", $where);
    $result = mysqli_query($dbhandle,$sql);

    $groupsql = "SELECT p.bnum, COUNT(*) as count, SUM(tbal) as sum
            FROM PTOS_Patients p
            LEFT JOIN collection_accounts ca ON (cabnum = bnum COLLATE latin1_swedish_ci and capnum = pnum COLLATE latin1_swedish_ci)
            LEFT JOIN master_collections_lienstatus_codes mclc ON (ca.caaccttype = mclc.clsmaccttype COLLATE latin1_swedish_ci and ca.calienstatus = mclc.clsmcode COLLATE latin1_swedish_ci)
            WHERE ".implode(" and ", $where).
            " GROUP BY p.bnum";
    $groupresult = mysqli_query($dbhandle,$groupsql);

    echo mysqli_error($dbhandle);

}

?>
<fieldset id="lien-report-controls" class="donotprintthis lein-report-fieldset">
    <legend>Liens To Be Filed</legend>
    <form name="reportForm" method="POST">
        <div class="control-row">
            Dead Date
            <input id="fromDate" type="text" onchange="validateDate(this.id)" value="<?php if(isset($_POST['fromDate'])) echo $_POST['fromDate']; ?>" maxlength="10" size="10" name="fromDate" placeholder="From Date">
            <img id="anchor1" align="absmiddle" onclick="cal.select(document.reportForm.fromDate,'anchor1','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="anchor1">
            to
            <input id="toDate" type="text" onchange="validateDate(this.id)" value="<?php if(isset($_POST['toDate'])) echo $_POST['toDate']; ?>" maxlength="10" size="10" name="toDate" placeholder="To Date">
            <img id="anchor2" align="absmiddle" onclick="cal.select(document.reportForm.toDate,'anchor2','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="anchor2">
        </div>
        <div class="control-row">
            Lower Limit:
            <input id="lowerLimit" name="lowerLimit" type="text" value="<?php if(isset($_POST['lowerLimit'])) echo $_POST['lowerLimit']; ?>" />
            Upper Limit:
            <input id="upperLimit" name="upperLimit" type="text" value="<?php if(isset($_POST['upperLimit'])) echo $_POST['upperLimit']; ?>" />
        </div>
        <div class="control-row">
            <input type="submit" value="Run Report" name="run" />
            <?php if (isset($_POST['run']) && $_POST['toDate'] && $_POST['fromDate']) : ?>
                <a href="javascript:window.print()">Print This Report</a>
                <a href="modules/reports/reportLiensToBeFiledCSV.php?toDate=<?php echo $_POST['toDate']; ?>&fromDate=<?php echo $_POST['fromDate']; ?>&lowerLimit=<?php if(isset($_POST['lowerLimit'])) echo $_POST['lowerLimit']; ?>&upperLimit=<?php if(isset($_POST['upperLimit'])) echo $_POST['upperLimit']; ?>" target="_blank" />Download To Excel</a>
            <?php endif; ?>
        </div>
        <div class="control-row">
            <input type="hidden" name="report" value="Liens To Be Filed" />
        </div>

    </form>
</fieldset>

<?php if (isset($_POST['run'])) : ?>

    <fieldset class="lein-report-fieldset">
        <legend>Summary</legend>
        <table id="lien-report-summary" cellspacing="0" cellpadding="3" border="1">
            <thead>
                <tr>
                    <th>BNum</th>
                    <th>Cases</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $countTotal = 0; $sumTotal = 0; ?>
                <?php while($grouprow = mysqli_fetch_assoc($groupresult)) : ?>
                <tr>
                    <td><?php echo $grouprow['bnum']; ?></td>
                    <td class="number-cell"><?php echo $grouprow['count']; $countTotal+= $grouprow['count']; ?></td>
                    <td class="number-cell"><?php echo $grouprow['sum']; $sumTotal+= $grouprow['sum']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total:</th>
                    <th class="number-cell"><?php echo $countTotal; ?></th>
                    <th class="number-cell"><?php echo $sumTotal; ?></th>
                </tr>
            </tfoot>
        </table>
    </fieldset>


    <table id="lien-report" cellspacing="0" cellpadding="3" border="1">
        <thead>
            <tr>
                <th>BNum</th>
                <th>PNum</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>SSN</th>
                <th>Birth Date</th>
                <th>Last Visit</th>
                <th>Dead Date</th>                
                <th>Acct Type</th>
                <th>WCAB</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $row['bnum']; ?></td>
                <td><?php echo $row['pnum']; ?></td>
                <td><?php echo $row['fname']; ?></td>
                <td><?php echo $row['lname']; ?></td>
                <td><?php echo $row['ssn']; ?></td>
                <td><?php echo date('Y-m-d', strtotime($row['birth'])); ?></td>                
                <td><?php echo date('Y-m-d', strtotime($row['lvisit'])); ?></td>
                <td>
                    <?php if ($row['lvisit'] < $cutoff): ?>
                    <?php echo date('Y-m-d', strtotime('+3 years', strtotime($row['lvisit']))); ?>
                    <?php elseif ($row['lvisit'] >= $cutoff): ?>
                    <?php echo date('Y-m-d', strtotime('+18 months', strtotime($row['lvisit']))); ?>
                    <?php endif; ?>
                </td>
                <td><?php echo $row['caaccttype']; ?></td>
                <td><?php echo $row['cawcab1']; ?></td>
                <td style="text-align:right"><?php echo $row['tbal']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>
<script>
    $(document).ready(function(){
        var oTable = $('#lien-report').dataTable({
            "aaSorting": [[5, 'asc']],
            "bPaginate": false,
            "bInfo": false,
            "bFilter": false,
        });
    });

    var cal = new CalendarPopup();
</script>