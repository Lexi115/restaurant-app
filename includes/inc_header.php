<?php
    function page_is($page_name) {
        return defined($page_name) ? 'disabled' : '';
    }

    $root_folder = '/restaurant-app/restaurant-app';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a class="<?php echo page_is('HOME'); ?>" href="<?php echo $root_folder . '/index.php'; ?>">Home</a></li>
            <li><a class="<?php echo page_is('BOOKING'); ?>" href="<?php echo $root_folder . '/booking.php'; ?>">Prenota</a></li>

            <?php if (!isset($_SESSION['account'])) { ?>
                <li><a class="<?php echo page_is('LOGIN'); ?>" href="<?php echo $root_folder . '/login.php'; ?>">Login</a></li>
            <?php } else {
                    if (has_permission('mostra_prenotazioni', $_SESSION['account']['cod_gruppo'])) { ?>
                        <li><a class="<?php echo page_is('RESERVATIONS'); ?>" href="<?php echo $root_folder . '/reservations_list.php'; ?>">Prenotazioni</a></li>
            <?php   }
                    if (has_permission('admin', $_SESSION['account']['cod_gruppo'])) { ?>
                        <li><a class="<?php echo page_is('ROOMS'); ?>" href="<?php echo $root_folder . '/rooms_list.php'; ?>">Sale</a></li>
                        <li><a class="<?php echo page_is('TABLES'); ?>" href="<?php echo $root_folder . '/tables_list.php'; ?>">Tavoli</a></li>
                        <li><a class="<?php echo page_is('ACCOUNTS'); ?>" href="<?php echo $root_folder . '/accounts_list.php'; ?>">Accounts</a></li>
            <?php   } ?>
                <li><a class="nav-button" href="<?php echo $root_folder . '/api/api_logout.php'; ?>">Logout</a></li>
            <?php } ?>
        </ul>  
    </nav>
</body>
</html>