<?php 
    require_once __DIR__ . '/../includes/functions/inc_reservations.php';

    if (isset($_GET['q'])) {
        switch ($_GET['q']) {

            case 'reservations':
                $reservation_id = $_POST['id'];
                delete_reservation($reservation_id);
                break;

            case 'tables':
                $table_number = $_POST['id'];
                delete_table($table_number);
                break;
                
        }
    }

    exit();
?>