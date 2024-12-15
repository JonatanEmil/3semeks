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
<button class="floating-btn position-fixed border-0 p-3 h3 bg-<?php echo $level->worldColor2 ?>" id="openMenuBtn" onclick="openMenu()">Menu</button>

<!-- The Modal -->
<div class="modal modal-menu w-100 h-100 overflow-auto bg-black bg-opacity-50" id="menuModal">
    <div class="modal-content-menu text-center p-3 border-2 border rounded position-relative">
        <?php if ($currentPage == "playLevel.php"): ?>
            <!-- Display back to country and level name buttons only on playLevel.php -->
            <span class="d-block mt-3 display-6 text-black" id="levelName"><?php echo $level->worldName . " - Level " . $level->levelId; ?></span>
            <a href="levelSelect.php?userId=<?php echo $_SESSION["userId"]?>"><button class="menu-btn my-3 mx-auto border-0 p-2 rounded w-100 bg-primary text-white h3" id="backToCountryBtn"">Tilbage til <?php echo $level->worldName?> </button></a>
        <?php endif; ?>
        <span class="close-btn" onclick="closeMenu()">&times;</span>

        <!-- Always display the info button -->
        <button class="menu-btn my-3 mx-auto border-0 p-2 rounded w-100 bg-primary text-white h3" onclick="openModal('infoModal')">Info</button>

        <!-- Display the help button on all pages -->
        <button class="menu-btn my-3 mx-auto p-2 border-0 rounded w-100 bg-primary text-white h3" onclick="openModal('helpModal')">Hjælp</button>

        <!-- Display the logout button on all pages -->
        <a href="logud.php"><button class="menu-btn my-3 mx-auto p-2 border-0 rounded w-100 bg-primary text-white h3" id="logoutBtn">Log ud</button></a>
    </div>
</div>

<!-- Info Modal -->
<div id="infoModal" class="modal">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('infoModal')">&times;</span>
        <h2>Information</h2>
        <img src="img/infograffik.webp" alt="Infografik" style=" height: auto; border-radius: 8px;">
    </div>
    </div>
</div>

<!-- Help Modal -->
<div id="helpModal" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('helpModal')">&times;</span>
        <h2>Help</h2>
        <video controls style="width: 100%; height: auto;">
            <source src="vid/matchthree.webm" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    </div>
</div>
