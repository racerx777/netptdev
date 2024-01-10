<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$year = date('Y') - 3;
$date = $year.date('md');

if (isset($_POST['run'])) {

    if($_POST['lienToDate'] && $_POST['lienFromDate']) {
        $lienToDateTimeStamp = strtotime($_POST['lienToDate']);
        $lienToDate = date('Y-m-d 23:59:59', $lienToDateTimeStamp);

        $lienFromDateTimeStamp = strtotime($_POST['lienFromDate']);
        $lienFromDate = date('Y-m-d 00:00:00', $lienFromDateTimeStamp);

        $where[] = "ca.caliendate >='$lienFromDate' and ca.caliendate <= '$lienToDate'";
    }

    if (isset($_POST['upperLimit']) && $_POST['upperLimit']) {
        $where[] = "tbal <= ".intval($_POST['upperLimit']);
    }

    if (isset($_POST['lowerLimit']) && $_POST['lowerLimit']) {
        $where[] = "tbal >= ".intval($_POST['lowerLimit']);
    }

    if (isset($_POST['lienStatus']) && $_POST['lienStatus']) {
        list($accttype, $lienstatus) = explode('|', $_POST['lienStatus']);
        $where[] = "ca.caaccttype = '$accttype' and ca.calienstatus = '$lienstatus'";
    }

    $result = false;
    $groupresult = false;
    if(count($where) > 0) {

        $sql = "SELECT p.bnum, p.pnum, p.lvisit, p.tbal, mclc.clsmdescription, ca.caliendate, ca.calienuser, ca.caaccttype, p.acctype
                FROM PTOS_Patients p
                LEFT JOIN collection_accounts ca ON (cabnum = bnum COLLATE latin1_swedish_ci and capnum = pnum COLLATE latin1_swedish_ci)
                LEFT JOIN master_collections_lienstatus_codes mclc ON (ca.caaccttype = mclc.clsmaccttype COLLATE latin1_swedish_ci and ca.calienstatus = mclc.clsmcode COLLATE latin1_swedish_ci)
                WHERE ".implode(" and ", $where);
        $result = mysqli_query($dbhandle,$sql);

        $groupsql = "SELECT p.bnum, p.acctype, COUNT(*) as count, SUM(tbal) as sum
                FROM PTOS_Patients p
                LEFT JOIN collection_accounts ca ON (cabnum = bnum COLLATE latin1_swedish_ci and capnum = pnum COLLATE latin1_swedish_ci)
                LEFT JOIN master_collections_lienstatus_codes mclc ON (ca.caaccttype = mclc.clsmaccttype COLLATE latin1_swedish_ci and ca.calienstatus = mclc.clsmcode COLLATE latin1_swedish_ci)
                WHERE ".implode(" and ", $where)."
                GROUP BY p.acctype, p.bnum";
        $groupresult = mysqli_query($dbhandle,$groupsql);

        $summary = array();
        $sums = array(
            'NET' => array('sum' => 0, 'count' => 0),
            'WS' => array('sum' => 0, 'count' => 0),
            'total' => array('sum' => 0, 'count' => 0)
        );
        while ($grouprow = mysqli_fetch_assoc($groupresult)) {
            $summary[$grouprow['acctype']][$grouprow['bnum']]['sum'] = $grouprow['sum'];
            $summary[$grouprow['acctype']][$grouprow['bnum']]['count'] = $grouprow['count'];
            if (isset($summary[$grouprow['acctype']]['sum'])) {
                $summary[$grouprow['acctype']]['sum']   += $grouprow['sum'];
                $summary[$grouprow['acctype']]['count'] += $grouprow['count'];
            } else {
                $summary[$grouprow['acctype']]['sum']   =  $grouprow['sum'];
                $summary[$grouprow['acctype']]['count'] =  $grouprow['count'];
            }
            $sums[$grouprow['bnum']]['sum'] += $grouprow['sum'];
            $sums[$grouprow['bnum']]['count'] += $grouprow['count'];
            $sums['total']['sum'] +=   $grouprow['sum'];
            $sums['total']['count'] += $grouprow['count'];
        }

        ksort($summary);
    }



}


$lienStatusSQL = "SELECT clsmaccttype, clsmcode, clsmdescription FROM master_collections_lienstatus_codes ORDER BY clsmaccttype, clsmdspseq";
$lienStatusResult = mysqli_query($dbhandle,$lienStatusSQL);

$accountTypeSQL = "SELECT DISTINCT acctype FROM PTOS_Patients WHERE acctype <> '' ORDER BY acctype * 1";
$accountTypeResult = mysqli_query($dbhandle,$accountTypeSQL);
echo mysqli_error($dbhandle);

?>
<fieldset id="lien-report-controls" class="donotprintthis lein-report-fieldset">
    <legend>Liens Filed Controls</legend>
    <form name="reportForm" method="POST">
        <div class="control-row">
            Lien Date
            <input id="lienFromDate" type="text" onchange="validateDate(this.id)" value="<?php if(isset($_POST['lienFromDate'])) echo $_POST['lienFromDate']; ?>" maxlength="10" size="10" name="lienFromDate" placeholder="From Date">
            <img id="anchor3" align="absmiddle" onclick="cal.select(document.reportForm.lienFromDate,'anchor3','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="anchor3">
            to
            <input id="lienToDate" type="text" onchange="validateDate(this.id)" value="<?php if(isset($_POST['lienToDate'])) echo $_POST['lienToDate']; ?>" maxlength="10" size="10" name="lienToDate" placeholder="To Date">
            <img id="anchor4" align="absmiddle" onclick="cal.select(document.reportForm.lienToDate,'anchor4','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="anchor4">
        </div>
        <div class="control-row">
            Lower Limit:
            <input id="lowerLimit" name="lowerLimit" type="text" value="<?php if(isset($_POST['lowerLimit'])) echo $_POST['lowerLimit']; ?>" />
            Upper Limit:
            <input id="upperLimit" name="upperLimit" type="text" value="<?php if(isset($_POST['upperLimit'])) echo $_POST['upperLimit']; ?>" />
        </div>
        <div class="control-row">
            Status:
            <select id="lienStatus" name="lienStatus">
                <option value="">All Statuses</option>
                <?php while($lienStatusRow = mysqli_fetch_assoc($lienStatusResult)) : ?>
                <?php $lienValue = $lienStatusRow['clsmaccttype']."|".$lienStatusRow['clsmcode']; ?>
                <option <?php if(isset($_POST['lienStatus']) && $lienValue == $_POST['lienStatus']) echo 'selected="selected"'; ?> value="<?php echo $lienValue; ?>"><?php echo $lienStatusRow['clsmaccttype']." - ".$lienStatusRow['clsmdescription'] ?></option>
                <?php endwhile; ?>
            </select>
            Account Type:
            <select id="acctype" name="acctype">
                <option value="">All</option>
                <?php while($accountTypeRow = mysqli_fetch_assoc($accountTypeResult)): ?>
                <option value="<?php echo $accountTypeRow['acctype']; ?>"><?php echo $accountTypeRow['acctype']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="control-row">
            <input type="submit" value="Run Report" name="run" />
            <?php if (isset($_POST['run']) && $_POST['toDate'] && $_POST['fromDate']) : ?>
                <a href="javascript:window.print()">Print This Report</a>
                <a href="modules/reports/reportLiensFiledCSV.php?toDate=<?php echo $_POST['toDate']; ?>&fromDate=<?php echo $_POST['fromDate']; ?>&lowerLimit=<?php if(isset($_POST['lowerLimit'])) echo $_POST['lowerLimit']; ?>&upperLimit=<?php if(isset($_POST['upperLimit'])) echo $_POST['upperLimit']; ?>" target="_blank" />Download To Excel</a>
            <?php endif; ?>
        </div>
        <div class="control-row">
            <input type="hidden" name="report" value="Liens Filed" />
        </div>

    </form>
</fieldset>

<?php if (isset($_POST['run']) && $result) : ?>

    <fieldset class="lein-report-fieldset">
        <legend>Summary</legend>
        <table id="lien-report-summary" cellspacing="0" cellpadding="3" border="1">
            <thead>
                <tr>
                    <th>BNum</th>
                    <?php foreach(array_keys($summary) as $code): ?>
                    <th><?php echo $code; ?></th>
                    <?php endforeach ?>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>NET</td>
                    <?php foreach($summary as $code): ?>
                    <td style="text-align:right">
                        <?php if(isset($code['NET'])): ?>
                            <?php echo $code['NET']['sum']; ?>
                        <?php else: ?>
                            0.00
                        <?php endif; ?>
                    </td>
                    <?php endforeach ?>
                    <td style="text-align:right"><?php echo $sums['NET']['sum']; ?></td>
                </tr>
                <tr>
                    <td>WS</td>
                    <?php foreach($summary as $code): ?>
                    <td style="text-align:right">
                        <?php if(isset($code['WS'])): ?>
                            <?php echo $code['WS']['sum']; ?>
                        <?php else: ?>
                            0.00
                        <?php endif; ?>
                    </td>
                    <?php endforeach ?>
                    <td style="text-align:right"><?php echo $sums['WS']['sum']; ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Sum</th>
                    <?php foreach($summary as $code): ?>
                    <th style="text-align:right">
                        <?php echo $code['sum']; ?>
                    </th>
                    <?php endforeach ?>
                    <th style="text-align:right"><?php echo $sums['total']['sum']; ?></th>
                </tr>
            </tfoot>
        </table>
    </fieldset>


    <table id="lien-report" cellspacing="0" cellpadding="3" border="1">
        <thead>
            <tr>
                <th>BNum</th>
                <th>PNum</th>
                <th>Last Visit</th>
                <th>Type</th>
                <th>Acct Type</th>
                <th>Current Status</th>
                <th>Lien Date</th>
                <th>Lien User</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $row['bnum']; ?></td>
                <td><?php echo $row['pnum']; ?></td>
                <td><?php echo date('Y-m-d', strtotime($row['lvisit'])); ?></td>
                <td><?php echo $row['caaccttype']; ?></td>
                <td><?php echo $row['acctype']; ?></td>
                <td><?php echo $row['clsmdescription']; ?></td>
                <td><?php echo $row['caliendate']; ?></td>
                <td><?php echo $row['calienuser']; ?></td>
                <td style="text-align:right"><?php echo $row['tbal']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>
<script>
    $(document).ready(function(){
        var oTable = $('#lien-report').dataTable({
            "aaSorting": [[6, 'asc']],
            "bPaginate": false,
            "bInfo": false,
            "bFilter": false,
        });
    });

    var cal = new CalendarPopup();
</script>