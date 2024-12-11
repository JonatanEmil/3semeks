<?php
if (!empty($_GET["userId"])) {
    $userId = ($_GET['userId']);
}
$users = $db->sql("SELECT * FROM users INNER JOIN levels ON currentLevel = levelId WHERE userId = $userId");
$user = $users[0]; // Access the first (and presumably only) result
$currentLevel = $user->currentLevel;
// Assume session_start() and user data are already set up in the main page
// Fetch user info (assuming $db is your database connection, and user is already retrieved)
$userProfilePic = $user->profilePic; // User's profile picture
$creatureImg = ''; // Default creature image

// Fetch creature image for the current world
// This query will pull the creature image for the world of the current level
$levelQuery = "SELECT * FROM levels 
               INNER JOIN worlds ON worldDesign = worldId 
               WHERE levelId = {$currentLevel}";
$levelResult = $db->sql($levelQuery);
if ($levelResult) {
    $level = $levelResult[0]; // Assuming there’s always a result
    $creatureImg = $level->worldFriend; // Creature image for the current world
    $levelMoves = $level->levelMoves;
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <title><?php echo isset($level) ? $level->worldName : 'Game'; ?></title>
    <!-- Include your stylesheets here -->
</head>
<body>
<!-- Navbar -->

<nav class="navbar bg-body-tertiary">
    <div class="container-fluid d-flex justify-content-between">
        <?php
        // Get the current page
        $currentPage = basename($_SERVER['PHP_SELF']);

        // Show profile picture if on levelSelect.php
        if ($currentPage == 'levelSelect.php'): ?>
            <div class="circle bg-farve">
                <p class="display-1" id="moves"><?php echo $levelMoves ?></p>
            </div>
            <div>

            </div>
            <div>
                <img src="img/<?php echo $userProfilePic ?>" alt="Profile Picture" class="rounded-circle"
                     style="width: 40px; height: 40px;">
            </div>
        <?php
        // Show creature image if on playLevel.php
        elseif ($currentPage == 'playLevel.php'): ?>
            <div class="circle bg-farve">
                <p class="display-1" id="moves"><?php echo $levelMoves ?></p>
            </div>
            <div>

            </div>
            <div>

            <img src="img/<?php echo $creatureImg ?>" alt="Creature Image" class="rounded-circle"
                 style="width: 40px; height: 40px;">
            </div>
        <?php endif; ?>
    </div>
</nav>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top mb-5">
    <div class="container-fluid">
        <!-- Dynamically show content based on the current page -->
        <a class="navbar-brand" href="#">
            <?php
            // Get the current page
            $currentPage = basename($_SERVER['PHP_SELF']);

            // Show profile picture if on levelSelect.php
            if ($currentPage == 'levelSelect.php'): ?>
                <img src="img/<?php echo $userProfilePic ?>" alt="Profile Picture" class="rounded-circle"
                     style="width: 40px; height: 40px;">
            <?php
            // Show creature image if on playLevel.php
            elseif ($currentPage == 'playLevel.php'): ?>
                <img src="img/<?php echo $creatureImg ?>" alt="Creature Image" class="rounded-circle"
                     style="width: 40px; height: 40px;">
            <?php endif; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="levelSelect.php">Level Select</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="playLevel.php">Play Level</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logud.php">Log Out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<!-- Bootstrap JS & Dependencies -->
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>