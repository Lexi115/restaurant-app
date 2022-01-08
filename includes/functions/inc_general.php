<?php
    // Converti risultato query in un array associativo
    function to_array($query_result) {
        $arr = array();

        while ($row = $query_result->fetch_assoc()) {
            array_push($arr, $row);
        }

        return $arr;
    }

    // Converti risultato query in un array
    function to_direct_array($query_result, $column_name) {
        $arr = array();

        while ($row = $query_result->fetch_assoc()) {
            array_push($arr, $row[$column_name]);
        }

        return $arr;
    }

    /**
     * Applica uno sfasamento all'ora di una data
     * (utilizzato durante la prenotazione per
     * assicurarsi che quel determinato tavolo non sia
     * già occupato da qualcun'altro in quell'intervallo
     * di tempo)
     */
    function date_hour_offset($date, $offset) {
        $split_date = preg_split('/[\s:-]+/', $date);
        $year = $split_date[0];
        $month = $split_date[1];
        $day = $split_date[2];
        $hour = $split_date[3];
        $minutes = $split_date[4];

        return date('Y-m-d H:i:s', mktime($hour + $offset, $minutes, 0, $month, $day, $year));
    }

    // Restituisci il numero di records che soddisfano una condizione
    function get_count($table, $checked_column, $value = '%') {
        global $conn;

        $sql = "SELECT COUNT(*) AS `count` FROM `%s` WHERE `%s` LIKE '%s';";
        $result = $conn->query(sprintf($sql, $table, $checked_column, $value));
        return intval($result->fetch_assoc()['count']);
    }

    // Restituisci tutti i records di una tabella (senza divisione in pagine)
    function get($table) {
        global $conn;

        $sql = "SELECT * FROM `%s`;";
        return to_array($conn->query(sprintf($sql, $table)));
    }
