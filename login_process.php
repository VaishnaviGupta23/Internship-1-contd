<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database configuration
    include("db_config.php");

    // Get input data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row["password"])) {
            if ($row["is_verified"] == 1) {
                $_SESSION["username"] = $username;
                header("Location: profile.php");
            } else {
                echo "Please verify your email address to log in.";
            }
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }

    mysqli_close($conn);
}
?>
