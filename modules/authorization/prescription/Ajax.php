<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
errorclear();
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$requestBody = file_get_contents('php://input');
$requestData = json_decode($requestBody, true);

$searching = $requestData['searching'];
$like = $requestData['searchTerm'];

// print_r("sdfjksdjflsdfjldf");
// print_r($requestBody);

// $searching = $_GET['searching'];
// $like = $_GET['searchTerm'];

if ($searching) {
    $query = mysqli_query(
        $dbhandle,
        "SELECT imicd9, imdx
FROM master_ICD9
WHERE iminactive = 0 
  AND (imdx LIKE '%" . mysqli_real_escape_string($dbhandle, $like) . "%'
       OR imicd9 LIKE '%" . mysqli_real_escape_string($dbhandle, $like) . "%')
ORDER BY imicd9
LIMIT 800;"
    );
    $results = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $results[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($results);
}
?>