<?php
    session_start();
    require_once 'includes/inc_auth.php';
    require_once 'includes/inc_alerts.php';
    define('LOGIN', 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Login</title>
</head>
<body>
    <?php 
        require 'includes/inc_header.php';
    ?>
    <section>
        <form class="form" action="api/api_login.php" method="post">
            <h1>Login</h1>
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button type="submit" name="submit">Vai</button>
            <?php 
                if (isset($_GET['error'])) {
                    print_alert($_GET['error']);
                }
            ?>
        </form>
    </section>
</body>
</html>
