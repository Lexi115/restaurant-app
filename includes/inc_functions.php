<?php
    require 'inc_database.php';
    
    function get_user($username, $password, $token = '') {
        global $conn;

        $username = $conn->real_escape_string($username);
        $password = $conn->real_escape_string($password);
        $token = $conn->real_escape_string($token);

        $sql = "SELECT * FROM `accounts` WHERE `username` = '%s' OR `token_accesso` = '%s';";
        $result = $conn->query(sprintf($sql, $username, $token));

        if ($result->num_rows == 0) {
            // Utente non trovato
            return 1;
        }

        $user = $result->fetch_assoc();

        if (!password_verify($password, $user['password_hash'])) {
            // Password non corretta
            return 2;
        }

        return $user;
    }

    function create_user($username, $password, $group) {
        global $conn;

        $username = $conn->real_escape_string($username);
        $password_hash = password_hash($conn->real_escape_string($password), PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(20));

        $sql = "INSERT INTO `accounts` (`username`, `password_hash`, `gruppo`, `token_accesso`)
        VALUES ('%s', '%s', '%s', '%s');";

        return $conn->query(sprintf($sql, $username, $password_hash, $group, $token));
    }
    