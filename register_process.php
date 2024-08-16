<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database configuration
    include("db_config.php");

    // Get input data
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $email = $_POST["email"];

    // Check if the username is unique (not already in the database)
    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        // Insert new user data into the database
        $insert_query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        if (mysqli_query($conn, $insert_query)) {
            echo "Registration successful. Please check your email for verification instructions.";
            // Send verification email here (use Mailgun API)
        } else {
            echo "Registration failed. Please try again later.";
        }
    } else {
        echo "Username already exists. Please choose a different one.";
    }

    mysqli_close($conn);
}
?>
