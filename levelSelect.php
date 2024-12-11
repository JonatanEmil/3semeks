<?php
require "settings/init.php";
session_start();

if (!empty($_GET["userId"])) {
    $userId = ($_GET['userId']);
}
$users = $db->sql("SELECT * FROM users INNER JOIN levels ON currentLevel = levelId WHERE userId = $userId");
$user = $users[0]; // Access the first (and presumably only) result
$currentLevel = $user->currentLevel;
$levels = $db->sql("SELECT * FROM levels INNER JOIN worlds ON worldDesign = worldId WHERE levelId = $currentLevel");
$level = $levels[0];
$worldLevels = $db->sql("SELECT * FROM levels WHERE worldDesign = $level->worldDesign");
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title><?php echo $level->worldName ?> World</title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">

    <link href="css/styles.css" rel="stylesheet" type="text/css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body class="d-flex justify-content-center align-items-center bg-farve levelSelectBody">
<?php
$worldGens = $db->sql("SELECT * FROM worlds INNER JOIN levels ON worldId = worldDesign WHERE levelId = $currentLevel ");
$worldGen = $worldGens[0];
include 'navbar.php';
?>
<div class="level-container">
    <!-- Background Image -->
    <img src="img/<?php echo $worldGen->worldBackgroundImg ?>" alt="Background" class="img-fluid">

    <?php
    // Iterate through the levels in the current world
    foreach ($worldLevels as $level) {
        // Determine the correct image based on level status
        if ($level->levelId < $currentLevel) {
            $levelImage = "img/" . $worldGen->levelsClearImg; // Cleared image
            $extraClass = "cleared-level"; // CSS class for cleared levels
        } elseif ($level->levelId == $currentLevel) {
            $levelImage = "img/" . $worldGen->levelsImg; // Playable image
            $extraClass = "current-level"; // CSS class for the current level
        } else {
            $levelImage = "img/" . $worldGen->levelsImg; // Default locked image if needed
            $extraClass = "locked-level"; // CSS class for locked levels
        }

        // Check if the level is playable
        $isPlayable = $level->levelId == $currentLevel;
        $linkStart = $isPlayable ? '<a href="playLevel.php?levelId=' . $level->levelId . '&userId=' . $user->userId . '">' : '';
        $linkEnd = $isPlayable ? '</a>' : '';

        // Output the level icon
        echo $linkStart .
            '<img src="' . $levelImage . '" 
                  alt="Level ' . $level->levelId . '" 
                  class="level-icon ' . $extraClass . '"
                  style="left: ' . $level->xPos . 'px; 
                         top: ' . $level->yPos . 'px;">' .
            $linkEnd;
    }
    ?>
</div>


<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
