<?php
    require '../includes/inc_functions.php';

    $group = isset($_GET['group']) ? $_GET['group'] : '%';
    $columns = isset($_GET['columns']) ? $_GET['columns'] : '*';

    $user = get_users('', '', $group, $columns);
    echo !$user ? '{}' : json_encode($user);
?>