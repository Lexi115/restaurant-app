<?php
    require_once __DIR__ . '/../inc_database.php';
    require_once 'inc_general.php';

    // Crea sala
    function create_dining_room($room_name, $room_type) {
        global $conn;

        $sql = "INSERT INTO `sale` (`nome_sala`, `cod_tipo_sala`) VALUES ('%s', '%s');";
        return $conn->query(sprintf($sql, $room_name, $room_type));
    }

    // Modifica sala già esistente
    function edit_dining_room($room_id, $room_name, $room_type) {
        global $conn;

        $sql = "UPDATE `sale` SET `nome_sala` = '%s', `cod_tipo_sala` = '%s' WHERE `cod_sala` = '%s';";
        return $conn->query(sprintf($sql, $room_name, $room_type, $room_id));
    }

    // Rimuovi sala
    function delete_dining_room($room_id) {
        global $conn;
        
        $sql = "DELETE FROM `sale` WHERE `cod_sala` = %s;";
        return $conn->query(sprintf($sql, $room_id));
    }

    // Restituisci tutte le sale tranne una in particolare
    function get_dining_rooms_except($room) {
        global $conn;

        $sql = "SELECT * FROM `sale` WHERE `cod_sala` <> '%s';";
        return to_array($conn->query(sprintf($sql, $room)));
    }

    // Restituisci tutte le sale
    function get_dining_rooms($id, $type = '%', $rows = 5, $page = 1, $columns = '*') {
        global $conn;

        $sql = "SELECT %s FROM `sale` INNER JOIN `tipisala` USING (`cod_tipo_sala`) 
        WHERE `cod_sala` LIKE '%s' AND `cod_tipo_sala` LIKE '%s' LIMIT %s OFFSET %s;";
        $arr = to_array($conn->query(sprintf($sql, $columns, $id, $type, $rows, $rows * ($page - 1))));

        return $arr;
    }
