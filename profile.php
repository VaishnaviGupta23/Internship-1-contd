<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// If the user is logged in, retrieve their username
$username = $_SESSION["username"];

// Include your database connection configuration
include("db_config.php");

// Query to retrieve the user's name based on their username
$query = "SELECT username, name FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $name = $row["name"];
} else {
    // Handle the case where the user's name is not found
    $name = "Name not found";
}

// You can include a header template here with navigation links, etc.
// Example: include("header.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <?php require_once("head.php")?>
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
        <p>This is your profile page. You can add more information here.</p>
        <a href="logout.php" class="btn btn-primary">Logout</a>
    </div>
</body>
</html>
