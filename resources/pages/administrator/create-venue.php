<?php
if (isset($_POST["addRoom"]) || isset($_POST["editRoom"])) {
    $className = htmlspecialchars(trim($_POST['className']));
    $capacity = filter_var($_POST['capacity'], FILTER_VALIDATE_INT);
    $classification = htmlspecialchars(trim($_POST['classification']));
    $venueId = $_POST['roomId'] ?? null;

    if (!$className || !$capacity || !$classification) {
        $_SESSION['message'] = "All fields are required and must be valid.";
    } else {
        $dateRegistered = date("Y-m-d");

        try {
            if ($venueId) {
                $stmt = $pdo->prepare(
                    "UPDATE tblroom SET className = :className, capacity = :capacity, classification = :classification WHERE Id = :roomId"
                );
                $stmt->bindParam(':venueId', $roomId, PDO::PARAM_INT);
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO tblroom (className, capacity, classification, dateCreated)
                    VALUES (:className, :capacity, :classification, :dateCreated)"
                );
                $stmt->bindParam(':dateCreated', $dateRegistered);
            }
            $stmt->bindParam(':className', $className);
            $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
            $stmt->bindParam(':classification', $classification);

            if ($stmt->execute()) {
                $_SESSION['message'] = $roomId ? "Room Updated Successfully" : "Room Inserted Successfully";
            } else {
                $_SESSION['message'] = "Failed to Save Room.";
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
    <link href="resources/images/logo/bpc-logo.png" rel="icon">
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

            <?php showMessage() ?>
            <div class="table-container">
                <div class="title" id="addClass2">
                    <h2 class="section--title">Rooms</h2>
                    <button class="add show-form"><i class="ri-add-line"></i>Add Class</button>
                </div>

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Capacity</th>
                                <th>Classification</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tblroom";
                            $stmt = $pdo->query($sql);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if ($result) {
                                foreach ($result as $row)
                                    echo "<tr id='rowvenue{$row["Id"]}'>";
                                echo "<td>" . $row["className"] . "</td>";
                                echo "<td>" . $row["capacity"] . "</td>";
                                echo "<td>" . $row["classification"] . "</td>";
                                echo "<td>
                                        <span><i class='ri-pencil-line edit' data-id='{$row["Id"]}' data-name='room'></i></span>
                                        <span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='room'></i></span>
                                        </td>";
                                echo "</tr>";
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="formDiv-venue" id="addClassForm" style="display:none;">
                <form method="POST" action="" name="addRoom" enctype="multipart/form-data">
                    <div style="display:flex; justify-content:space-around;">
                        <div class="form-title">
                            <p id="formTitle">Add Room</p>
                        </div>
                        <div>
                            <span class="close">&times;</span>
                        </div>
                    </div>
                    <input type="hidden" name="venueId" id="venueId">
                    <input type="text" name="className" id="className" placeholder="Class Name" required>
                    <input type="text" name="capacity" id="capacity" placeholder="Capacity" required>
                    <select name="classification" id="classification" required>
                        <option value="" selected> --Select Class Type--</option>
                        <option value="computerLab">Computer Lab</option>
                        <option value="class">Class Room</option>
                    </select>

                    <input type="submit" class="submit" value="Save Room" name="addRoom">
                </form>
            </div>
        </div>
    </section>
    <?php js_asset(["active_link", "delete_request"]) ?>

    <script>
        const show_form = document.querySelectorAll(".show-form");

        const addClassForm = document.getElementById('addClassForm');
        const overlay = document.getElementById('overlay');
        const closeButtons = document.querySelectorAll('#addClassForm .close');
        show_form.forEach((showForm) => {
            showForm.addEventListener('click', function() {
                addClassForm.style.display = 'block'; 
                overlay.style.display = 'block'; 
                document.body.style.overflow = 'hidden';
                document.getElementById('formTitle').innerText = "Add Room";
                document.getElementById('roomId').value = ""; 
            });
        });

        closeButtons.forEach(function(closeButton) {
            closeButton.addEventListener('click', function() {
                addClassForm.style.display = 'none'; 
                overlay.style.display = 'none'; 
                document.body.style.overflow = 'auto'; 
            });
        });

        const editButtons = document.querySelectorAll('.edit');
        editButtons.forEach((editButton) => {
            editButton.addEventListener('click', function() {
                const roomId = editButton.getAttribute('data-id');
                
                document.getElementById('formTitle').innerText = "Edit Room";
                document.getElementById('roomId').value = roomId;
                document.getElementById('className').value = "Room " + roomId; 
                document.getElementById('capacity').value = 50;
                document.getElementById('classification').value = "class"; 
                
                addClassForm.style.display = 'block'; 
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden'; 
            });
        });
    </script>
</body>

</html>
