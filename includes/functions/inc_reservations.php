<?php
    require __DIR__ . '/../inc_database.php';
    require 'inc_general.php';
    
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
            $table = get_free_table($number_of_people, $date, $dining_room);
            
            // Controlla disponibilità tavoli nella sala
            if (!$table) {

                // Cambia sala se possibile
                if ($dining_room != '%' && $i < count($dining_rooms)) {
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

    function get_free_table($number_of_people, $date, $dining_room_id) {
        global $conn;
        $date_offset = 3;

        $sql = "SELECT * FROM `tavoli` WHERE `numero_tavolo` NOT IN (SELECT `numero_tavolo` FROM `tavoliprenotati` 
        WHERE `data` > '%s' AND `data` < '%s') AND `sala` LIKE '%s' ORDER BY `n_posti` ASC;";

        $result = $conn->query(sprintf($sql, date_hour_offset($date, -$date_offset), 
        date_hour_offset($date, $date_offset), $dining_room_id));
        
        $table_array = to_array($result);

        if (!$table_array) 
            return false;

        return $table_array[get_table_index_for($number_of_people, $table_array)];
    }

    function get_table_index_for($number_of_people, $table_array) {
        $i = 0;
        $length = count($table_array);

        while ($i < $length && $number_of_people > $table_array[$i]['n_posti']) {
            $i++;
        }

        return ($i == $length) ? $i - 1 : $i;
    }

    function get_dining_rooms_except($room) {
        global $conn;

        $sql = "SELECT * FROM `sale` WHERE `codice_sala` <> '%s';";
        return to_array($conn->query(sprintf($sql, $room)));
    }