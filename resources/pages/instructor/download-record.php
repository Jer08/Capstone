<?php




$courseCode = isset($_GET['course']) ? $_GET['course'] : '';
$subjectCode = isset($_GET['subject']) ? $_GET['subject'] : '';

$studentRows = fetchStudentRecordsFromDatabase($courseCode, $subjectCode);

$coursename = "";
if (!empty($courseCode)) {
    $coursename_query = "SELECT name FROM tblcourse WHERE courseCode = '$courseCode'";
    $result = fetch($coursename_query);


    if ($result) {
        foreach ($result as $row) {
            $coursename = $row['name'];
        }
    }
}
$subjectname = "";
if (!empty($subjectCode)) {
    $subjectname_query = "SELECT name FROM tblsubject WHERE subjectCode = '$subjectCode'";
    $result = fetch($subjectname_query);
    if ($result) {
        foreach ($result as $row)
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
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
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

            <button class="add" onclick="exportTableToExcel('attendaceTable', '<?php echo $subjectCode . '_on_' . date('Y-m-d'); ?>','<?php echo $coursename ?>', '<?php echo $subjectname ?>')">Export Attendance As Excel</button>

            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">Attendance Preview</h2>
                </div>
                <div class="table attendance-table" id="attendaceTable">
                    <table>
                        <thead>
                            <tr>
                                <th>ID No</th>
                                <?php
                                // Fetch distinct dates for the selected course and subject
                                $distinctDatesQuery = "SELECT DISTINCT dateMarked FROM tblattendance WHERE course = :courseCode AND subject = :subjectCode";
                                $stmtDates = $pdo->prepare($distinctDatesQuery);
                                $stmtDates->execute([
                                    ':courseCode' => $courseCode,
                                    ':subjectCode' => $subjectCode,
                                ]);
                                $distinctDatesResult = $stmtDates->fetchAll(PDO::FETCH_ASSOC);

                                // Display each distinct date as a column header
                                if ($distinctDatesResult) {
                                    foreach ($distinctDatesResult as $dateRow) {
                                        echo "<th>" . $dateRow['dateMarked'] . "</th>";
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch all unique students for the given course and subject
                            $studentsQuery = "SELECT DISTINCT studentIDNumber FROM tblattendance WHERE course = :courseCode AND subject = :subjectCode";
                            $stmtStudents = $pdo->prepare($studentsQuery);
                            $stmtStudents->execute([
                                ':courseCode' => $courseCode,
                                ':subjectCode' => $subjectCode,
                            ]);
                            $studentRows = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);

                            // Display each student's attendance row
                            foreach ($studentRows as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['studentIDNumber'] . "</td>";

                                // Loop through each date and fetch the attendance status for the student
                                foreach ($distinctDatesResult as $dateRow) {
                                    $date = $dateRow['dateMarked'];

                                    // Fetch attendance for the current student and date
                                    $attendanceQuery = "SELECT attendanceStatus FROM tblattendance 
                                    WHERE studentIDNumber = :studentIDNumber 
                                    AND dateMarked = :date 
                                    AND course = :courseCode 
                                    AND subject = :subjectCode";
                                    $stmtAttendance = $pdo->prepare($attendanceQuery);
                                    $stmtAttendance->execute([
                                        ':studentIDNumber' => $row['studentIDNumber'],
                                        ':date' => $date,
                                        ':courseCode' => $courseCode,
                                        ':subjectCode' => $subjectCode,
                                    ]);
                                    $attendanceResult = $stmtAttendance->fetch(PDO::FETCH_ASSOC);

                                    // Display attendance status or default to "Absent"
                                    if ($attendanceResult) {
                                        echo "<td>" . $attendanceResult['attendanceStatus'] . "</td>";
                                    } else {
                                        echo "<td>Absent</td>";
                                    }
                                }

                                echo "</tr>";
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
</body>


<?php js_asset(["min/js/filesaver", "min/js/xlsx", "active_link"]) ?>
<script>
    function updateTable() {
        var courseSelect = document.getElementById("courseSelect");
        var subjectSelect = document.getElementById("subjectSelect");

        var selectedCourse = courseSelect.value;
        var selectedSubject = subjectSelect.value;

        var url = "download-record";
        if (selectedCourse && selectedSubject) {
            url += "?course=" + encodeURIComponent(selectedCourse) + "&subject=" + encodeURIComponent(selectedSubject);
            window.location.href = url;

        }
    }

    function exportTableToExcel(tableId, filename = '', courseCode = '', subjectCode = '') {
        var table = document.getElementById(tableId);
        var currentDate = new Date();
        var formattedDate = currentDate.toLocaleDateString(); // Format the date as needed

        var headerContent = '<p style="font-weight:700;"> Attendance for : ' + courseCode + ' Subject name : ' + subjectCode + ' On: ' + formattedDate + '</p>';
        var tbody = document.createElement('tbody');
        var additionalRow = tbody.insertRow(0);
        var additionalCell = additionalRow.insertCell(0);
        additionalCell.innerHTML = headerContent;
        table.insertBefore(tbody, table.firstChild);
        var wb = XLSX.utils.table_to_book(table, {
            sheet: "Attendance"
        });
        var wbout = XLSX.write(wb, {
            bookType: 'xlsx',
            bookSST: true,
            type: 'binary'
        });
        var blob = new Blob([s2ab(wbout)], {
            type: 'application/octet-stream'
        });
        if (!filename.toLowerCase().endsWith('.xlsx')) {
            filename += '.xlsx';
        }

        saveAs(blob, filename);
    }

    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
        return buf;
    }
</script>

</html>