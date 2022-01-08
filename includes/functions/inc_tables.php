<?php
    require_once __DIR__ . '/../inc_database.php';
    require_once 'inc_general.php';
    require_once 'inc_rooms.php';

    // Restituisci array di tavoli
    function get_tables($id, $room = '%', $rows = 5, $page = 1, $columns = '*') {
        global $conn;

        $sql = "SELECT %s FROM `tavoli` INNER JOIN `sale` USING (`cod_sala`) 
        WHERE `n_tavolo` LIKE '%s' AND `cod_sala` LIKE '%s' LIMIT %s OFFSET %s;";
        $arr = to_array($conn->query(sprintf($sql, $columns, $id, $room, $rows, $rows * ($page - 1))));

        return $arr;
    }

    // Crea nuovo tavolo
    function create_table($table_number, $number_of_seats, $room) {
        global $conn;

        $table_number = $conn->real_escape_string($table_number);
        $number_of_seats = $conn->real_escape_string($number_of_seats);
        $room = $conn->real_escape_string($room);

        $sql = "INSERT INTO `tavoli` (`n_tavolo`, `n_posti`, `cod_sala`) VALUES ('%s', '%s', '%s');";
        return $conn->query(sprintf($sql, $table_number, $number_of_seats, $room));
    }

    // Modifica tavolo già esistente
    function edit_table($table_number, $number_of_seats, $room) {
        global $conn;

        $table_number = $conn->real_escape_string($table_number);
        $number_of_seats = $conn->real_escape_string($number_of_seats);
        $room = $conn->real_escape_string($room);

        $sql = "UPDATE `tavoli` SET `n_posti` = '%s', `cod_sala` = '%s' WHERE `n_tavolo` = '%s';";
        return $conn->query(sprintf($sql, $number_of_seats, $room, $table_number));
    }

    // Rimuovi tavolo
    function delete_table($table_number) {
        global $conn;
        
        $sql = "DELETE FROM `tavoli` WHERE `n_tavolo` = %s;";
        return $conn->query(sprintf($sql, $table_number));
    }

    // Rimuovi tutti i tavoli associati ad una prenotazione in particolare
    function delete_all_booked_tables($reservation_id = '%') {
        global $conn;
        
        $sql = "DELETE FROM `tavoliprenotati` WHERE `cod_prenotazione` LIKE '%s';";
        return $conn->query(sprintf($sql, $reservation_id));
    }

    // Prenota tutti i tavoli contenuti in un array
    function book_tables($reservation_id, $tables_array) {
        foreach ($tables_array as $table) {
            book_table($reservation_id, $table);
        }
    }

    // Prenota tavolo
    function book_table($reservation_id, $table_number) {
        global $conn;

        $sql = "INSERT IGNORE INTO `tavoliprenotati` (`cod_prenotazione`, `n_tavolo`) 
        VALUES ('%s', '%s');";
        
        return $conn->query(sprintf($sql, $reservation_id, $table_number));
    }

    // Restituisci tutti i tavoli associati ad una prenotazione
    // (utilizzato per la lista dei tavoli modificabile nel file edit_reservation.php)
    function get_booked_tables($reservation_id = '%') {
        global $conn;

        $sql = "SELECT `n_tavolo` FROM `tavoliprenotati` WHERE `cod_prenotazione` LIKE '%s';";
        return to_direct_array($conn->query(sprintf($sql, $reservation_id)), 'n_tavolo');
    }

    // Trova un tavolo libero dati il numero di persone, la data e la sala
    function get_free_table($number_of_people, $date, $dining_room_id) {
        global $conn;
        $date_offset = 3;

        $sql = "SELECT * FROM `tavoli` WHERE `n_tavolo` NOT IN (SELECT `n_tavolo` FROM `tavoliprenotati` 
        INNER JOIN `prenotazioni` USING (`cod_prenotazione`) WHERE `data` > '%s' AND `data` < '%s') 
        AND `cod_sala` LIKE '%s' ORDER BY `n_posti` ASC;";

        $result = $conn->query(sprintf($sql, date_hour_offset($date, -$date_offset), 
        date_hour_offset($date, $date_offset), $dining_room_id));

        if (!$result) 
            return false;

        $table_array = to_array($result);
        return $table_array[get_table_index_for($number_of_people, $table_array)];
    }

    /** 
     * Restituisci l'indice del tavolo più adatto dato il numero di persone
     * (es. se sono 10 persone, l'algoritmo cerca di trovare un tavolo con
     * numero posti >= 10. Se non lo trova, prende il tavolo con più posti
     * nella sala)
     * */ 
    function get_table_index_for($number_of_people, $table_array) {
        $i = 0;
        $length = count($table_array);

        while ($i < $length && $number_of_people > $table_array[$i]['n_posti']) {
            $i++;
        }

        return ($i == $length) ? $i - 1 : $i;
    }
