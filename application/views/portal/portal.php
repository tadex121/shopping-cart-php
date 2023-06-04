<!DOCTYPE html>
<html lang="sl" >
    <head>
        <meta charset="utf-8">
        <title>Nakupovalni seznam</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Montserrat:wght@700&family=Yeseva+One&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="<?php echo LIBRARY_URL; ?>/css/core.css?revision=<?php echo REVISION; ?>">



    </head>
    <body>
    <input type="hidden" id="BASE_URL" value="<?php echo BASE_URL; ?>" />
        <div class="top-nav">
            <?php if(!Helper::checkIfUserLoggedIn()): ?>
            <a href="<?= BASE_URL . "/prijava"; ?>" title="Prijava"> 
            <?php echo!Helper::checkIfUserLoggedIn() ? "Prijava" : ""; ?>
            <?php else: ?>
            <a class="log-out" href="<?= BASE_URL . "/log-out"; ?>" title="Odjava">
                    Odjava
                </a>
                <?php endif; ?>
        </a>
        </div>
        <?= $Content; ?>
    </body>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>






    <script src="<?php echo LIBRARY_URL; ?>/js/core.js?revision=<?php echo REVISION; ?>"></script>




</html>




