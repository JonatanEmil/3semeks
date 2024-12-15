<?php
require "settings/init.php";
session_start();
if(!empty($_SESSION["userId"])) {
    header("Location: levelSelect.php?userId=".$_SESSION["userId"]);
}
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Klima Match</title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">
    <meta name=”description" content="Match 3 spil om klimaet, der helst skulle lære unge om klimaet">
    <meta property="og:title" content="Klima Match" />
    <meta property="og:type" content="klimamatch.dk" />
    <meta property="og:url" content="https://www.klimamatch.dk" />
    <meta property="og:image" content="https://www.klimamatch.dk/titlescreen.webp" />
    <meta property="og:description" content="Match 3 spil om klimaet, der helst skulle lære unge om klimaet" />
    <meta property="og:locale" content="da_DK" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" type="text/css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div class="d-flex align-items-center justify-content-center text-white vh-100"
     style="background-image: url('img/titlescreen.webp'); background-size: cover; background-position: center;">
    <div class="container-fluid vh-100">
        <div class="row g-2 d-flex justify-content-center text-center align-items-center h-100">
            <div class="col-8 col-md-4 col-xl-3">
                <h1 class="mb-3">Klima Match!</h1>
                <h2 class="mb-5">Tryk på START for at starte spillet</h2>
                <button type="button" class="btn btn-success mt-3 fs-3" data-bs-toggle="modal" data-bs-target="#exampleModal">START
                </button>
            </div>
        </div>
    </div>
</div>

<form action="login.php" method="post">
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="exampleModalLabel">Log In</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label d-block" for="brugernavn">Brugernavn</label>
                <div class="input-group mb-5">
                    <input class="form-control" id="brugernavn" name="brugernavn" placeholder="">
                </div>
                <label class="form-label d-block" for="kodeord">Kodeord</label>
                <div class="input-group">
                    <input class="form-control" id="kodeord" name="kodeord" placeholder="">
                </div>
                <div id="error-msg" class="text-danger mt-3"></div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div><a href="signup.php"><button type="button" class="btn btn-secondary">Opret ny bruger</button></a>
                <button type="submit" class="btn btn-primary" id="login">Log in</button></div>
            </div>
        </div>
    </div>
</div>
</form>


<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
