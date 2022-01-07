<?php
    session_start();
    require_once __DIR__ . '/../includes/functions/inc_reservations.php';
    require_once __DIR__ . '/../includes/inc_auth.php';

    if (!isset($_SESSION['account']) || !has_permission('admin', $_SESSION['account']['cod_gruppo'])) {
        header('Location: errors/forbidden.php');
        exit();
    }

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

            case 'rooms':
                $room_id = $_POST['id'];
                delete_dining_room($room_id);
                break;

            case 'accounts':
                $username = $_POST['id'];
                delete_account($username);
                break;
                
        }
    }

    exit();
?>