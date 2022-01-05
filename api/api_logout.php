<?php
    session_start();
    session_unset();
    session_destroy();
    setcookie('token_accesso', $auth_token, time() - 360, '/');
    
    header('Location: ../index.php');
?>