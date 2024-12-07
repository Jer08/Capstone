<?php

include './includes/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);

    if (!empty($attendanceData)) {
        foreach ($attendanceData as $data) {
            $studentID = $data['studentID'];
            $attendanceStatus = $data['attendanceStatus'];
            $course = $data['course'];
            $subject = $data['subject'];
            $date = date("Y-m-d"); 

            $sql = "INSERT INTO tblattendance(studentIDNumber, course, subject, attendanceStatus, dateMarked)  
                    VALUES ('$studentID', '$course', '$subject', '$attendanceStatus', '$date')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Attendance data for student ID $studentID inserted successfully.<br>";
            } else {
                echo "Error inserting attendance data: " . $conn->error . "<br>";
            }
        }
    } else {
        echo "No attendance data received.<br>";
    }
} else {
    echo "Invalid request method.<br>";
}

?>
