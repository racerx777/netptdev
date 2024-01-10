<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$user = $_SESSION['user']['umuser'];

if (isset($_POST['start'])) {
    $acctype = $_POST['acctype'];
    $percentOff = intval($_POST['percent-off']);
    $template = $_POST['template'];
    $runDate = date('Y-m-d 00:00:00', strtotime($_POST['run-date']));
    $lowerLimit = intval($_POST['lower-limit']);
    $upperLimit = intval($_POST['upper-limit']);
    $queue = trim($_POST['queue']);
    $sql = "INSERT INTO mass_mailing (acctype,percent_off,run_date,lower_limit,upper_limit,queue,template,user,date,in_process) VALUES ('$acctype',$percentOff,'$runDate', $lowerLimit, $upperLimit,'$queue','$template','$user', NOW(), 0)";
    mysqli_query($dbhandle,$sql);
    echo mysqli_error($dbhandle);
}

if (isset($_POST['stats'])) {

    $acctype = $_POST['acctype'];
    $percentOff = intval($_POST['percent-off']);
    $template = $_POST['template'];
    $runDate = date('Y-m-d 00:00:00', strtotime($_POST['run-date']));
    $lowerLimit = intval($_POST['lower-limit']);
    $upperLimit = intval($_POST['upper-limit']);
    $queue = trim($_POST['queue']);

    $where = array();
    $where[] = "acctype='$acctype'";
    $where[] = "tbal > 0";

    if ($lowerLimit) {
        $where[] = "tbal >=" . $lowerLimit;
    }

    if ($upperLimit) {
        $where[] = "tbal <= " . $upperLimit;
    }

    if ($queue) {
        $where[] = "cqgroup = '" . $queue ."'";
    }

    $wherestr = implode(" and ", $where);

    $sql = "SELECT cqgroup, umuser, uminactive, COUNT(*) as count
            FROM PTOS_Patients p
            LEFT JOIN collection_accounts ca on ca.capnum = p.pnum
            LEFT JOIN collection_queue cq on ca.caid = cq.cqcaid
            LEFT JOIN master_collections_queue_assign mcqa ON cqgroup = mcqa.cqagroup
            LEFT JOIN master_user ON cqauser = umuser
            LEFT JOIN PTOS_Insurance pi on pi.icode = ca.cainsname1 and p.bnum = pi.bnum
            WHERE $wherestr
            GROUP BY cqgroup, umuser, uminactive
            ORDER BY cqgroup, umuser";
    $result = mysqli_query($dbhandle,$sql);

    $stats = array();
    while($row = mysqli_fetch_assoc($result)) {
        $queueS = $row['cqgroup'];
        $stats[$queueS]['count'] = $row['count'];
        $stats[$queueS]['users'][] = $row['umuser'];
        $stats[$queueS]['msgs'] = array();
        if ($row['uminactive']) {
            $stats[$queueS]['msgs'][] = $row['umuser']. " is inactive. Jackie will be substitued.";
        } elseif (!is_file("modules/collectionsmassmailer/signatures/".$row["umuser"].".jpg")) {
            $stats[$queueS]['msgs'][] = $row['umuser']. " is missing a signature image.";
        }
    }
}

$sql = "SELECT * FROM mass_mailing ORDER BY date DESC";
$result = mysqli_query($dbhandle,$sql);

$accountTypeSQL = "SELECT DISTINCT acctype FROM PTOS_Patients WHERE acctype <> '' ORDER BY acctype * 1";
$accountTypeResult = mysqli_query($dbhandle,$accountTypeSQL);

$queueSQL = "SELECT DISTINCT cqgroup FROM collection_queue ORDER BY cqgroup";
$queueResult = mysqli_query($dbhandle,$queueSQL);

?>
<style>
    form#massmailer div {
        margin: 10px 0;
    }

    form#massmailer label {
        display: inline-block;
        width: 100px;
        text-align: right;
    }

    #left {
        width: 45%;
        display: inline-block;
        vertical-align: top;
    }

    #right {
        width: 50%;
        display: inline-block;
        vertical-align: top;
    }
</style>
<div class="containedBox">
<fieldset>
    <legend>
        Mass Mailer - Demand Letter
    </legend>
    <form id="massmailer" method="POST">
        <div id="left">
            <div>
                <label for="acctype">AcctType:</label>
                <select id="acctype" name="acctype">
                    <?php while($accountTypeRow = mysqli_fetch_assoc($accountTypeResult)): ?>
                    <option <?php echo ($accountTypeRow['acctype'] == $acctype) ? "selected" : ""; ?> value="<?php echo $accountTypeRow['acctype']; ?>"><?php echo $accountTypeRow['acctype']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="queue">Queue:</label>
                <select id="queue" name="queue">
                    <option value="">All</option>
                    <?php while($queueRow = mysqli_fetch_assoc($queueResult)): ?>
                    <option <?php echo ($queueRow['cqgroup'] == $queue) ? "selected" : ""; ?> value="<?php echo $queueRow['cqgroup']; ?>"><?php echo $queueRow['cqgroup']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="percent-off">Percent Off:</label>
                <input type="text" name="percent-off" id="percent-off" placeholder="Percent Off" size="10" required="true" value="<?php echo $percentOff; ?>" />
            </div>
            <div>
                <label for="run-date">Run Date:</label>
                <input type="text" name="run-date" id="run-date" value="<?php echo ($runDate) ? $runDate : date('m/d/Y', strtotime("+5 days")); ?>" size="10" required="true" />
            </div>
            <div>
                <label for="range-lower">Lower Limit:</label>
                <input type="text" name="lower-limit" id="lower-limit" size="10" value="<?php echo ($lowerLimit) ? $lowerLimit : "" ?>" />
                <label for="range-upper">Upper Limit:</label>
                <input type="text" name="upper-limit" id="upper-limit" size="10" value="<?php echo ($upperLimit) ? $upperLimit : "" ?>" />
            </div>
            <div>
                <input type="hidden" name="template" value="demandLetter.template.php" />
                <input type="submit" name="stats" value="Get Stats" />
                <input type="submit" name="start" value="Start Generation" />
            </div>
        </div>
        <div id="right">
            <?php if (isset($_POST['stats'])): ?>
            <h2>Stats</h2>
            <table cellspacing="0" cellpadding="3" border="1">
                <thead>
                    <tr>
                        <th>Queue</th>
                        <th>Count</th>
                        <th>Users</th>
                        <th>Errors</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($stats as $queue => $stat) : ?>
                    <tr>
                        <td><?php echo $queue; ?></td>
                        <td><?php echo $stat['count']; ?></td>
                        <td><?php echo implode(", ", $stat['users']); ?></td>
                        <td>
                            <?php if(!implode(",", $stat['users'])) : ?>
                                There are no users assigned to this queue.
                            <?php elseif (count($stat['users']) > 1) : ?>
                                There are multiple users assigned to this queue.
                            <?php elseif (count($stat['msgs']) > 0) : ?>
                                <?php echo implode("<br />", $stat['msgs']) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </form>
</fieldset>

<table cellspacing="0" cellpadding="3" border="1" style="margin:10px; width: 90%;">
    <thead>
        <tr>
            <th>Acctype</th>
            <th>Percent Off</th>
            <th>Lower</th>
            <th>Upper</th>
            <th>Run Date</th>
            <th>Queue</th>
            <th>Template</th>
            <th>User</th>
            <th>Created</th>
            <th>In Process</th>
            <th>Done</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['acctype']; ?></td>
            <td><?php echo $row['percent_off']; ?></td>
            <td>$<?php if($row['lower_limit']) { echo number_format($row['lower_limit']); } ?></td>
            <td>$<?php if($row['upper_limit']) { echo number_format($row['upper_limit']); } ?></td>
            <td><?php echo date('m/d/Y', strtotime($row['run_date'])); ?></td>
            <td><?php echo $row['queue']; ?></td>
            <td><?php echo $row['template']; ?></td>
            <td><?php echo $row['user']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo ($row['in_process']) ? "true" : "false"; ?></td>
            <td><?php echo ($row['done']) ? "true" : "false"; ?></td>
            <td>
                <?php if($row['done']): ?>
                <a target="_blank" href="modules/collectionsmassmailer/pdfs/demand_letter_<?php echo $row['id']; ?>.pdf">Download</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>