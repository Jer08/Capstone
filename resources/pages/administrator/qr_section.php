<?php
// session_start();
$server = "localhost";
$username = "root";
$password = "";
$dbname = "qr-attendance";
$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['text'])) {
    $voice = new COM("SAPI.SpVoice"); 
    $text = $_POST['text'];
    $message = "Hi " . $text . ", your attendance has been successfully recorded. Thank you";

    $sql = "INSERT INTO attendance (STUDENTID, TIMEIN) VALUES ('$text', NOW())";
    if ($conn->query($sql) === TRUE) {
        $voice->speak($message); 
    } else {
        $_SESSION['error'] = $conn->error; 
    }
    header("location: index.php");
}

?>

<html>
<head>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>QR Attendance</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <video id="preview" width="100%"></video>
            </div>
            <div class="col-md-6">
                <form action="" method="post" class="form-horizontal">
                    <label>SCAN QR CODE</label>
                    <input type="text" name="text" id="text" readonly="" placeholder="scan qrcode" class="form-control">
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td> ID </td>
                            <td> STUDENT ID </td>
                            <td> TIMEIN </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT ID, STUDENTID, TIMEIN FROM attendance WHERE DATE(TIMEIN) = CURDATE()"; 
                        $query = $conn->query($sql);
                        while ($row = $query->fetch_assoc()) {
                        ?>
                        <tr>
                            <td> <?php echo $row['ID']; ?> </td>
                            <td> <?php echo $row['STUDENTID']; ?> </td> 
                            <td> <?php echo $row['TIMEIN']; ?> </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);  
            } else {
                alert('No camera found'); 
            }
        }).catch(function(e) {
            console.error(e);
        });

        scanner.addListener('scan', function(c) {
            document.getElementById('text').value = c;
            document.forms[0].submit();
        });
    </script>
</body>
</html>

<?php
$conn->close(); 
?>
