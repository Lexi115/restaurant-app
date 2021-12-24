<?php
    require 'inc_functions.php';

    if (isset($_POST['submit'])) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        $user = get_user($username, $password);
        switch ($user) {
            case 1:
                die('Utente non trovato');
                break;
            case 2:
                die('Password non corretta');
                break;
        }

        session_start();
        $_SESSION['user'] = $user;
        echo var_dump($_SESSION['user']);

    }

    exit();