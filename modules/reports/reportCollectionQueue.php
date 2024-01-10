
<?php require_once('reportCollectionQueueQuery.php'); ?>

<fieldset id="collection-report-controls" class="donotprintthis">
    <legend>Collection Queue Report Controls</legend>
    <form name="reportForm" method="POST">
        <div style="margin-top: 15px; margin-bottom: 15px;">
            From Date:
            <input id="fromDate" type="text" onchange="validateDate(this.id)" value="<?php if(isset($_POST['fromDate'])) echo $_POST['fromDate']; ?>" maxlength="10" size="10" name="fromDate">
            <img id="anchor1" align="absmiddle" onclick="cal.select(document.reportForm.fromDate,'anchor1','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="anchor1">
            To Date:
            <input id="toDate" type="text" onchange="validateDate(this.id)" value="<?php if(isset($_POST['toDate'])) echo $_POST['toDate']; ?>" maxlength="10" size="10" name="toDate">
            <img id="anchor2" align="absmiddle" onclick="cal.select(document.reportForm.toDate,'anchor2','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="anchor2">

            <input type="checkbox" name="acctype" id="acctype" value="true"  <?php echo (isset($_POST['acctype']) && $_POST['acctype']) ? "checked" : "" ; ?> /> <label for="acctype">Breakdown By Acctype</label>
            <input type="checkbox" name="detail" id="detail" value="true"  <?php echo (isset($_POST['detail']) && $_POST['detail']) ? "checked" : "" ; ?> /> <label for="detail">Run with Detail</label>

            <input type="submit" value="Run Report" name="run" />
            <?php if (isset($_POST['run']) && $_POST['toDate'] && $_POST['fromDate']) : ?>
                <a href="javascript:window.print()">Print This Report</a>
                <a href="modules/reports/reportCollectionQueueCSV.php?run=1&toDate=<?php echo $_POST['toDate']; ?>&fromDate=<?php echo $_POST['fromDate']; ?>&acctype=<?php echo (isset($_POST['acctype']) && $_POST['acctype']) ? "1" : "0"; ?>&detail=<?php echo (isset($_POST['detail']) && $_POST['detail']) ? "1" : "0"; ?>" target="_blank" />Download To Excel</a>
            <?php endif; ?>

            <input type="hidden" name="report" value="Collection Queue" />
        </div>
    </form>
</fieldset>


<?php if (isset($_POST['run']) && $_POST['toDate'] && $_POST['fromDate']) : ?>
Collector Queue Report: <?php echo $_POST['fromDate'] . " to " . $_POST['toDate']; ?>

<?php if (isset($_POST['detail']) && $_POST['detail']) : ?>
    <?php foreach ($queues as $user => $queueRecord) : ?>
    <h1 class="detail-header"><?php echo ($user) ? $user : "None"; ?></h1>
    <table class="report-table" cellspacing="0" cellpadding="3" border="1">
        <thead>
            <tr>
                <th>BNum</th>
                <th>PNum</th>
                <th>AccType</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Cur User</th>
                <th>Cur Queue</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($queueRecord['records'] as $record): ?>
            <tr>
                <td><?php echo $record['bnum']; ?></td>
                <td><?php echo $record['pnum']; ?></td>
                <td><?php echo $record['acctype']; ?></td>
                <td><?php echo $record['date']; ?></td>
                <td style="text-align:right">$<?php echo number_format($record['amount'], 2); ?></td>
                <td <?php if($record['exception_user'] != $user) { echo 'style="color:red"'; }  ?>><?php echo $record['exception_user']; ?></td>
                <td <?php if($record['exception_user'] != $user) { echo 'style="color:red"'; }  ?>><?php echo $record['exception_queue']; ?></td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <table class="footer-table" cellspacing="0" cellpadding="3" border="1">
        <thead>
            <tr>
                <th>User</th>
                <th>Current Queue</th>
                <?php foreach($bnums as $bnum => $bnumAmount) : ?>
                <th><?php echo $bnum; ?></th>
                <?php endforeach; ?>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $user; ?>
                <td><?php echo $queueRecord['queue']; ?></td>
                <?php foreach($bnums as $bnum => $bnumAmount) : ?>
                <?php if(isset($queueRecord['amounts'][$bnum])) : ?>
                <td style="text-align:right">$<?php echo number_format($queueRecord['amounts'][$bnum], 2); ?></td>
                <?php else: ?>
                <td style="text-align:right">$0.00</td>
                <?php endif; ?>
                <?php endforeach;?>
                <td style="text-align:right">$<?php echo number_format($queueRecord['total'],2); ?></td>
            </tr>
        </tbody>
    </table>
    <?php endforeach; //Queue Loop ?>
<?php else: //Is Not Detail ?>
    <table class="footer-table" cellspacing="0" cellpadding="3" border="1">
        <thead>
            <tr>
                <th>User</th>
                <th>Current Queue</th>
                <?php if(isset($_POST['acctype']) && $_POST['acctype']) : ?>
                <th>Acctype</th>
                <?php endif; ?>
                <?php foreach($bnums as $bnum => $bnumAmount) : ?>
                <th><?php echo $bnum; ?></th>
                <?php endforeach; ?>
                <th>Total</th>
                <th>Exception</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($_POST['acctype']) && $_POST['acctype']) : ?>

            <?php foreach ($queues as $user => $acctQueueRecord) : ?>
            <?php foreach ($acctQueueRecord['acctype'] as $acctype => $queueRecord) : ?>
            <tr>
                <td><?php echo $user; ?>
                <td><?php echo $queueRecord['queue']; ?></td>
                <td><?php echo $acctype; ?></td>
                <?php foreach($bnums as $bnum => $bnumAmount) : ?>
                <?php if(isset($queueRecord['amounts'][$bnum])) : ?>
                <td style="text-align:right">$<?php echo number_format($queueRecord['amounts'][$bnum], 2); ?></td>
                <?php else: ?>
                <td style="text-align:right">$0.00</td>
                <?php endif; ?>
                <?php endforeach;?>
                <td style="text-align:right">$<?php echo number_format($queueRecord['total'],2); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>

            <?php else: ?>

            <?php foreach ($queues as $user => $queueRecord) : ?>
            <tr>
                <td><?php echo $user; ?>
                <td><?php echo $queueRecord['queue']; ?></td>
                <?php foreach($bnums as $bnum => $bnumAmount) : ?>
                <?php if(isset($queueRecord['amounts'][$bnum])) : ?>
                <td style="text-align:right">$<?php echo number_format($queueRecord['amounts'][$bnum], 2); ?></td>
                <?php else: ?>
                <td style="text-align:right">$0.00</td>
                <?php endif; ?>
                <?php endforeach;?>
                <td style="text-align:right">$<?php echo number_format($queueRecord['total'],2); ?></td>
                <td style="text-align:right">$<?php echo number_format($queueRecord['exception'],2); ?></td>
            </tr>
            <?php endforeach; ?>

            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <?php if(isset($_POST['acctype']) && $_POST['acctype']) : ?>
                <th colspan="3">&nbsp;</th>
                <?php else: ?>
                <th colspan="2">&nbsp;</th>
                <?php endif; ?>
                <?php foreach($bnums as $bnum => $bnumAmount) : ?>
                <th style="text-align:right">$<?php echo number_format($bnumAmount, 2); ?></th>
                <?php endforeach; ?>
                <th style="text-align:right">$<?php echo number_format($total, 2); ?>
                <th style="text-align:right">$<?php echo number_format($exception_total, 2); ?>
            </tr>

        </tfoot>
    </table>
<?php endif; //Check Is Detail ?>
<!-- @todo I don't know where the best place for the css should be so I did it inline for the moment -->
<?php else: ?>
Please Select a From and To Date
<?php endif; ?>

<script>

$(document).ready(function(){

    $('.report-table').dataTable({
        "bPaginate": false,
        "bInfo": false,
        "bFilter": false,
        "aaSorting": [[3, 'asc']]
    });

    $('.footer-table').dataTable({
        "bPaginate": false,
        "bInfo": false,
        "bFilter": false,
        <?php if (isset($_POST['detail']) && $_POST['detail']) : ?>
        "bSort": false
        <?php else: ?>
        "bSort": true,
        "aaSorting": [[1, 'asc'],[0,'asc']]
        <?php endif; ?>
    });
});

var cal = new CalendarPopup();
</script>