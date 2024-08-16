<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = 'Pass@12345';
$db_name = 'smalldb';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['verify_token'])) {
    $token = $_GET['verify_token'];

    $sql = "SELECT email FROM register WHERE verify_token = '$verify_token'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        $sql = "UPDATE register SET status = 'active' WHERE email = '$email'";
        if ($conn->query($sql) === TRUE) {
			
            $sql = "DELETE FROM register WHERE verify_token = '$verify_token'";
            $conn->query($sql);

            echo "Email verified successfully! You can now <a href='login.php'>Login</a>.";
        } else {
            echo "Error activating your account: " . $conn->error;
        }
    } else {
        echo "Invalid or expired verification token.";
    }
} else {
    echo "Verification token not provided.";
}

$conn->close();
?>
