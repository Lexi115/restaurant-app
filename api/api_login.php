<?php
    /**
     * Effettua il login dell'account
     */
    require __DIR__ . '/../includes/functions/inc_accounts.php';

    if (isset($_POST['submit'])) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Verifica esistenza dell'utente
        $user = get_accounts($username);
        if (empty($user)) {
            header('Location: ../login.php?error=invalid-user');
            exit();
        }

        $user = $user[0];

        // Verifica password immessa con quella salvata nel database
        if (!password_verify($password, $user['password_hash'])) {
            header('Location: ../login.php?error=invalid-password');
            exit();
        }

        session_start();
        $_SESSION['account'] = $user;

        // Manda al client il cookie contenente il token di accesso
        setcookie('token_accesso', $user['token_accesso'], time() + 86400, '/');

        header('Location: ../index.php');
    }

    exit();
?>
