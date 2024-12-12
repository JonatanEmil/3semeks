<?php
require "settings/init.php"; // Assuming this includes database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the userId and levelId from the POST request
    $userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;
    $levelId = isset($_POST['levelId']) ? intval($_POST['levelId']) : 0;

    if ($userId > 0 && $levelId > 0) {
        // Query to increment the current level
        $sql = "UPDATE users SET currentLevel = currentLevel + 1 WHERE userId = $userId";

        // Prepare the statement and execute
        $stmt = $db->sql($sql);
        $stmt->execute([$userId]);

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            echo "Level incremented successfully.";
        } else {
            echo "Failed to increment level.";
        }
    } else {
        echo "Invalid data.";
    }
} else {
    echo "Invalid request method.";
}
?>