<?php
    require_once __DIR__ . '/../inc_database.php';
    require_once 'inc_general.php';

    function get_tables($id, $room, $rows = 5, $page = 1, $columns = '*') {
        global $conn;

        $sql = "SELECT %s FROM `tavoli` INNER JOIN `sale` USING (`cod_sala`) 
        WHERE `numero_tavolo` LIKE '%s' AND `cod_sala` LIKE '%s' LIMIT %s OFFSET %s;";
        $arr = to_array($conn->query(sprintf($sql, $columns, $id, $room, $rows, $rows * ($page - 1))));

        return $arr;
    }

    function edit_table($table_number, $number_of_seats, $room) {
        global $conn;

        $table_number = $conn->real_escape_string($table_number);
        $number_of_seats = $conn->real_escape_string($number_of_seats);
        $room = $conn->real_escape_string($room);

        $sql = "UPDATE `tavoli` SET `n_posti` = '%s', `cod_sala` = '%s' WHERE `numero_tavolo` = '%s';";
        return $conn->query(sprintf($sql, $number_of_seats, $room, $table_number));
    }

    function delete_table($table_number) {
        global $conn;
        
        $sql = "DELETE FROM `tavoli` WHERE `numero_tavolo` = %s;";

        return $conn->query(sprintf($sql, $table_number));
    }

    function delete_all_booked_tables($reservation_id = '%') {
        global $conn;
        
        $sql = "DELETE FROM `tavoliprenotati` WHERE `cod_prenotazione` LIKE '%s';";

        return $conn->query(sprintf($sql, $reservation_id));
    }

    function book_tables($reservation_id, $tables_array) {
        foreach ($tables_array as $table) {
            book_table($reservation_id, $table);
        }
    }

    function book_table($reservation_id, $table_number) {
        global $conn;

        $sql = "INSERT IGNORE INTO `tavoliprenotati` (`cod_prenotazione`, `numero_tavolo`) 
        VALUES ('%s', '%s');";
        
        return $conn->query(sprintf($sql, $reservation_id, $table_number));
    }

    function get_booked_tables($reservation_id = '%') {
        global $conn;

        $sql = "SELECT `numero_tavolo` FROM `tavoliprenotati` WHERE `cod_prenotazione` LIKE '%s';";
        return to_direct_array($conn->query(sprintf($sql, $reservation_id)), 'numero_tavolo');
    }

    function get_free_table($number_of_people, $date, $dining_room_id) {
        global $conn;
        $date_offset = 3;

        $sql = "SELECT * FROM `tavoli` WHERE `numero_tavolo` NOT IN (SELECT `numero_tavolo` FROM `tavoliprenotati` 
        INNER JOIN `prenotazioni` USING (`cod_prenotazione`) WHERE `data` > '%s' AND `data` < '%s') 
        AND `sala` LIKE '%s' ORDER BY `n_posti` ASC;";

        $result = $conn->query(sprintf($sql, date_hour_offset($date, -$date_offset), 
        date_hour_offset($date, $date_offset), $dining_room_id));
        
        $table_array = to_array($result);

        if (empty($table_array)) 
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

        $sql = "SELECT * FROM `sale` WHERE `cod_sala` <> '%s';";
        return to_array($conn->query(sprintf($sql, $room)));
    }

    function get_dining_rooms() {
        global $conn;

        $sql = "SELECT * FROM `sale`;";
        return to_array($conn->query($sql));
    }