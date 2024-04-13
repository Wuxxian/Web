<?php
// Check if session is not already started
if (session_status() == PHP_SESSION_NONE) {
    // Set session save path explicitly
    session_save_path("/var/lib/php/sessions");
    // Start the session
    session_start();
}

include "db_conn.php";

// Check if the request method is set before accessing it
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required POST variables are set
    if (isset($_POST["username"]) && isset($_POST["pwd"])) {
        $username = $_POST["username"];
        $pwd = $_POST["pwd"];

        // Validate input
        if (empty($username) || empty($pwd)) {
            header("Location: login.php?error=credentialsrequired");
            exit();
        }

        // Sanitize input
        $username = mysqli_real_escape_string($conn, $username);

        // Query database to fetch user details
        $sql = "SELECT * FROM registered_user WHERE Username ='$username'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Verify the password
            if (password_verify($pwd, $row['Password'])) {
                $_SESSION["Username"] = $username;
                if ($row["User_Type"] == "User") {
                    header("Location: profilePage.php");
                    exit();
                } elseif ($row["User_Type"] == "Admin") {
                    header("Location: admin.php");
                    exit();
                } else {
                    header("Location: login.php?error=invalidusertype");
                    exit();
                }
            } else {
                header("Location: login.php?error=incorrectcredentials");
                exit();
            }
        } else {
            header("Location: login.php?error=usernotfound");
            exit();
        }
    } else {
        header("Location: login.php?error=missingcredentials");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>

