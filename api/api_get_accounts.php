<?php
    require __DIR__ . '/../includes/functions/inc_accounts.php';

    $group = isset($_GET['group']) ? $_GET['group'] : '%';
    $columns = isset($_GET['columns']) ? $_GET['columns'] : '*';

    $user = get_users('', '', $group, $columns);
    echo !$user ? '{}' : json_encode($user);
?>