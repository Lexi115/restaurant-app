<?php
    function to_array($query_result, $direct_access = '') {
        $arr = array();

        if ($query_result->num_rows > 0) {
            
            if (strlen($direct_access) > 0)
                while ($row = $query_result->fetch_assoc()) {
                    array_push($arr, $row[$direct_access]);
                }
            else
                while ($row = $query_result->fetch_assoc()) {
                    array_push($arr, $row);
                }

        }

        return $arr;
    }

    function date_hour_offset($date, $offset) {
        $split_date = preg_split('/[\s:-]+/', $date);
        $year = $split_date[0];
        $month = $split_date[1];
        $day = $split_date[2];
        $hour = $split_date[3];
        $minutes = $split_date[4];

        return date('Y-m-d H:i:s', mktime($hour + $offset, $minutes, 0, $month, $day, $year));
    }