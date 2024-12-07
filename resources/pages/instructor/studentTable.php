

<div class="table">
    <table>
        <thead>
            <tr>
                <th>ID No</th>
                <th>Name</th>
                <th>Course</th>
                <th>Subject</th>
                <th>Venue</th>
                <th>Attendance</th>
                <th>Settings</th>
            </tr>
        </thead>
        <tbody id="studentTableContainer">
            <?php
            if (isset($_POST['courseID']) && isset($_POST['subjectID']) && isset($_POST['venueID'])) {

                $courseID = $_POST['courseID'];
                $subjectID = $_POST['subjectID'];
                $venueID = $_POST['venueID'];

                $sql = "SELECT * FROM tblStudents WHERE courseCode = '$courseID'";
                $result = fetch($sql);

                if ($result) {
                    foreach ($result as $row) {
                        echo "<tr>";
                        $idNumber = $row["idNumber"];
                        echo "<td>" . $idNumber . "</td>";
                        echo "<td>" . $row["firstName"] . $row["lastName"] . "</td>";
                        echo "<td>" . $courseID . "</td>";
                        echo "<td>" . $subjectID . "</td>";
                        echo "<td>" . $venueID . "</td>";
                        echo "<td>Absent</td>"; 
                        echo "<td><span><i class='ri-edit-line edit'></i><i class='ri-delete-bin-line delete'></i></span></td>";
                        echo "</tr>";
                    }

                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>
