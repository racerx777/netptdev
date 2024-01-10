<?php

$insuranceCompany = $pat['iname'];
$insuranceAddress1 = $pat['iadd1'];
$insuranceAddress2 = $pat['iadd2'];
$insuranceAddress3 = $pat['iadd3'];

$attention = $pat['caadjuster1'];
$regarding = $pat['fname']." ".$pat['lname'];
$claimNo = $pat['caclaimnumber1'];
$acctNo = $pat['pnum'];
$dos = date('m/d/Y', strtotime($pat['fvisit'])) . " to " . date('m/d/Y', strtotime($pat['lvisit']));

$acceptAmount = $pat['tbal'] - ($pat['tbal'] * ($mailing['percent_off']/100));
$runTime = strtotime($mailing['run_date']);

$runUser = $mailing['user'];
$signatureFile = "signatures/$runUser.jpg";
if (!file_exists($signatureFile)) {
    $signatureFile = null;
}
$runUserName = $mailing['umname'];

?>
<html>
    <body>
        <p align="center">
            <?php if($pat['bnum'] == 'NET'): ?><img src="logo.jpg" /><br /><? endif; ?>
            5400 Orange Ave, Ste #215 Cypress, CA, 90630<br />
            TAX I. D. No. <?php echo ($pat['bnum'] == 'NET') ? '33-0643694' : '33-0574098'; ?><br />
            Phone: (714) 827-4822 Fax: (714) 826-6833<br />
        </p>
        <p>
            <?php echo date('m/d/Y', $runTime); ?>
        </p>
        <p>
            <?php echo $insuranceCompany; ?><br />
            <?php echo $insuranceAddress1; ?><br />
            <?php if($insuranceAddress2): ?>
            <?php echo $insuranceAddress2; ?><br />
            <?php endif; ?>
            <?php echo $insuranceAddress3; ?>
        </p>
        <p>
        Attention: <?php echo $attention; ?><br />
        RE: <?php echo $regarding; ?><br />
        Claim: <?php echo $claimNo; ?><br />
        Acct#: <?php echo $acctNo; ?><br />
        DOS: <?php echo $dos; ?><br />
        </p>
        <p>
            Please be advised that despite the above referenced claim has settled by way of C&R, our bill/lien remains unpaid. Several attempts to resolve this matter with your office has been unsuccessful.
            <br /><br />
            In a good faith effort to resolve this matter, and to avoid the expense of litigation, West Star is willing to accept $<?php echo number_format($acceptAmount, 2); ?> as payment in full to satisfy our bill/lein.
            <br /><br />
            If payment is received within 20 days of the date of this letter West Star agrees to waive its right to pursue Penalties and Interest as allowed pursuant to L.C.
            <br /><br />
            If payment is not received within 20 days of the date of this letter this offer is null & void and we will have no other choice but to seek the assistance of the WCAB to resolve this matter pursuant to L.C.
            <br /><br />
            Thank you for your immediate attention in resolve this matter.
        </p>
        <div>
            Sincerely,<br />
            <?php if($signatureFile): ?>
            <img src="signatures/<?php echo $runUser; ?>.jpg" /><br />
            <?php else: ?>
            <br />
            <br />
            <?php endif;?>
            <?php echo $runUserName; ?><br />
        </div>
        <br pagebreak="true" />
        <p>
        Regarding: <?php echo $regarding; ?><br />
        Claim #:<?php echo $claimNo; ?><br />
        Account #:<?php echo $acctNo; ?><br />
        </p>
        <p>
        I am employed in the County of Orange, State of California. I am over the age of 18 and not a party within the action. My business address is 5400 Orange Ave, Suite #215 Cypress, CA 90630.
        </p>
        <p>
        On <?php echo date('m/d/Y', $runTime); ?> I served the foregoing document described as <u><b>Itemized Statement and Settlement Demand</b></u> on the interested parties by mail, postage fully prepaid, addressed as follows:
        </p>
        <p>
            <?php echo $insuranceCompany; ?><br />
            <?php echo $insuranceAddress1; ?><br />
            <?php if($insuranceAddress2): ?>
            <?php echo $insuranceAddress2; ?><br />
            <?php endif; ?>
            <?php echo $insuranceAddress3; ?>
        </p>
        <p>
        By MAIL, I am familiar with this office's practice for collection and processing of correspondence for mailing. This correspondence will be deposited with the United States Postal Service in the ordinary course of business on the date indicated below at Cypress, California.
        </p>
        <p>
            Executed on <?php echo date('F d, Y', $runTime); ?> at Cypress, California.
        </p>
        <p>
            I declare under penalty of perjury under the laws of the State of California that the foregoing is true and correct.
        </p>
        <div>
            Sincerely,<br />
            <?php if($signatureFile): ?>
            <img src="signatures/<?php echo $runUser; ?>.jpg" /><br />
            <?php else: ?>
            <br />
            <br />
            <?php endif;?>
            <?php echo $runUserName; ?><br />
        </div>
        <div>
            <?php echo date('m/d/Y', $runTime); ?>
        </div>
    </body>
</html>