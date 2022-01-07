<?php
    session_start();
    require_once 'includes/inc_auth.php';
    define('ACCOUNTS', 0);

    if (!isset($_SESSION['account']) || !has_permission('admin', $_SESSION['account']['cod_gruppo'])) {
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <?php 
        require_once 'includes/inc_header.php';
    ?>
    <div id="pagination-element"></div>
    <div id="list-container"></div>

    <script src="js/general.js"></script>
    <script src="js/accounts_list.js"></script>
</body>
</html>