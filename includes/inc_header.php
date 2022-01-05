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
    <nav>
        <ul>
            <li><a class="nav-button <?php if (defined('HOME')) echo 'disabled'; ?>" href="index.php">Home</a></li>
            <li><a class="nav-button <?php if (defined('BOOKING')) echo 'disabled'; ?>" href="booking.php">Prenota</a></li>

            <?php if (!isset($_SESSION['account'])) { ?>
                <li><a class="nav-button <?php if (defined('LOGIN')) echo 'disabled'; ?>" href="login.php">Login</a></li>
            <?php } else {
                    if (has_permission('mostra_prenotazioni', $_SESSION['account']['cod_gruppo'])) { ?>
                        <li><a class="nav-button <?php if (defined('RESERVATIONS')) echo 'disabled'; ?>" href="reservations_list.php">Prenotazioni</a></li>
            <?php   }
                    if (has_permission('admin', $_SESSION['account']['cod_gruppo'])) { ?>
                        <li><a class="nav-button <?php if (defined('ROOMS')) echo 'disabled'; ?>" href="rooms_list.php">Sale</a></li>
                        <li><a class="nav-button <?php if (defined('TABLES')) echo 'disabled'; ?>" href="tables_list.php">Tavoli</a></li>
                        <li><a class="nav-button <?php if (defined('ACCOUNTS')) echo 'disabled'; ?>" href="accounts_list.php">Accounts</a></li>
            <?php   } ?>
                <li><a class="nav-button" href="api/api_logout.php">Logout</a></li>
            <?php } ?>
        </ul>  
    </nav>
</body>
</html>