<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="resources/images/logo/attnlg.png" rel="icon">
    <title>Dashboard</title>
    <link rel="stylesheet" href="resources/assets/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/topbar.php'; ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
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
                    <div class="card card-1">

                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Registered Students</h5>
                                <h1><?php total_rows('tblstudents') ?></h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>

                    </div>
                    <div class="card card-1">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Subjects</h5>
                                <h1><?php total_rows("tblsubject") ?></h1>
                            </div>
                            <i class="ri-file-text-line card--icon--lg"></i>
                        </div>
                    </div>

                    <div class="card card-1">

                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Registered Instructors</h5>
                                <h1><?php total_rows('tblinstructor') ?></h1>
                            </div>
                            <i class="ri-user-line card--icon--lg"></i>
                        </div>

                    </div>
                </div>
            </div>

            <div class="table-container">
                <a href="manage-instructor" style="text-decoration:none;">
                    <div class="title">
                        <h2 class="section--title">Instructors</h2>
                        <button class="add"><i class="ri-add-line"></i>Add instructor</button>
                    </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Phone No</th>
                                <th>Date Registered</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                            <?php
                            $sql = "SELECT * FROM tblinstructor";
                            $stmt = $pdo->query($sql);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowinstructor{$row["Id"]}'>";
                                    echo "<td>" . $row["firstName"] . "</td>";
                                    echo "<td>" . $row["emailAddress"] . "</td>";
                                    echo "<td>" . $row["phoneNo"] . "</td>";
                                    echo "<td>" . $row["dateCreated"] . "</td>";
                                    echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='instructor'></i></span></td>";
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
                <a href="manage-students" style="text-decoration:none;">
                    <div class="title">
                        <h2 class="section--title">Students</h2>
                        <button class="add"><i class="ri-add-line"></i>Add Student</button>
                    </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID No</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Email</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tblstudents";
                            $stmt = $pdo->query($sql);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowstudents{$row["Id"]}'>";
                                    echo "<td>" . $row["idNumber"] . "</td>";
                                    echo "<td>" . $row["firstName"] . "</td>";
                                    echo "<td>" . $row["courseCode"] . "</td>";
                                    echo "<td>" . $row["email"] . "</td>";
                                    echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='students'></i></span></td>";
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
                <a href="create-venue" style="text-decoration:none;">
                    <div class="title">
                        <h2 class="section--title">Instructor Rooms</h2>
                        <button class="add"><i class="ri-add-line"></i>Add room</button>
                    </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Current Status</th>
                                <th>Capacity</th>
                                <th>Classification</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tblvenue";
                            $stmt = $pdo->query($sql);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowvenue{$row["Id"]}'>";
                                    echo "<td>" . $row["className"] . "</td>";
                                    echo "<td>" . $row["currentStatus"] . "</td>";
                                    echo "<td>" . $row["capacity"] . "</td>";
                                    echo "<td>" . $row["classification"] . "</td>";
                                    echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='venue'></i></span></td>";
                                    echo "</tr>";
                                }
                            } else {

                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="table-container">
                <a href="manage-course" style="text-decoration:none;">
                    <div class="title">
                        <h2 class="section--title">Courses</h2>
                        <button class="add"><i class="ri-add-line"></i>Add Course</button>
                    </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Total Subjects</th>
                                <th>Total Students</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                        c.name AS course_name,c.Id AS Id,
                        COUNT(u.ID) AS total_subjects,
                        COUNT(DISTINCT s.Id) AS total_students,
                        c.dateCreated AS date_created
                        FROM tblcourse c
                        LEFT JOIN tblsubject u ON c.ID = u.courseID
                        LEFT JOIN tblstudents s ON c.courseCode = s.courseCode
                        GROUP BY c.ID";
                            $stmt = $pdo->query($sql);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowcourse{$row["Id"]}'>";
                                    echo "<td>" . $row["course_name"] . "</td>";
                                    echo "<td>" . $row["total_subjects"] . "</td>";
                                    echo "<td>" . $row["total_students"] . "</td>";
                                    echo "<td>" . $row["date_created"] . "</td>";
                                    echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='course'></i></span></td>";
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
    </section>

    <?php js_asset(["active_link", "delete_request"]) ?>


</body>

</html>