<?php
require "settings/init.php";  // Assuming init.php handles DB connection

// Get user input from POST (username and password)
$username = $_POST['brugernavn'] ?? null;
$password = $_POST['kodeord'] ?? null;

// Check if both username and password are provided
if ($username && $password) {
    // SQL query to fetch the user details based on username and password
    $query = "SELECT * FROM users WHERE userName = :username AND userPassword = :password";

    // Execute query using the db class
    $binds = [':username' => $username, ':password' => $password];
    $user = $db->sql($query, $binds, true);  // true to fetch as an associative array

    // Check if a user was found
    if ($user) {
        // Fetch userId and currentLevel from the result
        $userId = $user[0]->userId;  // Assuming it's the first result in the array
        $currentLevel = $user[0]->currentLevel;

        $_SESSION["userId"] = $user[0]->userId;

        header("Location: levelSelect.php?userId=".$_SESSION["userId"]);

    } else {
        // If no user is found, return an error message
        echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);

        header("Location: index.php");
    }
} else {
    // If either username or password is missing
    header("Location: index.php");
}
