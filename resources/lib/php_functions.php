<?php
function user()
{
    if (isset($_SESSION['user'])) {
        return (object) $_SESSION['user'];
    }
    return null;
}

function getDepartmentNames()
{
    global $pdo;
    $sql = "SELECT * FROM tbldepartment";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $departmentNames = array();
    if ($result) {
        foreach ($result as $row) {
            $departmentNames[] = $row;
        }
    }

    return $departmentNames;
}
function getInstructorNames()
{
    global $pdo;
    $sql = "SELECT Id, firstName, lastName FROM tblinstructor";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $instructorNames = array();
    if ($result) {
        foreach ($result as $row) {
            $instructorNames[] = $row;
        }
    }

    return $instructorNames;
}
function getCourseNames()
{
    global $pdo;
    $sql = "SELECT * FROM tblcourse";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $courseNames = array();
    if ($result) {
        foreach ($result as $row) {
            $courseNames[] = $row;
        }
    }

    return $courseNames;
}
function getVenueNames()
{
    $sql = "SELECT className FROM tblvenue";
    $result =  fetch($sql);

    $venueNames = array();
    if ($result) {
        foreach ($result as $row) {
            $venueNames[] = $row;
        }
    }

    return $venueNames;
}
function getSubjectNames()
{
    $sql = "SELECT subjectCode,name FROM tblsubject";
    $result = fetch($sql);

    $subjectNames = array();
    if ($result) {
        foreach ($result as $row) {
            $subjectNames[] = $row;
        }
    }

    return $subjectNames;
}

function showMessage(): void
{
    if (isset($_SESSION['message'])) {
        echo " <div id='messageDiv' class='messageDiv' >{$_SESSION['message']}</div>";
        echo `<script>
        
         var messageDiv = document.getElementById('messageDiv');
    messageDiv.style.opacity = 1;
    setTimeout(function() {
      messageDiv.style.opacity = 0;
    }, 5000);
        </script>`;

        unset($_SESSION['message']);
    }
}


function total_rows($tablename)
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM {$tablename}");
    $total_rows = $stmt->rowCount();
    echo $total_rows;
}

function fetch($sql)
{
    global $pdo;
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}


function fetchStudentRecordsFromDatabase($courseCode, $subjectCode)
{
    $studentRows = array();

    $query = "SELECT * FROM tblattendance WHERE course = '$courseCode' AND subject = '$subjectCode'";
    $result = fetch($query);

    if ($result) {
        foreach ($result as $row) {
            $studentRows[] = $row;
        }
    }

    return $studentRows;
}

function js_asset($links = [])
{
    if ($links) {
        foreach ($links as $link) {
            echo "<script src='resources/assets/javascript/{$link}.js'>
        </script>";
        }
    }
}
