<?php
    require_once __DIR__ . '/../inc_database.php';
    require_once 'inc_general.php';
    
    function get_accounts($username, $token = '', $group = '', $rows = 5, $page = 1, $columns = '*') {
        global $conn;

        $columns = $conn->real_escape_string($columns);
        $username = $conn->real_escape_string($username);
        $token = $conn->real_escape_string($token);
        $group = $conn->real_escape_string($group);
        $rows = $conn->real_escape_string($rows);
        $page = $conn->real_escape_string($page);

        $sql = "SELECT %s FROM `accounts` INNER JOIN `gruppi` USING (`cod_gruppo`) INNER JOIN `permessi` 
        USING (`cod_set_permessi`) WHERE `username` LIKE '%s' OR `token_accesso`='%s' OR `cod_gruppo` 
        LIKE '%s' LIMIT %s OFFSET %s;";

        $result = $conn->query(sprintf($sql, $columns, $username, $token, $group, $rows, $rows * ($page - 1)));
        return to_array($result);
    }

    function create_account($username, $password, $group) {
        global $conn;

        $username = $conn->real_escape_string($username);
        $password_hash = password_hash($conn->real_escape_string($password), PASSWORD_BCRYPT);
        $group = $conn->real_escape_string($group);

        do {
            $token = bin2hex(random_bytes(20));
        } while (!empty(get_accounts('', $token)));
        

        $sql = "INSERT INTO `accounts` (`username`, `password_hash`, `cod_gruppo`, `token_accesso`)
        VALUES ('%s', '%s', '%s', '%s');";

        return $conn->query(sprintf($sql, $username, $password_hash, $group, $token));
    }

    function delete_account($username) {
        global $conn;

        $sql = "DELETE FROM `accounts` WHERE `username` = '%s';";
        return $conn->query(sprintf($sql, $username));
    }

    function edit_account($token, $username, $password, $group) {
        global $conn;

        $username = $conn->real_escape_string($username);
        $group = $conn->real_escape_string($group);

        if (strlen($password) > 0) {
            $password_hash = password_hash($conn->real_escape_string($password), PASSWORD_BCRYPT);
            $sql = "UPDATE `accounts` SET `username` = '%s', `password_hash` = '%s', `cod_gruppo` = '%s' 
            WHERE `token_accesso` = '%s';";

            return $conn->query(sprintf($sql, $username, $password_hash, $group, $token));
        } else {
            $sql = "UPDATE `accounts` SET `username` = '%s', `cod_gruppo` = '%s' 
            WHERE `token_accesso` = '%s';";

            return $conn->query(sprintf($sql, $username, $group, $token));
        }

        
    }

    function has_permission($permission, $group_id) {
        global $conn;

        $sql = "SELECT * FROM `gruppi` INNER JOIN `permessi` USING (`cod_set_permessi`) 
        WHERE `cod_gruppo` = '%s';";
        $permission_value = $conn->query(sprintf($sql, $group_id))->fetch_assoc()[$permission];
        return $permission_value == 1;
    }
    