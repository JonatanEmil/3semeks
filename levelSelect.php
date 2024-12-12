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

    <title><?php echo $level->worldName ?> </title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">

    <link href="css/styles.css" rel="stylesheet" type="text/css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-<?php echo $level->worldColor1 ?>">
    <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center w-100 position-relative">
            <div class="lives-wrapper bg-<?php echo $level->worldColor1 ?>">
                <img src="img/lives.webp"
                     alt="Du har <?php echo $user->hearts; ?> liv"
                     class="lives-picture">
                <span class="heart-count display-1"><?php echo $user->hearts; ?></span>
            </div>
            <p class="display-1 text-center m-0 fw-bold text-white"><?php echo $level->worldName; ?></p>
            <!-- Profile Picture -->
            <div class="profile-wrapper bg-<?php echo $level->worldColor1 ?>">
                <img src="img/<?php echo $user->profilePic; ?>"
                     alt="Profile Picture"
                     class="profile-picture rounded-circle">
            </div>
        </div>
    </div>
</nav>

<?php
$worldGens = $db->sql("SELECT * FROM worlds INNER JOIN levels ON worldId = worldDesign WHERE levelId = $currentLevel ");
$worldGen = $worldGens[0];
?>

<div class=" bg-farve levelSelectBody">
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
</div>
<?php
$levelData = $level; // Pass `$level` to another variable, if needed.
include 'menu.php';
?>


<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Open the modal
    function openModal(modalId) {
        // Hide all modals first
        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.style.display = 'none';
        });
        // Show the modal with the given ID
        document.getElementById(modalId).style.display = 'block';
    }

    // Close the modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }

    // Open and close menu modal
    function openMenu() {
        document.getElementById('menuModal').style.display = 'block';
    }

    function closeMenu() {
        document.getElementById('menuModal').style.display = 'none';
    }

</script>
</body>
</html>
