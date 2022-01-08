<?php
    /**
     * Sistema di autenticazione.
     * Questo file verifica l'esistenza del cookie per eseguire
     * automaticamente l'accessso (token_accesso)
    */
    $root_folder = '/restaurant-app';
    require_once $_SERVER['DOCUMENT_ROOT'] . $root_folder . '/includes/functions/inc_accounts.php';

    if (isset($_COOKIE['token_accesso'])) {
        $auth_token = $_COOKIE['token_accesso'];
        $account = get_accounts('', $auth_token);

        if (!empty($account)) {
            $_SESSION['account'] = $account[0];
        }
    }

    function no_permission($permission) {
        return !isset($_SESSION['account']) || 
        !has_permission($permission, $_SESSION['account']['cod_gruppo']);
    }
