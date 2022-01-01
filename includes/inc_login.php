<?php
    require 'functions/inc_accounts.php';

    if (isset($_POST['submit'])) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Verifica esistenza dell'utente
        $user = get_users($username);
        if (empty($user)) {
            die("Utente non trovato");
        }

        $user = $user[0];

        // Verifica password immessa con quella salvata nel database
        if (!password_verify($password, $user['password_hash'])) {
            die("Pw non corretta");
        }

        session_start();
        $_SESSION['user'] = $user;
        echo var_dump($_SESSION['user']);
    }

    exit();