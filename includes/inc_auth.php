<?php
    define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/restaurant-app/restaurant-app');
    require_once ROOT . '/includes/functions/inc_accounts.php';

    if (isset($_COOKIE['token_accesso'])) {
        $auth_token = $_COOKIE['token_accesso'];
        $account = get_accounts('', $auth_token);

        if (!empty($account)) {
            $_SESSION['account'] = $account[0];
        }
    }
?>