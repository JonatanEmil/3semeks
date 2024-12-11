<?php
require "settings/init.php";

if (!empty($_POST["data"])) {
    $data = $_POST["data"];
    $starterLevel = 1;
    $profilePic = "ppplaceholder.webp";
    $starterHearts = 5;
    // Use placeholders for all values
    $sql = "INSERT INTO users (userName, userPassword, currentLevel, profilePic, hearts) 
            VALUES (:userName, :userPassword, :currentLevel, :profilePic, :hearts)";

    // Bind values securely
    $bind = [
        ":userName" => $data["brugernavn"],
        ":userPassword" => $data["password"],
        ":currentLevel" => $starterLevel,
        ":profilePic" => $profilePic,
        ":hearts" => $starterHearts
    ];

    $db->sql($sql, $bind, false);
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Forside</title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">

    <link href="css/styles.css" rel="stylesheet" type="text/css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div class="d-flex align-items-center justify-content-center text-white vh-100"
     style="background-image: url('img/titlescreen.webp'); background-size: cover; background-position: center;">
    <div class="container">
        <div class="row g-5 d-flex justify-content-center">
            <div class="col-12 col-md-6">
                <form action="signup.php" method="post">
                    <div><h3 class="text-danger col-8 offset-2 text-center">Ikke sikkert, brug ikke PERSONLIGE data</h3></div>
                    <div><label for="brugernavn" class="form-label">Vælg Brugernavn</label></div>
                    <div><input type="text" class="form-control" id="brugernavn" name="data[brugernavn]"
                                placeholder="Vælg brugernavn" value=""></div>
                    <div><label for="password" class="form-label">Lav Password</label></div>
                    <div><input type="text" class="form-control" id="password" name="data[password]"
                                placeholder="Vælg password" value=""></div>
                    <div>
                        <button class="btn btn-success" type="submit">Opret Bruger</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
