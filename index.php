<?php
    session_start();
    require_once 'includes/inc_auth.php';
    define('HOME', 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Home</title>
</head>
<body>
    <?php 
        require_once 'includes/inc_header.php';
    ?>
    <h1>Benvenuto!</h1>
    <h3>Piattaforma online di prenotazione tavoli in un ristorante.</h3>
    
    <div class="center">
        <img class="test-picture" src="resources/img/tables.jpg">
    </div> 
</body>
</html>
