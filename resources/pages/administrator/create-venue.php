<?php


if (isset($_POST["addVenue"])) {
    $className = htmlspecialchars(trim($_POST['className']));
    $capacity = filter_var($_POST['capacity'], FILTER_VALIDATE_INT);
    $classification = htmlspecialchars(trim($_POST['classification']));

    if (!$className || !$capacity || !$classification) {
        $_SESSION['message'] = "All fields are required and must be valid.";
    } else {
        $dateRegistered = date("Y-m-d");

        try {
            $stmt = $pdo->prepare("SELECT * FROM tblvenue WHERE className = :className");
            $stmt->bindParam(':className', $className);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "Venue Already Exists";
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO tblvenue (className, capacity, classification, dateCreated)
                    VALUES (:className, :capacity, :classification, :dateCreated)"
                );
                $stmt->bindParam(':className', $className);
                $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
                $stmt->bindParam(':classification', $classification);
                $stmt->bindParam(':dateCreated', $dateRegistered);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Venue Inserted Successfully";
                } else {
                    $_SESSION['message'] = "Failed to Insert Venue.";
                }
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Database Error: " . $e->getMessage();
        }
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

            <?php showMessage() ?>
            <div class="table-container">
                <div class="title" id="addClass2">
                    <h2 class="section--title">Instructor Rooms</h2>
                    <button class="add show-form"><i class="ri-add-line"></i>Add Class</button>
                </div>

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Class Name</th>
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
                                foreach ($result as $row)
                                    echo "<tr id='rowvenue{$row["Id"]}'>";
                                echo "<td>" . $row["className"] . "</td>";
                                echo "<td>" . $row["capacity"] . "</td>";
                                echo "<td>" . $row["classification"] . "</td>";
                                echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='venue'></i></span></td>";
                                echo "</tr>";
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="formDiv-venue" id="addClassForm" style="display:none ">
                <form method="POST" action="" name="addVenue" enctype="multipart/form-data">
                    <div style="display:flex; justify-content:space-around;">
                        <div class="form-title">
                            <p>Add Venue</p>
                        </div>
                        <div>
                            <span class="close">&times;</span>
                        </div>
                    </div>
                    <input type="text" name="className" placeholder="Class Name" required>
                    <input type="text" name="capacity" placeholder="Capacity" required>
                    <select required name="classification">
                        <option value="" selected> --Select Room--</option>
                        <option value="laboratory">Laboratory</option>
                        <option value="computerLab">Computer Lab</option>
                        <option value="instructorHall">Instructor Hall</option>
                        <option value="class">Class</option>
                        <option value="office">Office</option>
                    </select>
                    <select required name="Course">
                        <option value="" selected>Select Course</option>
                        <option value="" selected>Bachelor of Science in Information Systems</option>
                        <option value="" selected>Bachelor of Science in Acconting Information Systems</option>
                    </select>
                    <input type="submit" class="submit" value="Save Venue" name="addVenue">
                </form>
            </div>
        </div>
    </section>
    <?php js_asset(["active_link", "delete_request"]) ?>


    <script>
        const show_form = document.querySelectorAll(".show-form")
        const addClassForm = document.getElementById('addClassForm');
        const overlay = document.getElementById('overlay');
        const closeButtons = document.querySelectorAll('#addClassForm .close');
        show_form.forEach((showForm) => {
            showForm.addEventListener('click', function() {
                addClassForm.style.display = 'block';
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';

            });
        })

        closeButtons.forEach(function(closeButton) {
            closeButton.addEventListener('click', function() {
                addClassForm.style.display = 'none';
                overlay.style.display = 'none';
                document.body.style.overflow = 'auto';

            });
        });
    </script>
</body>

</html>


