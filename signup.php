<?php
require "settings/init.php";

if (!empty($_POST["data"])){
    $data = $_POST["data"];
    $starterLevel = 1;
    $starterHearts = 5;
    $sql = "INSERT INTO users (userName, userPassword, currentLevel, hearts) VALUES(:userName, :userPassword, $starterLevel, $starterHearts)";
    $bind = [":userName" => $data["brugernavn"], ":userPassword" => $data["password"]];

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
<div class="container">
    <div class="row g-5 d-flex justify-content-center">
        <div class="col-12 col-md-6">
            <form action="signup.php" method="post">
                <div><h3 class="text-danger">Ikke sikkert, brug ikke PERSONLIGE data</h3></div>
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

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
