<?php
require "settings/init.php";
session_start();

if (!empty($_GET["levelId"])) {
    $levelId = ($_GET['levelId']);
} else {
    die('Level ID is missing.');
}

$levels = $db->sql("SELECT * FROM levels INNER JOIN worlds ON worldDesign = WorldId WHERE levelId = $levelId");
$level = $levels[0]; // Access the first (and presumably only) result
$world = $level->worldName;
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Level <?php echo $level->levelId ?></title>

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
            <div class="profile-wrapper position-absolute bg-<?php echo $level->worldColor1 ?> p-2">
                <img src="img/<?php echo $level->worldFriend; ?>"
                     alt="Profile Picture"
                     class="profile-picture object-fit-cover rounded-circle">
                <span id="health" class="position-absolute display-5 fw-bold text-black">Glæde:<br>0/100</span>
            </div>
            <div class="moves-wrapper position-absolute bg-<?php echo $level->worldColor1 ?> p-2">
                <div class="profile-picture object-fit-cover rounded-circle d-flex justify-content-center display-5 fw-bold text-black" id="moves">
                    Træk <?php echo $level->moves; ?>
                </div>
            </div>
            <p class="display-1 text-center m-0 fw-bold">Level <?php echo $level->levelId; ?></p>
            <!-- Profile Picture -->
        </div>
    </div>
</nav>
<div class=" text-white vh-100" style="background-image: url('img/<?php echo $level->worldLevelImg ?>'); background-size: cover; background-position: center;">
    <div class="row g-2">
        <div id="game-container">
            <div id="grid" class="bg-opacity-50 bg-black my-0 mx-auto d-grid gap-1 w-100"></div>
        </div>
    </div>
</div>
<!-- Include the menu -->
<?php include 'menu.php'; ?>

<div class="modal fade" id="winModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title h1" id="winModalLabel">TILYKKE, DU VANDT!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body h4">
                <p><?php echo($level->levelFact); ?></p>
                <p class="text-break" ><a target="_blank" href="<?php echo($level->levelFactSource); ?>">Kilde</a></p>
            </div>
            <div class="modal-footer">
                <a href="logud.php">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Log Ud</button>
                </a>
                <a href="levelSelect.php?&userId=<?php echo $_SESSION["userId"] ?> ">
                    <button type="button" class="btn btn-primary ">Tilbage til <?php echo $world ?> </button>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="loseModal" tabindex="-1" aria-labelledby="loseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title h1" id="loseModalLabel">ØV, du tabte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer h4">
                <a href="logud.php">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Log Ud</button>
                </a>
                <a href="levelSelect.php?&userId=<?php echo $_SESSION["userId"] ?> ">
                    <button type="button" class="btn btn-primary">Tilbage til <?php echo $world ?> </button>
                </a>
                <a href="playLevel.php?levelId=<?php echo $level->levelId ?>&userId=<?php echo $_SESSION["userId"] ?>">
                    <button type="button" class="btn btn-primary">Prøv igen</button>
                </a>
            </div>
        </div>
    </div>
</div>
<script>

    const winModal = document.getElementById('winModal');
    const loseModal = document.getElementById('loseModal');
    const myInput = document.getElementById('myInput');

    winModal.addEventListener('shown.bs.modal', () => {
        myInput.focus()
    })
    loseModal.addEventListener('shown.bs.modal', () => {
        myInput.focus()
    })

    const grid = [];
    const gridSize = 7;
    const tileTypes = 5; // Number of tile colors
    let selectedTile = null; // Track the currently selected tile
    let health = 0;
    //const moves = document.getElementById("moves");
    let moves = <?php echo $level->moves ?>;
    console.log(moves);

    // Initialize the game grid
    function initGrid() {
        grid.length = 0; // Clear the existing grid array
        for (let i = 0; i < gridSize; i++) {
            const row = [];
            for (let j = 0; j < gridSize; j++) {
                const tile = Math.floor(Math.random() * tileTypes); // Random tile type
                row.push(tile);
            }
            grid.push(row);
        }

        // Ensure no matches are present
        removeInitialMatches();
        renderGrid();
        checkGameStatus();
    }

    function removeInitialMatches() {
        for (let row = 0; row < gridSize; row++) {
            for (let col = 0; col < gridSize; col++) {
                do {
                    grid[row][col] = Math.floor(Math.random() * tileTypes);
                } while (
                    (col > 1 && grid[row][col] === grid[row][col - 1] && grid[row][col] === grid[row][col - 2]) ||
                    (row > 1 && grid[row][col] === grid[row - 1][col] && grid[row][col] === grid[row - 2][col])
                    );
            }
        }
    }


    let levelIncremented = false; // Prevent multiple increments

    function checkGameStatus() {
        if (health >= 100 && !levelIncremented) {
            levelIncremented = true; // Set guard to true

            // Send AJAX request to increment the currentLevel
            incrementLevel();

            // Show the win modal
            const winModal = new bootstrap.Modal(document.getElementById('winModal'));
            winModal.show();
        }

        if (moves <= 0 && !levelIncremented) {
            const loseModal = new bootstrap.Modal(document.getElementById('loseModal'));
            loseModal.show();
        }
    }

    // Function to send AJAX request to increment the level
    function incrementLevel() {
        const userId = <?php echo $_SESSION["userId"]; ?>; // Assuming the userId is available in session
        const levelId = <?php echo $level->levelId; ?>; // Assuming levelId is available

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'incrementLevel.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Prepare the data to send to the server
        const data = `userId=${userId}&levelId=${levelId}`;

        // Send the data
        xhr.send(data);

        // Handle the response from the server
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log('Level incremented successfully');
            } else {
                console.error('Failed to increment level');
            }
        };
    }

    // Render the grid in the DOM
    function renderGrid() {
        const gridElement = document.getElementById('grid');
        grid.forEach((row, rowIndex) => {
            row.forEach((tile, colIndex) => {
                let tileElement = document.querySelector(`[data-row="${rowIndex}"][data-col="${colIndex}"]`);
                if (!tileElement) {
                    // Create the tile if it doesn't exist
                    tileElement = document.createElement('div');
                    tileElement.className = 'tile';
                    tileElement.dataset.row = rowIndex;
                    tileElement.dataset.col = colIndex;
                    tileElement.addEventListener('click', onTileClick);
                    gridElement.appendChild(tileElement);
                }
                // Update the tile's appearance
                tileElement.style.backgroundImage = getTileImg(tile);
            });
        });
    }

    // Map tile types to colors
    function getTileImg(type) {
        const images = [
            'url("img/bee.webp")',
            'url("img/coal.webp")',
            'url("img/heart.webp")',
            'url("img/plant.webp")',
            'url("img/solarcell.webp")',
            'url("img/svane.webp")',
            'url("img/watermelon.webp")',
            'url("img/windmill.webp")',
        ];
        return images[type];
    }

    // Handle tile clicks
    function onTileClick(event) {
        const row = parseInt(event.target.dataset.row);
        const col = parseInt(event.target.dataset.col);

        console.log('Tile clicked:', row, col); // Debug log to check selection

        if (!selectedTile) {
            // Select the first tile
            selectedTile = {row, col};
            event.target.classList.add('selected');
        } else {
            // Swap tiles
            const targetRow = selectedTile.row;
            const targetCol = selectedTile.col;
            swapTiles(row, col, targetRow, targetCol);
            selectedTile = null;
            document.querySelectorAll('.tile').forEach(tile => tile.classList.remove('selected'));
            checkMatches(); // Check for matches after swapping
        }
    }

    // Swap two tiles
    function swapTiles(row1, col1, row2, col2) {
        const temp = grid[row1][col1];
        grid[row1][col1] = grid[row2][col2];
        grid[row2][col2] = temp;

        moves--;
        console.log(moves); // Debugging log for moves
        document.getElementById('moves').innerText = `Træk ${moves}`;
        renderGrid(); // Re-render the grid after the swap
    }

    // Check for matches
    let matchCheckInProgress = false;

    function checkMatches() {
        if (matchCheckInProgress) return; // Prevent multiple simultaneous checks
        matchCheckInProgress = true;

        const matches = new Set();
        // Check rows for matches
        for (let row = 0; row < gridSize; row++) {
            for (let col = 0; col < gridSize - 2; col++) {
                if (grid[row][col] === grid[row][col + 1] && grid[row][col] === grid[row][col + 2]) {
                    matches.add(`${row}-${col}`);
                    matches.add(`${row}-${col + 1}`);
                    matches.add(`${row}-${col + 2}`);
                }
            }
        }

        // Check columns for matches
        for (let col = 0; col < gridSize; col++) {
            for (let row = 0; row < gridSize - 2; row++) {
                if (grid[row][col] === grid[row + 1][col] && grid[row][col] === grid[row + 2][col]) {
                    matches.add(`${row}-${col}`);
                    matches.add(`${row + 1}-${col}`);
                    matches.add(`${row + 2}-${col}`);
                }
            }
        }

        if (matches.size > 0) {
            clearMatches(matches);
        }

        matchCheckInProgress = false; // Reset flag after processing
    }

    // Clear matched tiles and refill the grid
    function clearMatches(matches) {
        matches.forEach(match => {
            const [row, col] = match.split('-').map(Number);
            const tileType = grid[row][col];

            // Check if the matched tile is the coal tile (type 1)
            if (tileType === 1) {
                health -= 10; // Subtract points for coal matches (adjust the value as needed)
            } else {
                health += 5; // Add points for other matches
            }

            grid[row][col] = null; // Mark tile as cleared
        });

        document.getElementById('health').innerHTML = `Glæde: <br> ${health}/100`;
        checkGameStatus(); // Check after updating health1

        // Drop tiles down to fill empty spaces
        for (let col = 0; col < gridSize; col++) {
            for (let row = gridSize - 1; row >= 0; row--) {
                if (grid[row][col] === null) {
                    // Find the nearest non-null tile above
                    for (let k = row - 1; k >= 0; k--) {
                        if (grid[k][col] !== null) {
                            grid[row][col] = grid[k][col];
                            grid[k][col] = null;
                            break;
                        }
                    }
                }
            }
        }

        // Refill the grid with new tiles
        for (let row = 0; row < gridSize; row++) {
            for (let col = 0; col < gridSize; col++) {
                if (grid[row][col] === null) {
                    grid[row][col] = Math.floor(Math.random() * tileTypes);
                }
            }
        }

        renderGrid();
        setTimeout(checkMatches, 500); // Check again in case new matches were created
    }

    // Initialize the game
    initGrid();

    // Open the modal
    function openModal(modalId) {
        // Hide all modals first
        document.querySelectorAll('.modal').forEach(function (modal) {
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
    window.onclick = function (event) {
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

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
