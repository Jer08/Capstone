<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);
    $response = [];

    if ($attendanceData) {
        try {
            $sql = "INSERT INTO tblattendance (studentIDNumber, course, subject, attendanceStatus, dateMarked)  
                VALUES (:studentID, :course, :subject, :attendanceStatus, :date)";

            $stmt = $pdo->prepare($sql);

            foreach ($attendanceData as $data) {
                $studentID = $data['studentID'];
                $attendanceStatus = $data['attendanceStatus'];
                $course = $data['course'];
                $subject = $data['subject'];
                $date = date("Y-m-d");

                $stmt->execute([
                    ':studentID' => $studentID,
                    ':course' => $course,
                    ':subject' => $subject,
                    ':attendanceStatus' => $attendanceStatus,
                    ':date' => $date
                ]);
            }

            $response['status'] = 'success';
            $response['message'] = "Attendance recorded successfully for all entries.";
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Error inserting attendance data: " . $e->getMessage();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "No attendance data received.";
    }

    echo json_encode($response);
}
