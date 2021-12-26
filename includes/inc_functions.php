<?php
    require 'inc_database.php';
    
    /* 
     * ----------------------------------------------- *
     * Funzioni database
     * ----------------------------------------------- *
     */

    function get_users($username, $token = '', $group = '', $columns = '*', $limit = 5, $page = 0) {
        global $conn;

        $columns = $conn->real_escape_string($columns);
        $username = $conn->real_escape_string($username);
        $token = $conn->real_escape_string($token);
        $group = $conn->real_escape_string($group);
        $limit = $conn->real_escape_string($limit);
        $page = $conn->real_escape_string($page);

        $sql = "SELECT %s FROM ((`accounts` INNER JOIN `gruppi` ON `accounts`.`gruppo`=`gruppi`.`cod_gruppo`) 
        INNER JOIN `permessi` ON `gruppi`.`set_permessi`=`permessi`.`cod_set_permessi`) WHERE `username` LIKE '%s' 
        OR `token_accesso`='%s' OR `cod_gruppo` LIKE '%s' LIMIT %s OFFSET %s;";

        $result = $conn->query(sprintf($sql, $columns, $username, $token, $group, $limit, $page));

        if ($result->num_rows > 0) {
            $arr = array();

            while ($row = $result->fetch_assoc()) {
                array_push($arr, $row);
            }

            return $arr;
        } else {
            return false;
        }
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
