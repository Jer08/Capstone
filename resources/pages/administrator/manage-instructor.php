<?php
if (isset($_POST["addInstructor"])) {
    $firstName = htmlspecialchars(trim($_POST["firstName"]));
    $lastName = htmlspecialchars(trim($_POST["lastName"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $phoneNumber = htmlspecialchars(trim($_POST["phoneNumber"]));
    $password = password_hash("password", PASSWORD_DEFAULT); 

    if ($email && $firstName && $lastName && $phoneNumber) {
        try {
            $query = $pdo->prepare("SELECT * FROM tblinstructor WHERE emailAddress = :email");
            $query->bindParam(':email', $email);
            $query->execute();

            if ($query->rowCount() > 0) {
                $_SESSION['message'] = "Instructor Already Exists";
            } else {
                $query = $pdo->prepare("INSERT INTO tblinstructor
                    (firstName, lastName, emailAddress, password, phoneNo, dateCreated) 
                    VALUES (:firstName, :lastName, :email, :password, :phoneNumber, :faculty, :dateCreated)");
                $query->bindParam(':firstName', $firstName);
                $query->bindParam(':lastName', $lastName);
                $query->bindParam(':email', $email);
                $query->bindParam(':phoneNumber', $phoneNumber);
                $query->bindParam(':password', $password);


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

if (isset($_POST["editInstructor"])) {
    $id = intval($_POST["id"]);
    $firstName = htmlspecialchars(trim($_POST["firstName"]));
    $lastName = htmlspecialchars(trim($_POST["lastName"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $phoneNumber = htmlspecialchars(trim($_POST["phoneNumber"]));

    if ($id && $email && $firstName && $lastName && $phoneNumber) {
        try {
            $query = $pdo->prepare("UPDATE tblinstructor
                SET firstName = :firstName, 
                    lastName = :lastName, 
                    emailAddress = :email, 
                    phoneNo = :phoneNumber, 
                WHERE Id = :id");
            $query->bindParam(':firstName', $firstName);
            $query->bindParam(':lastName', $lastName);
            $query->bindParam(':email', $email);
            $query->bindParam(':phoneNumber', $phoneNumber);
            $query->bindParam(':id', $id);
            $query->execute();

            $_SESSION['message'] = "Instructor Updated Successfully";
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

    <section class="main">

        <?php include "Includes/sidebar.php"; ?>

        <div class="main--content">
            <div id="overlay" class="overlay"></div>
            <?php showMessage() ?>
            <div class="table-container">
                <div class="title" id="showButton">
                    <h2 class="section--title">Instructors</h2>
                    <button class="add" id="addInstructorButton"><i class="ri-add-line"></i>Add Instructor</button>
                </div>
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
                            <?php
                            $sql = "SELECT * FROM tblinstructor";
                            $result = fetch($sql);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowinstructor{$row["Id"]}'>";
                                    echo "<td>" . $row["firstName"] . " " . $row["lastName"] . "</td>";
                                    echo "<td>" . $row["emailAddress"] . "</td>";
                                    echo "<td>" . $row["phoneNo"] . "</td>";
                                    echo "<td>" . $row["dateCreated"] . "</td>";
                                    echo "<td>
                                            <span><i class='ri-edit-line edit' 
                                                data-id='{$row["Id"]}' 
                                                data-firstname='{$row["firstName"]}' 
                                                data-lastname='{$row["lastName"]}' 
                                                data-email='{$row["emailAddress"]}' 
                                                data-phone='{$row["phoneNo"]}' 
                                            <span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='lecture'></i></span>
                                          </td>";
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

            <div class="formDiv--" id="form" style="display:none;">
                <form method="POST" action="" name="addLecture" enctype="multipart/form-data">
                    <div style="display:flex; justify-content:space-around;">
                        <div class="form-title">
                            <p id="form-title">Add Instructor</p>
                        </div>
                        <div>
                            <span class="close">&times;</span>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="lectureId">
                    <input type="text" name="firstName" id="firstName" placeholder="First Name" required>
                    <input type="text" name="lastName" id="lastName" placeholder="Last Name" required>
                    <input type="email" name="email" id="email" placeholder="Email Address" required>
                    <input type="text" name="phoneNumber" id="phoneNumber" placeholder="Phone Number" required>
                    <input type="password" name="password" id="password" placeholder="Password" required>

                    <input type="submit" class="submit" value="Save Instructor" name="editInstructor" id="submitBtn">
                </form>
            </div>
        </div>
    </section>

    <?php js_asset(["admin_functions", "active_link", "delete_request", "script"]) ?>

    <script>
        const addInstructorButton = document.getElementById("addInstructorButton");
        const editButtons = document.querySelectorAll(".edit");
        const formDiv = document.getElementById("form");
        const closeButton = document.querySelector(".close");
        const overlay = document.getElementById("overlay");
        const formTitle = document.getElementById("form-title");
        const submitBtn = document.getElementById("submitBtn");

        addInstructorButton.addEventListener("click", function () {
            formTitle.innerText = "Add Instructor";
            submitBtn.setAttribute("name", "addLecture");
            formDiv.style.display = "block";
            overlay.style.display = "block"; 
            document.getElementById("lectureId").value = '';
            document.body.style.overflow = "hidden";
        });

        editButtons.forEach(button => {
            button.addEventListener("click", function () {
                const id = button.getAttribute("data-id");
                const firstName = button.getAttribute("data-firstname");
                const lastName = button.getAttribute("data-lastname");
                const email = button.getAttribute("data-email");
                const phone = button.getAttribute("data-phone");

                formTitle.innerText = "Edit Instructor";
                submitBtn.setAttribute("name", "editInstructor");
                document.getElementById("instructorId").value = id;
                document.getElementById("firstName").value = firstName;
                document.getElementById("lastName").value = lastName;
                document.getElementById("email").value = email;
                document.getElementById("phoneNumber").value = phone;

                formDiv.style.display = "block";
                overlay.style.display = "block"; 
                document.body.style.overflow = "hidden";
            });
        });

        closeButton.addEventListener("click", function () {
            formDiv.style.display = "none";
            overlay.style.display = "none"; 
            document.body.style.overflow = "auto";
        });

        overlay.addEventListener("click", function () {
            formDiv.style.display = "none";
            overlay.style.display = "none"; 
            document.body.style.overflow = "auto";
        });
    </script>

</body>

</html>