<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


if ($_SESSION['button'] == 'defer one week') {
    $query = "
        INSERT INTO report_reconciliation_defer (rrdpnum, rrddeferdate) 
        VALUES ('".$_SESSION['id']."',DATE_ADD(CURDATE(), INTERVAL 7 DAY))";
    mysqli_query($dbhandle,$query);
    
    unset($_SESSION['id']);
    unset($_SESSION['button']);
}

//Remove Old Report Deferments
$query = "DELETE FROM report_reconciliation_defer WHERE rrddeferdate <= CURDATE()";
mysqli_query($dbhandle,$query);

$query = "
SELECT * FROM (
    SELECT p.pnum,p.lvisit,p.lname,p.fname,p.cnum, th.thdate, 'Missed Initial' as type
    FROM ptos_pnums p
    LEFT JOIN treatment_header th ON pnum = thpnum AND thvtmcode = 'NPE'
    WHERE lvisit >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)
    AND thpnum IS NULL
    AND cnum IN " . getUserClinicsList() . "
    
    UNION ALL
    
    SELECT p.pnum,p.lvisit,p.lname,p.fname,p.cnum, th.thdate, 'Re-eval' as type
    FROM ptos_pnums p
    JOIN (
      SELECT    MAX(thid) max_id, thpnum 
      FROM      treatment_header
      WHERE thvtmcode = 'REE'
      GROUP BY  thpnum
    ) th_max ON (th_max.thpnum = p.pnum)
    JOIN treatment_header th ON th_max.max_id = th.thid
    WHERE lvisit >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)
    AND thdate <= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    AND cnum IN " . getUserClinicsList(). "
    
    UNION ALL
    
    SELECT p.pnum,p.lvisit,p.lname,p.fname,p.cnum, th.thdate, 'Discharge' as type
    FROM ptos_pnums p
    LEFT JOIN treatment_header th ON pnum = thpnum AND (thvtmcode = 'DCW' OR thvtmcode = 'DC')
    WHERE lvisit >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)
    AND lvisit <= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
    AND pnum NOT IN ( SELECT rrdpnum FROM report_reconciliation_defer )
    AND th.thpnum IS NULL
    AND cnum IN " . getUserClinicsList(). "
) as E 
JOIN master_clinics ON cmcnum = cnum
ORDER BY cmname,lvisit,pnum
";

if($result = mysqli_query($dbhandle,$query)) {
    
    $numRows = mysqli_num_rows($result);
    
?>
<div class="containedBox">
    <fieldset>
        <legend>Reporting Reconciliation</legend>
    <form name="duplicateList" method="post">
        <table width="100%" cellspacing="0" cellpadding="3" border="1">
            <thead>
                <tr>
                    <th>Clinic</th>
                    <th>PNum</th>
                    <th>Name</th>
                    <th>Missed Eval</th>
                    <th>Last Visit</th>
                    <th>Last Eval</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
<?php
    while($row = mysqli_fetch_assoc($result)) {
?>
            <tr>
                <td><?php echo $row['cmname']; ?></td>
                <td><?php echo $row['pnum']; ?></td>
                <td>
                    <?php echo $row['fname']; ?> <?php echo $row['lname']; ?>
                </td>
                <td>
                    <?php echo $row['type']; ?>
                </td>
                <td><?php echo date('m/d/y', strtotime($row['lvisit'])); ?></td>
                <td><?php if ($row['thdate']) { echo date('m/d/y', strtotime($row['thdate']));} else { echo "&nbsp;"; } ?></td>
                <td>
                    <?php if ($row['type'] == 'Discharge') { ?>
                    <input type="submit" name="button[<?php echo $row['pnum'] ?>]" value="defer one week" />
                    <?php } else { ?> 
                    &nbsp;
                    <?php } ?>
                </td>
            </tr>
<?php
    }
}
?>
            </tbody>
        </table>
    </form>
</fieldset>
</div>