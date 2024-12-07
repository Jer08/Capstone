<?php
require_once "../../lib/php_functions.php";
require_once "../../../database/database_connection.php";
$response = array();

if (isset($_POST['courseID']) && isset($_POST['subjectID']) && isset($_POST['venueID'])) {
    $courseID = $_POST['courseID'];
    $subjectID = $_POST['subjectID'];
    $venueID = $_POST['venueID'];

    $sql = "SELECT idNumber FROM tblStudents WHERE courseCode = '$courseID'";
    $result = fetch($sql);

    if ($result) {
        $idNumbers = array();
        foreach ($result as $row) {
            $idNumbers[] = $row["idNumber"];
        }

        $response['status'] = 'success';
        $response['data'] = $idNumbers;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No records found';
    }

    ob_start();
    include './studentTable.php';
    $tableHTML = ob_get_clean();

    $response['html'] = $tableHTML;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid or missing parameters';
}


header('Content-Type: application/json');
echo json_encode($response);
