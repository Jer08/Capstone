<?php


if (isset($_POST["addCourse"])) {
    $courseName = htmlspecialchars(trim($_POST["courseName"])); // Escape and trim whitespace
    $courseCode = htmlspecialchars(trim($_POST["courseCode"]));
    $departmentID = filter_var($_POST["department"], FILTER_VALIDATE_INT);
    $dateRegistered = date("Y-m-d");

    if ($courseName && $courseCode && $departmentID) {
        $query = $pdo->prepare("SELECT * FROM tblcourse WHERE courseCode = :courseCode");
        $query->bindParam(':courseCode', $courseCode);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['message'] = "Course Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tblcourse (name, courseCode, departmentID, dateCreated) 
                                     VALUES (:name, :courseCode, :departmentID, :dateCreated)");
            $query->bindParam(':name', $courseName);
            $query->bindParam(':courseCode', $courseCode);
            $query->bindParam(':departmentID', $departmentID);
            $query->bindParam(':dateCreated', $dateRegistered);
            $query->execute();

            $_SESSION['message'] = "Course Inserted Successfully";
        }
    } else {
        $_SESSION['message'] = "Invalid input for course";
    }
}

if (isset($_POST["addSubject"])) {
    $subjectName = htmlspecialchars(trim($_POST["subjectName"]));
    $subjectCode = htmlspecialchars(trim($_POST["subjectCode"]));
    $courseID = filter_var($_POST["course"], FILTER_VALIDATE_INT);
    $dateRegistered = date("Y-m-d");

    if ($subjectName && $subjectCode && $courseID) {
        $query = $pdo->prepare("SELECT * FROM tblsubject WHERE subjectCode = :subjectCode");
        $query->bindParam(':subjectCode', $subjectCode);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['message'] = "Subject Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tblsubject (name, subjectCode, courseID, dateCreated) 
                                     VALUES (:name, :subjectCode, :courseID, :dateCreated)");
            $query->bindParam(':name', $subjectName);
            $query->bindParam(':subjectCode', $subjectCode);
            $query->bindParam(':courseID', $courseID);
            $query->bindParam(':dateCreated', $dateRegistered);
            $query->execute();

            $_SESSION['message'] = "Subject Inserted Successfully";
        }
    } else {
        $_SESSION['message'] = "Invalid input for subject";
    }
}

if (isset($_POST["addDepartment"])) {
    $departmentName = htmlspecialchars(trim($_POST["departmentName"]));
    $departmentCode = htmlspecialchars(trim($_POST["departmentCode"]));
    $dateRegistered = date("Y-m-d");

    if ($departmentName && $departmentCode) {
        $query = $pdo->prepare("SELECT * FROM tbldepartment WHERE departmentCode = :departmentCode");
        $query->bindParam(':departmentCode', $departmentCode);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['message'] = "Department Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tbldepartment (departmentName, departmentCode, dateRegistered) 
                                     VALUES (:departmentName, :departmentCode, :dateRegistered)");
            $query->bindParam(':departmentName', $departmentName);
            $query->bindParam(':departmentCode', $departmentCode);
            $query->bindParam(':dateRegistered', $dateRegistered);
            $query->execute();

            $_SESSION['message'] = "Department Inserted Successfully";
        }
    } else {
        $_SESSION['message'] = "Invalid input for department";
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="resources/assets/css/admin_styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/topbar.php' ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
            <div id="overlay"></div>
            <div class="overview">
                <div class="title">
                    <h2 class="section--title">Overview</h2>
                    <select name="date" id="date" class="dropdown">
                        <option value="today">Today</option>
                        <option value="lastweek">Last Week</option>
                        <option value="lastmonth">Last Month</option>
                        <option value="lastyear">Last Year</option>
                        <option value="alltime">All Time</option>
                    </select>
                </div>
                <div class="cards">
                    <div id="addCourse" class="card card-1">

                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Course</button>
                                <h1><?php total_rows('tblcourse') ?> Courses</h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>

                    </div>
                    <div class="card card-1" id="addSubject">

                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Subjects</button>
                                <h1><?php total_rows('tblsubject') ?> Subjects</h1>
                            </div>
                            <i class="ri-file-text-line card--icon--lg"></i>
                        </div>

                    </div>

                    <div class="card card-1" id="addDepartment">

                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Department</button>
                                <h1><?php total_rows("tbldepartment") ?> departments </h1>
                            </div>
                            <i class="ri-user-line card--icon--lg"></i>
                        </div>

                    </div>
                </div>
            </div>

            <?php showMessage() ?>
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">Course</h2>
                </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Total Subjects</th>
                                <th>Total Students</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                        c.name AS course_name,
                        c.departmentID AS department,
                        f.departmentName AS department_name,
                        c.Id AS Id,
                        COUNT(u.Id) AS total_subjects,
                        COUNT(DISTINCT s.Id) AS total_students,
                        c.dateCreated AS date_created
                        FROM tblcourse c
                        LEFT JOIN tblsubject u ON c.Id = u.courseID
                        LEFT JOIN tblstudents s ON c.courseCode = s.courseCode
                        LEFT JOIN tbldepartment f on c.departmentID=f.Id
                        GROUP BY c.Id";

                            $result = fetch($sql);

                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowcourse{$row["Id"]}'>";
                                    echo "<td>" . $row["course_name"] . "</td>";
                                    echo "<td>" . $row["department_name"] . "</td>";
                                    echo "<td>" . $row["total_subjects"] . "</td>";
                                    echo "<td>" . $row["total_students"] . "</td>";
                                    echo "<td>" . $row["date_created"] . "</td>";
                                    echo "<td><span><i class='ri-delete-bin-line delete'data-id='{$row["Id"]}' data-name='course'></i></span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">Subject</h2>
                </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Total Student</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                            c.name AS course_name,
                            u.subjectCode AS subject_code,
                            u.name AS subject_name, u.Id as Id,
                            u.dateCreated AS date_created,
                            COUNT(s.Id) AS total_students
                            FROM tblsubject u
                            LEFT JOIN tblcourse c ON u.courseID = c.Id
                            LEFT JOIN tblstudents s ON c.courseCode = s.courseCode
                            GROUP BY u.Id";
                            $result = fetch($sql);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowsubject{$row["Id"]}' >";
                                    echo "<td>" . $row["subject_code"] . "</td>";
                                    echo "<td>" . $row["subject_name"] . "</td>";
                                    echo "<td>" . $row["course_name"] . "</td>";
                                    echo "<td>" . $row["total_students"] . "</td>";
                                    echo "<td>" . $row["date_created"] . "</td>";
                                    echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='subject'></i></span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">Department</h2>
                </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Total Courses</th>
                                <th>Total Students</th>
                                <th>Total Instructors</th>
                                <th>Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                           f.departmentName AS department_name,
                           f.departmentCode AS department_code,
                           f.Id as Id,
                           f.dateRegistered AS date_created,
                           COUNT(DISTINCT c.Id) AS total_courses,
                           COUNT(DISTINCT s.Id) AS total_students,
                           COUNT(DISTINCT l.Id) AS total_instructors
                       FROM tbldepartment f
                       LEFT JOIN tblcourse c ON f.Id = c.departmentID
                       LEFT JOIN tblstudents s ON f.departmentCode = s.department
                       LEFT JOIN tblinstructor l ON f.departmentCode = l.departmentCode
                       GROUP BY f.Id";

                            $result = fetch($sql);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowdepartment{$row["Id"]}'>";
                                    echo "<td>" . $row["department_code"] . "</td>";
                                    echo "<td>" . $row["department_name"] . "</td>";
                                    echo "<td>" . $row["total_courses"] . "</td>";
                                    echo "<td>" . $row["total_students"] . "</td>";
                                    echo "<td>" . $row["total_instructors"] . "</td>";
                                    echo "<td>" . $row["date_created"] . "</td>";
                                    echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='department'></i></span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <div class="formDiv" id="addCourseForm" style="display:none; ">

            <form method="POST" action="" name="addCourse" enctype="multipart/form-data">
                <div style="display:flex; justify-content:space-around;">
                    <div class="form-title">
                        <p>Add Course</p>
                    </div>
                    <div>
                        <span class="close">&times;</span>
                    </div>
                </div>

                <input type="text" name="courseName" placeholder="Course Name" required>
                <input type="text" name="courseCode" placeholder="Course Code" required>


                <select required name="department">
                    <option value="" selected>Select Department</option>
                    <?php
                    $departmentNames = getDepartmentNames();
                    foreach ($departmentNames as $department) {
                        echo '<option value="' . $department["Id"] . '">' . $department["departmentName"] . '</option>';
                    }
                    ?>
                </select>

                <input type="submit" class="submit" value="Save Course" name="addCourse">
            </form>
        </div>

        <div class="formDiv" id="addSubjectForm" style="display:none; ">
            <form method="POST" action="" name="addSubject" enctype="multipart/form-data">
                <div style="display:flex; justify-content:space-around;">
                    <div class="form-title">
                        <p>Add Subject</p>
                    </div>
                    <div>
                        <span class="close">&times;</span>
                    </div>
                </div>

                <input type="text" name="subjectName" placeholder="Subject Name" required>
                <input type="text" name="subjectCode" placeholder="Subject Code" required>

                <select required name="instructor">
                    <option value="" selected>Assign Instructor</option>
                    <?php
                    $instructorNames = getInstructorNames();
                    foreach ($instructorNames as $instructor) {
                        echo '<option value="' . $instructor["Id"] . '">' . $instructor["firstName"] . ' ' . $instructor["lastName"]  .  '</option>';
                    }
                    ?>
                </select>
                <select required name="course">
                    <option value="" selected>Select Course</option>
                    <?php
                    $courseNames = getCourseNames();
                    foreach ($courseNames as $course) {
                        echo '<option value="' . $course["Id"] . '">' . $course["name"] . '</option>';
                    }
                    ?>
                </select>

                <input type="submit" class="submit" value="Save Subject" name="addSubject">
            </form>
        </div>

        <div class="formDiv" id="addDepartmentForm" style="display:none; ">
            <form method="POST" action="" name="addDepartment" enctype="multipart/form-data">
                <div style="display:flex; justify-content:space-around;">
                    <div class="form-title">
                        <p>Add Department</p>
                    </div>
                    <div>
                        <span class="close">&times;</span>
                    </div>
                </div>
                <input type="text" name="departmentName" placeholder="Department Name" required>
                <input type="text" name="departmentCode" placeholder="Department Code" required>
                <input type="submit" class="submit" value="Save Department" name="addDepartment">
            </form>
        </div>



    </section>

    <?php js_asset(["delete_request", "addCourse", "active_link"]) ?>
</body>

</html>