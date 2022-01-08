<?php
    session_start();
    require_once 'includes/inc_auth.php';
    define('TABLES', 0);

    if (no_permission('admin')) {
        header('Location: errors/forbidden.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Tavoli</title>
</head>
<body>
    <?php 
        require_once 'includes/inc_header.php';
    ?>
    <button onclick="location.href = 'edit_forms/create_table.php';">AGGIUNGI</button>
    <div id="pagination-element"></div>
    <div id="list-container"></div>

    <script src="js/general.js"></script>
    <script src="js/tables_list.js"></script>
</body>
</html>