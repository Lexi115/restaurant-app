<?php
    require_once __DIR__ . '/../inc_database.php';
    require_once 'inc_general.php';
    require_once 'inc_tables.php';
    
    function customer_exists($fiscal_code) {
        global $conn;

        $sql = "SELECT `cf_cliente` FROM `clienti` WHERE `cf_cliente` = '%s';";
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

            $sql = "INSERT INTO `clienti` (`cf_cliente`, `cognome`, `nome`, `telefono`, `indirizzo`) 
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

    function edit_customer($fiscal_code, $last_name, $first_name, $phone_number, $address) {
        global $conn;

        $fiscal_code = $conn->real_escape_string($fiscal_code);
        $last_name = $conn->real_escape_string($last_name);
        $first_name = $conn->real_escape_string($first_name);
        $phone_number = $conn->real_escape_string($phone_number);
        $address = $conn->real_escape_string($address);

        $sql = "UPDATE `clienti` SET `cognome` = '%s', `nome` = '%s', `telefono` = '%s', `indirizzo` = '%s' 
        WHERE `cf_cliente` = '%s';";
        echo sprintf($sql, $last_name, $first_name, $phone_number, $address, $fiscal_code);
        return $conn->query(sprintf($sql, $last_name, $first_name, $phone_number, $address, $fiscal_code));
    }

    function edit_reservation($reservation_id, $fiscal_code, $date, $number_of_people, $notes, $status) {
        global $conn;

        $reservation_id = $conn->real_escape_string($reservation_id);
        $fiscal_code = $conn->real_escape_string($fiscal_code);
        $date = $conn->real_escape_string($date);
        $number_of_people = $conn->real_escape_string($number_of_people);
        $notes = $conn->real_escape_string($notes);
        $status = $conn->real_escape_string($status);

        $sql = "UPDATE `prenotazioni` SET `cf_cliente` = '%s', `data` = '%s', `n_persone` = '%s', `note_aggiuntive` = '%s', 
        `cod_status` = %s WHERE `cod_prenotazione` = '%s';";

        return $conn->query(sprintf($sql, $fiscal_code, $date, $number_of_people, $notes, $status, $reservation_id));
    }

    function create_reservation($reservation_id, $fiscal_code, $date, $number_of_people, $notes = '', $status = 1) {
        global $conn;

        $fiscal_code = $conn->real_escape_string($fiscal_code);
        $date = $conn->real_escape_string($date);
        $number_of_people = $conn->real_escape_string($number_of_people);
        $notes = $conn->real_escape_string($notes);
        $status = $conn->real_escape_string($status);
        

        $sql = "INSERT INTO `prenotazioni` (`cod_prenotazione`, `cf_cliente`, `data`, `n_persone`, `note_aggiuntive`, `cod_status`) 
        VALUES ('%s', '%s', '%s', '%s', '%s', %s);";
        $conn->query(sprintf($sql, $reservation_id, $fiscal_code, $date, $number_of_people, $notes, $status));

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
                    $dining_room = $dining_rooms[$i++]['cod_sala']; 
                    continue;
                }

                // Rimuovi prenotazione se non ci sono abbastanza posti in tutto il ristorante
                delete_reservation($reservation_id);
                break;
            }

            // Memorizza la sala dell'ultimo tavolo
            $dining_room = $table['cod_sala'];

            // Conserva le altre sale per eventuali cicli (se non ci sono posti in una sala, controlla che siano
            // disponibili in un'altra)
            if ($i == 0)
                $dining_rooms = get_dining_rooms_except($dining_room);

            // Prenota tavolo
            book_table($reservation_id, $table['n_tavolo']);
            
            // Decrementa numero persone in attesa di trovare un posto
            $number_of_people -= $table['n_posti'];
        }
    }

    function get_reservations($id = '%', $status = 1, $rows = '%', $page = '%', $columns = '*') {
        global $conn;

        $limit_stmt = $rows != '%' && $page != '%' ? ' LIMIT ' . $rows . ' OFFSET ' . $rows * ($page - 1) : '';
        $sql = "SELECT %s FROM `prenotazioni` INNER JOIN `clienti` USING (`cf_cliente`) 
        INNER JOIN `statusprenotazione` USING (`cod_status`) WHERE `cod_prenotazione` LIKE '%s' AND 
        `cod_status` LIKE '%s'" . $limit_stmt . ";";

        $arr = to_array($conn->query(sprintf($sql, $columns, $id, $status)));

        foreach ($arr as &$element) {
            $element['tavoli_assegnati'] = get_booked_tables($id);
        }
        unset($element);

        return $arr;
    }

    function get_reservation_states() {
        global $conn;

        $sql = "SELECT * FROM `statusprenotazione`;";
        return to_array($conn->query($sql));
    }