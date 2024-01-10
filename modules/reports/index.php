<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="modules/reports/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="modules/reports/css/reports.css?v=v2">
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="modules/reports/js/jquery.js"></script>
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="modules/reports/js/jquery.dataTables.min.js"></script>

<div class="containedBox donotprintthis">
    <form name="reportNavigation" method="POST">
        <fieldset>
            <legend>Reports</legend>
            <table>
                <tr>
                    <td>
                        <input type="submit" name="report" value="Collection Queue" />
                    </td>
                    <td>
                        <input type="submit" name="report" value="Liens To Be Filed" />
                    </td>
                    <td>
                        <input type="submit" name="report" value="Liens Filed" />
                    </td>
                    <td>
                        <input type="submit" name="report" value="Reconciliation Report" />
                    </td>
                    <td>
                        <input type="submit" name="report" value="Date of Injury" />
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>

<?php

if(isset($_POST['report'])) {
    switch($_POST['report']) {
        case 'Collection Queue':
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/modules/reports/reportCollectionQueue.php');
            break;
        case 'Liens Filed':
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/modules/reports/reportLiensFiled.php');
            break;
        case 'Liens To Be Filed':
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/modules/reports/reportLiensToBeFiled.php');
            break;
        case 'Date of Injury':
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/modules/reports/reportDateOfInjury.php');
            break;
        case 'Reconciliation Report':
            require_once ($_SERVER['DOCUMENT_ROOT'] . '/modules/reports/reportReconciliation.php');
            break;
    }
} else {
    echo '<div style="text-align:center;margin-top:20px;">Please select a report.</div>';
}

?>

