<?php
    require_once $accountsFilePath;
    $logged_in = False;

    if (isset($_COOKIE['token_accesso'])) {
        $auth_token = $_COOKIE['token_accesso'];
        $account = get_accounts('', $auth_token);

        if (!empty($account)) {
            $_SESSION['account'] = $account[0];
            $logged_in = True;
        }
    }
?>