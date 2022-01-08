<?php
    session_start();
    require_once __DIR__ . '/../includes/functions/inc_reservations.php';
    require_once __DIR__ . '/../includes/inc_auth.php';

    if (no_permission('admin')) {
        header('Location: errors/forbidden.php');
        exit();
    }

    if (isset($_GET['q'])) {
        switch ($_GET['q']) {

            case 'reservations':
                $reservation_id = $_POST['id'];
                delete_reservation($reservation_id);
                header('Location: ../reservations_list.php');
                break;

            case 'tables':
                $table_number = $_POST['id'];
                delete_table($table_number);
                header('Location: ../tables_list.php');
                break;

            case 'rooms':
                $room_id = $_POST['id'];
                delete_dining_room($room_id);
                header('Location: ../rooms_list.php');
                break;

            case 'accounts':
                $username = $_POST['id'];
                delete_account($username);
                header('Location: ../accounts_list.php');
                break;
                
        }
    }

    exit();
?>