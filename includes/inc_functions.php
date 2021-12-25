<?php
    require 'inc_database.php';
    
    /* 
     * ----------------------------------------------- *
     * Database functions
     * ----------------------------------------------- *
     */

    function get_user($username, $token = '') {
        global $conn;

        $username = $conn->real_escape_string($username);
        $token = $conn->real_escape_string($token);

        $sql = "SELECT * FROM `accounts` WHERE `username` = '%s' OR `token_accesso` = '%s';";
        $result = $conn->query(sprintf($sql, $username, $token));

        return $result->num_rows == 0 ? false : $result->fetch_assoc();
    }

    function create_user($username, $password, $group) {
        global $conn;

        $username = $conn->real_escape_string($username);
        $password_hash = password_hash($conn->real_escape_string($password), PASSWORD_BCRYPT);
        $group = $conn->real_escape_string($group);
        $token = bin2hex(random_bytes(20));

        $sql = "INSERT INTO `accounts` (`username`, `password_hash`, `gruppo`, `token_accesso`)
        VALUES ('%s', '%s', '%s', '%s');";

        return $conn->query(sprintf($sql, $username, $password_hash, $group, $token));
    }
