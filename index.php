<?php
require "settings/init.php";
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
<div class="d-flex align-items-center justify-content-center"
     style="background-image: url('img/artboard.webp'); background-size: cover; background-position: center; height: 100vh;">
    <div class="container-fluid vh-100">
        <div class="row g-2 d-flex justify-content-center text-center align-items-center h-100">
            <div class="col-8 col-md-4 col-xl-3">
                <h1 class="mb-3">Klima Match!</h1>
                <h2 class="mb-5">Tryk på START for at starte spillet</h2>
                <div class="row my-5"></div>
                <div class="row my-5"></div>
                <div class="row my-5"></div>
                <div class="row my-5"></div>
                    <button type="button" class="btn btn-success mt-5 " data-bs-toggle="modal" data-bs-target="#exampleModal">START</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Log In</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label d-block" for="username">Username</label>
                <div class="input-group mb-5">
                    <input class="form-control" id="username" placeholder="">
                </div>
                <label class="form-label d-block" for="password">Password</label>
                <div class="input-group">
                    <input class="form-control" id="password" placeholder="">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Log in</button>
            </div>
        </div>
    </div>
</div>

<script>
    const myModal = document.getElementById('myModal')
    const myInput = document.getElementById('myInput')

    myModal.addEventListener('shown.bs.modal', () => {
        myInput.focus()
    })
</script>
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
