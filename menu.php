<?php
// Get the current page URL or script name to determine what buttons to display
$currentPage = basename($_SERVER['PHP_SELF']);
if (isset($levelId)) {
    $levels = $db->sql("SELECT * FROM levels INNER JOIN worlds ON worldDesign = worldId WHERE levelId = $levelId");
    $level = $levels[0]; // Retrieve the first result
} else {
    echo "Error: No level ID provided.";
    return; // Stop execution if `$levelId` is not set
}
?>

<!-- Button to trigger the modal -->
<button class="floating-btn bg-<?php echo $level->worldColor2 ?>" id="openMenuBtn" onclick="openMenu()">Menu</button>

<!-- The Modal -->
<div class="modal modal-menu" id="menuModal">
    <div class="modal-content-menu text-center">
        <?php if ($currentPage == "playLevel.php"): ?>
            <!-- Display back to country and level name buttons only on playLevel.php -->
            <span class="level-name" id="levelName"><?php echo $level->worldName . " - Level " . $level->levelId; ?></span>
            <a href="levelSelect.php?userId=<?php echo $_SESSION["userId"]?>"><button class="menu-btn" id="backToCountryBtn"">Tilbage til <?php echo $level->worldName?> </button></a>
        <?php endif; ?>
        <span class="close-btn" onclick="closeMenu()">&times;</span>

        <!-- Always display the info button -->
        <button class="menu-btn" onclick="openModal('infoModal')">Info</button>

        <!-- Display the help button on all pages -->
        <button class="menu-btn" onclick="openModal('helpModal')">Hjælp</button>

        <!-- Display the logout button on all pages -->
        <a href="logud.php"><button class="menu-btn" id="logoutBtn">Log ud</button></a>
    </div>
</div>

<!-- Info Modal -->
<div id="infoModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('infoModal')">&times;</span>
        <h2>Information</h2>
        <img src="img/infograffik.webp" alt="Infografik" style="width: 100%; height: auto; border-radius: 8px;">
    </div>
</div>

<!-- Help Modal -->
<div id="helpModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('helpModal')">&times;</span>
        <h2>Help</h2>
        <video controls style="width: 100%; height: auto;">
            <source src="your-video-url.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>
