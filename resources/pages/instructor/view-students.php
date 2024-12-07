<?php

$courseCode = isset($_GET['course']) ? $_GET['course'] : '';
$subjectCode = isset($_GET['subject']) ? $_GET['subject'] : '';

$studentRows = fetchStudentRecordsFromDatabase($courseCode, $subjectCode);

$coursename = "";
if (!empty($courseCode)) {
    $coursename_query = "SELECT name FROM tblcourse WHERE courseCode = '$courseCode'";
    $result = fetch($coursename_query);
    foreach ($result as $row) {

        $coursename = $row['name'];
    }
}
$subjectname = "";
if (!empty($subjectCode)) {
    $subjectname_query = "SELECT name FROM tblsubject WHERE subjectCode = '$subjectCode'";
    $result = fetch($subjectname_query);
    foreach ($result as $row) {

        $subjectname = $row['name'];
    }
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="resources/images/logo/attnlg.png" rel="icon">
    <title>instructor Dashboard</title>
    <link rel="stylesheet" href="resources/assets/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>



<body>
    <?php include 'includes/topbar.php'; ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
            <form class="instructor-options" id="selectForm">
                <select required name="course" id="courseSelect" onChange="updateTable()">
                    <option value="" selected>Select Course</option>
                    <?php
                    $courseNames = getCourseNames();
                    foreach ($courseNames as $course) {
                        echo '<option value="' . $course["courseCode"] . '">' . $course["name"] . '</option>';
                    }
                    ?>
                </select>

                <select required name="subject" id="subjectSelect" onChange="updateTable()">
                    <option value="" selected>Select Subject</option>
                    <?php
                    $subjectNames = getSubjectNames();
                    foreach ($subjectNames as $subject) {
                        echo '<option value="' . $subject["subjectCode"] . '">' . $subject["name"] . '</option>';
                    }
                    ?>
                </select>
            </form>


            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">Students List</h2>
                </div>
                <div class="table attendance-table" id="attendaceTable">
                    <table>
                        <thead>
                            <tr>
                                <th>ID No</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $query = "SELECT * FROM tblstudents WHERE courseCode = '$courseCode'";

                            $result = fetch($query);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr>";
                                    echo "<td>" . $row['idNumber'] . "</td>";
                                    echo "<td>" . $row['firstName'] . "</td>";
                                    echo "<td>" . $row['lastName'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";

                                    echo "</tr>";
                                }

                                echo "</table>";
                            } else {
                            }
                            ?>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        </div>
    </section>
    <div>
        <?php js_asset(["active_link", "min/js/filesaver", "min/js/xlsx"]) ?>
</body>


<script>
    function updateTable() {
        console.log("update noted");
        var courseSelect = document.getElementById("courseSelect");
        var subjectSelect = document.getElementById("subjectSelect");

        var selectedCourse = courseSelect.value;
        var selectedSubject = subjectSelect.value;

        var url = "view-students";
        if (selectedCourse && selectedSubject) {
            url += "?course=" + encodeURIComponent(selectedCourse) + "&subject=" + encodeURIComponent(selectedSubject);
            window.location.href = url;
            console.log(url)
        }
    }
</script>

</html>