<?php
require "settings/init.php";
session_start();

if (!empty($_GET["levelId"])) {
    $levelId = ($_GET['levelId']);
} else {
    die('Level ID is missing.');
}

$levels = $db->sql("SELECT * FROM levels WHERE levelId = $levelId");
$level = $levels[0]; // Access the first (and presumably only) result
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
<?php include 'navbar.php'; ?>
<div class="row g-2 mt-5">
    <div id="game-container">
        <div id="moves"> <?php echo $level->moves ?> </div>
        <div id="health">Health: 0</div>
        <div id="grid"></div>
    </div>
<script>
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


    function checkGameStatus() {
        if (health >= 100) {
            alert("<?php echo $level->levelFact ?> <a href='<?php echo $level->levelFactSource?>'> </a> ");
            // Optionally, you could redirect to another page or reload the game
            // window.location.reload();
            return; // Stop further checks if the game is won
        }

        if (moves <= 0) {
            alert("Game over! You lose. 😢");
            // Optionally, restart the game
            // window.location.reload();
        }
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
        console.log(moves);
        document.getElementById('moves').innerText = `Moves left: ${moves}`;
        renderGrid();
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
            grid[row][col] = null; // Mark tile as cleared
            health += 5; // Add points for each tile cleared
        });

        document.getElementById('health').innerText = `Health: ${health}`;
        checkGameStatus(); // Check after updating health

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
</script>
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
