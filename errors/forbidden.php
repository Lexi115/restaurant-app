<?php
    session_start();
    require_once '../includes/inc_auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Document</title>
</head>
<body>
    <?php 
        require_once '../includes/inc_header.php';
    ?>

    <div class="center">
        <p class="title">403</p>
        <p class="subtitle">Accesso negato.</p>
    </div>   
</body>
</html>
