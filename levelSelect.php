<?php
require "settings/init.php";
session_start();

if (!empty($_GET["userId"])) {
    $userId = ($_GET['userId']);
}
$users = $db->sql("SELECT * FROM users INNER JOIN levels ON currentLevel = levelId WHERE userId = $userId");
$user = $users[0]; // Access the first (and presumably only) result
$currentLevel = $user->currentLevel;
$nextLevel = intval($currentLevel + 3);
$levels = $db->sql("SELECT * FROM levels INNER JOIN worlds ON worldDesign = worldId WHERE levelId = $currentLevel");
$level = $levels[0];
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

<body class="bg-farve">
<div class="container-fluid vh-100">
    <div class="row">.....<a href="logud.php">Logud</a></div>
    <?php
    $worldGen = $db->sql("SELECT * FROM worlds INNER JOIN levels ON worldId = worldDesign WHERE worldId = $currentLevel ");
    ?>
    <div class="row">
        <div class="fixed-top bg-success">...</div>
    </div>
    <div class="row justify-content-center d-flex">
        <div class="col-6"><img class="img-fluid" src="img/artboard.webp" alt=""></div>
        <a href="playLevel.php?levelId=<?php echo $currentLevel ?>"><button type="button" class="btn btn-danger rounded-circle" style="height: 40px; width: 40px" > <?php echo $currentLevel ?> </button></a>
        <a href="playLevel.php?levelId=<?php echo $nextLevel ?>"><button type="button" class="btn btn-danger rounded-circle" style="height: 40px; width: 40px" > <?php echo $nextLevel ?> </button></a>
        <div><?php echo $user->userName; ?></div>
        <div><?php echo $user->moves; ?></div>
    </div>
    <div class="row">
        <div class="fixed-bottom bg-danger">...</div>
    </div>
    <?php ?>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
