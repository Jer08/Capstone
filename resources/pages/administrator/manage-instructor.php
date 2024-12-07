<?php

if (isset($_POST["addInstructor"])) {
    // Securely handle input
    $firstName = htmlspecialchars(trim($_POST["firstName"]));
    $lastName = htmlspecialchars(trim($_POST["lastName"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $phoneNumber = htmlspecialchars(trim($_POST["phoneNumber"]));
    $department = htmlspecialchars(trim($_POST["department"]));
    $dateRegistered = date("Y-m-d");
    $password = password_hash("password", PASSWORD_DEFAULT); // Secure password hashing

    if ($email && $firstName && $lastName && $phoneNumber && $department) {
        try {
            // Check if instructor already exists
            $query = $pdo->prepare("SELECT * FROM tblinstructor WHERE emailAddress = :email");
            $query->bindParam(':email', $email);
            $query->execute();

            if ($query->rowCount() > 0) {
                $_SESSION['message'] = "Instructor Already Exists";
            } else {
                // Insert new instructor
                $query = $pdo->prepare("INSERT INTO tblinstructor 
                    (firstName, lastName, emailAddress, password, phoneNo, departmentCode, dateCreated) 
                    VALUES (:firstName, :lastName, :email, :password, :phoneNumber, :department, :dateCreated)");
                $query->bindParam(':firstName', $firstName);
                $query->bindParam(':lastName', $lastName);
                $query->bindParam(':email', $email);
                $query->bindParam(':password', $password);
                $query->bindParam(':phoneNumber', $phoneNumber);
                $query->bindParam(':department', $department);
                $query->bindParam(':dateCreated', $dateRegistered);

                $query->execute();

                $_SESSION['message'] = "Instructor Added Successfully";
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Invalid input. Please check your data.";
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="resources/images/logo/attnlg.png" rel="icon">

    <title>AMS - Dashboard</title>
    <link rel="stylesheet" href="resources/assets/css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">

</head>

<body>
    <?php include "Includes/topbar.php"; ?>

    <section class=main>

        <?php include "Includes/sidebar.php"; ?>

        <div class="main--content">
            <div id="overlay"></div>
            <?php showMessage() ?>
            <div class="table-container">
                <div class="title" id="showButton">
                    <h2 class="section--title">Instructors</h2>
                    <button class="add"><i class="ri-add-line"></i>Add instructor</button>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Phone No</th>
                                <th>Department</th>
                                <th>Date Registered</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $sql = "SELECT * FROM tblinstructor";
                                $result = fetch($sql);
                                if ($result) {
                                    foreach ($result as $row) {
                                        echo "<tr id='rowinstructor{$row["Id"]}'>";
                                        echo "<td>" . $row["firstName"] . "</td>";
                                        echo "<td>" . $row["emailAddress"] . "</td>";
                                        echo "<td>" . $row["phoneNo"] . "</td>";
                                        echo "<td>" . $row["departmentCode"] . "</td>";
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
            <div class="formDiv--" id="form" style="display:none; ">
                <form method="POST" action="" name="addInstructor" enctype="multipart/form-data">
                    <div style="display:flex; justify-content:space-around;">
                        <div class="form-title">
                            <p>Add Instructor</p>
                        </div>
                        <div>
                            <span class="close">&times;</span>
                        </div>
                    </div>
                    <input type="text" name="firstName" placeholder="First Name" required>
                    <input type="text" name="lastName" placeholder="Last Name" required>
                    <input type="email" name="email" placeholder="Email Address" required>
                    <input type="text" name="phoneNumber" placeholder="Phone Number" required>
                    <input type="password" name="password" placeholder="**********" required>

                    <select required name="department">
                        <option value="" selected>Select Department</option>
                        <?php
                        $departmentNames = getDepartmentNames();
                        foreach ($departmentNames as $department) {
                            echo '<option value="' . $department["departmentCode"] . '">' . $department["departmentName"] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" class="submit" value="Save Instructor" name="addInstructor">
                </form>
            </div>



    </section>

    <?php js_asset(["admin_functions", "active_link", "delete_request", "script"]) ?>


</body>

</html>