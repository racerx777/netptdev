
<table id="cqr" cellspacing="0" cellpadding="3" border="1">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Queue</th>
            <th>User</th>
            <?php if(isset($_POST['acctype']) && $_POST['acctype']) : ?>
            <th>AcctType</th>
            <?php endif; ?>
            <th>Count</th>
            <?php foreach($bnums as $bnum => $bnumAmount) : ?>
            <th><?php echo $bnum; ?></th>
            <?php endforeach; ?>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php if(isset($_POST['acctype']) && $_POST['acctype']) : ?>
        <?php foreach ($queues as $user => $acctQueueRecord) : ?>
        <?php foreach ($acctQueueRecord['acctype'] as $acctype => $queueRecord) : ?>
        <tr>
            <td><?php echo $queueRecord['queue']; ?></td>
            <td><?php echo $user; ?></td>
            <td><?php echo $acctype; ?></td>
            <td style="text-align:right"><?php echo $queueRecord['count']; ?></td>
            <?php foreach($bnums as $bnum => $bnumAmount) : ?>
                <?php if(isset($queueRecord['amounts'][$bnum])) : ?>
                <td style="text-align:right">$<?php echo number_format($queueRecord['amounts'][$bnum], 2); ?></td>
                <?php else: ?>
                <td style="text-align:right">$0.00</td>
                <?php endif; ?>
            <?php endforeach;?>
            <td style="text-align:right">$<?php echo number_format($queueRecord['total'],2); ?></td>
            <?php /*<td>
                <table cellspacing="0" cellpadding="3" border="1">
                    <thead>
                        <tr>
                            <th>BNum</th>
                            <th>PNum</th>
                            <th>AccType</th>
                            <th>Date</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($queueRecord['records'] as $record): ?>
                        <tr>
                            <td><?php echo $record['bnum']; ?></td>
                            <td><?php echo $record['pnum']; ?></td>
                            <td><?php echo $record['acctype']; ?></td>
                            <td><?php echo $record['date']; ?></td>
                            <td><?php echo $record['descrip']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </td> */ ?>
        </tr>
        <?php endforeach; ?>
        <?php endforeach; ?>

        <?php else: ?>

        <?php foreach ($queues as $user => $queueRecord) : ?>
        <tr>
            <td>
        </tr>
        <tr>
            <td><?php echo $queueRecord['queue']; ?></td>
            <td><?php echo $user; ?></td>
            <td style="text-align:right"><?php echo $queueRecord['count']; ?></td>
            <?php foreach($bnums as $bnum => $bnumAmount) : ?>
                <?php if(isset($queueRecord['amounts'][$bnum])) : ?>
                <td style="text-align:right">$<?php echo number_format($queueRecord['amounts'][$bnum], 2); ?></td>
                <?php else: ?>
                <td style="text-align:right">$0.00</td>
                <?php endif; ?>
            <?php endforeach;?>
            <td style="text-align:right">$<?php echo number_format($queueRecord['total'],2); ?></td>
            <td>
                <table cellspacing="0" cellpadding="3" border="1">
                    <thead>
                        <tr>
                            <th>BNum</th>
                            <th>PNum</th>
                            <th>AccType</th>
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($queueRecord['records'] as $record): ?>
                        <tr>
                            <td><?php echo $record['bnum']; ?></td>
                            <td><?php echo $record['pnum']; ?></td>
                            <td><?php echo $record['acctype']; ?></td>
                            <td><?php echo $record['date']; ?></td>
                            <td style="text-align:right">$<?php echo number_format($record['descrip'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <?php endforeach; ?>

        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="<?php echo (isset($_POST['acctype']) && $_POST['acctype']) ? "4" : "3"; ?>">Total</th>
            <th style="text-align:right"><?php echo $count; ?></th>
            <?php foreach($bnums as $bnum => $bnumAmount) : ?>
            <th style="text-align:right">$<?php echo number_format($bnumAmount,2); ?></th>
            <?php endforeach; ?>
            <th style="text-align:right">$<?php echo number_format($total,2); ?></th>
        </tr>
    </tfoot>
</table><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

