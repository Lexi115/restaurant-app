<?php
    require 'inc_database.php';
    
    function to_array($query_result) {
        if ($query_result->num_rows > 0) {
            $arr = array();

            while ($row = $query_result->fetch_assoc()) {
                array_push($arr, $row);
            }

            return $arr;
        } else {
            return false;
        }
    }

    /* 
     * ----------------------------------------------- *
     * Funzioni accounts
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
        return to_array($result);
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

    function customer_exists($fiscal_code) {
        global $conn;

        $sql = "SELECT `cf` FROM `clienti` WHERE `cf` = '%s';";
        return ($conn->query(sprintf($sql, $fiscal_code)))->num_rows > 0;
    }

    function create_customer($fiscal_code, $last_name, $first_name, $phone_number, $address) {
        global $conn;

        $fiscal_code = $conn->real_escape_string($fiscal_code);

        if (!customer_exists($fiscal_code)) {
            $last_name = $conn->real_escape_string($last_name);
            $first_name = $conn->real_escape_string($first_name);
            $phone_number = $conn->real_escape_string($phone_number);
            $address = $conn->real_escape_string($address);

            $sql = "INSERT INTO `clienti` (`cf`, `cognome`, `nome`, `telefono`, `indirizzo`) 
            VALUES ('%s', '%s', '%s', '%s', '%s');";

            return $conn->query(sprintf($sql, $fiscal_code, $last_name, $first_name, $phone_number, $address));
        }

        return true;
    }

    function create_reservation($customer, $date, $time, $number_of_people, $notes = '') {
        global $conn;

        $customer = $conn->real_escape_string($customer);
        $date = $conn->real_escape_string($date) . ' ' . $conn->real_escape_string($time) . ':00';
        $number_of_people = $conn->real_escape_string($number_of_people);
        $notes = $conn->real_escape_string($notes);
        $reservation_id = bin2hex(random_bytes(10));

        $sql = "INSERT INTO `prenotazioni` (`cod_prenotazione`, `cf_cliente`, `n_persone`, `note_aggiuntive`, `status`) 
        VALUES ('%s', '%s', '%s', '%s', 1);";
        $conn->query(sprintf($sql, $reservation_id, $customer, $number_of_people, $notes));


        $table = get_free_table($date);
        if (!$table) {
            return false;
        }

        $sql = "INSERT INTO `tavoliprenotati` (`cod_prenotazione`, `numero_tavolo`, `data`) 
        VALUES ('%s', '%s', '%s');";
        echo sprintf($sql, $reservation_id, $table['numero_tavolo'], $date) . '<br>';
        $conn->query(sprintf($sql, $reservation_id, $table['numero_tavolo'], $date));

    }

    function get_free_table($date) {
        global $conn;

        $sql = "SELECT * FROM `tavoli` WHERE `numero_tavolo` NOT IN (SELECT `numero_tavolo` FROM `tavoliprenotati`);";
        $result = $conn->query($sql);
        $table_array = to_array($result);

        if (!$table_array) 
            return false;

        return $table_array[rand(0, count($table_array) - 1)];
    }

 