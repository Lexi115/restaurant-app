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

    function delete_reservation($reservation_id) {
        global $conn;

        $sql = "DELETE FROM `prenotazioni` WHERE `cod_prenotazione` = '%s';";
        return $conn->query(sprintf($sql, $reservation_id));
    }

    function create_reservation($customer, $date, $time, $number_of_people, $notes = '') {
        global $conn;

        $customer = $conn->real_escape_string($customer);
        $date = $conn->real_escape_string($date) . ' ' . $conn->real_escape_string($time) . ':00';
        $number_of_people = $conn->real_escape_string($number_of_people);
        $notes = $conn->real_escape_string($notes);
        $reservation_id = bin2hex(random_bytes(5));

        $sql = "INSERT INTO `prenotazioni` (`cod_prenotazione`, `cf_cliente`, `n_persone`, `note_aggiuntive`, `status`) 
        VALUES ('%s', '%s', '%s', '%s', 1);";
        $conn->query(sprintf($sql, $reservation_id, $customer, $number_of_people, $notes));

        // Ricerca in tutte le sale
        $dining_room = '%';
        $dining_rooms = array();
        $i = 0;
        
        while ($number_of_people > 0) {
            //echo "num people: " . $number_of_people . "   " . $dining_room . " <br>";
            $table = get_free_table($number_of_people, $dining_room);
            
            // Controlla disponibilità tavoli nella sala
            if (!$table) {

                // Cambia sala se possibile
                if ($dining_room != '%' && $i < count($dining_rooms)) {
                    //echo "cambio sala...";
                    $dining_room = $dining_rooms[$i++]['codice_sala']; 
                    continue;
                }

                // Rimuovi prenotazione se non ci sono abbastanza posti in tutto il ristorante
                delete_reservation($reservation_id);
                break;
            }

            // Memorizza la sala dell'ultimo tavolo
            $dining_room = $table['sala'];

            // Conserva le altre sale per eventuali cicli (se non ci sono posti in una sala, controlla che siano
            // disponibili in un'altra)
            if ($i == 0)
                $dining_rooms = get_dining_rooms_except($dining_room);

            // Prenota tavolo
            book_table($reservation_id, $table['numero_tavolo'], $date);

            // Decrementa numero persone in attesa di trovare un posto
            $number_of_people -= $table['n_posti'];
        }
    }

    function book_table($reservation_id, $table_number, $date) {
        global $conn;

        $sql = "INSERT INTO `tavoliprenotati` (`cod_prenotazione`, `numero_tavolo`, `data`) 
        VALUES ('%s', '%s', '%s');";
        
        return $conn->query(sprintf($sql, $reservation_id, $table_number, $date));
    }

    function get_free_table($number_of_people, $dining_room_id) {
        global $conn;

        $sql = "SELECT * FROM `tavoli` WHERE `numero_tavolo` NOT IN (SELECT `numero_tavolo` FROM `tavoliprenotati`) 
        AND `sala` LIKE '%s' ORDER BY `n_posti` ASC;";
        //echo "free table sql: " . sprintf($sql, $dining_room_id) . "<br>";
        $result = $conn->query(sprintf($sql, $dining_room_id));
        $table_array = to_array($result);
        //echo "result sql: " . var_dump($table_array) . "<br><br>";
        //return json_encode($table_array);
        if (!$table_array) 
            return false;

        return $table_array[get_table_index_for($number_of_people, $table_array)];

        //return $table_array[rand(0, count($table_array) - 1)];
    }

    function get_table_index_for($number_of_people, $table_array) {
        $i = 0;
        $length = count($table_array);

        while ($i < $length && $number_of_people > $table_array[$i]['n_posti']) {
            $i++;
        }

        return ($i == $length) ? $i - 1 : $i;

        /* if ($i - 1 < 0)
            return $i;

        else if ($i == $length)
            return $length - 1;

        else {
            $diff1 = abs($number_of_people - $table_array[$i - 1]['n_posti']); // 2
            $diff2 = abs($number_of_people - $table_array[$i]['n_posti']); // 1

            return $diff2 <= $diff1 ? $i : $i - 1; // 1 <= 2
        } */
    }

    function get_dining_rooms_except($room) {
        global $conn;

        $sql = "SELECT * FROM `sale` WHERE `codice_sala` <> '%s';";
        return to_array($conn->query(sprintf($sql, $room)));
    }

    function left_shift($arr, $times = 1) {
        $times = $times % count($arr);
        $other_half = array_splice($arr, 0, $times);
        return array_merge($arr, $other_half);
    }
 