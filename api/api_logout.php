<?php
    /**
     * Effettua il logout dell'account
     */
    session_start();
    session_unset();
    session_destroy();

    // Rimuovi cookie dal client
    setcookie('token_accesso', $auth_token, time() - 360, '/');
    
    header('Location: ../index.php');
?>
