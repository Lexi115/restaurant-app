<?php
    require_once __DIR__ . '/../includes/functions/inc_reservations.php';

    if (isset($_GET['q'])) {
        switch ($_GET['q']) {

            case 'reservations':
                $reservation_id = $_POST['id'];
                $fiscal_code = $_POST['cf'];
                $last_name = $_POST['cognome'];
                $first_name = $_POST['nome'];
                $phone_number = $_POST['telefono'];
                $address = $_POST['indirizzo'];
                $date = $_POST['data'] . ' ' . $_POST['ora'] . ':00';
                $number_of_people = $_POST['n_persone'];
                $notes = $_POST['note_aggiuntive'];
                $status = $_POST['status'];;
                $tables = json_decode($_POST['tavoli_assegnati']);

                edit_customer($fiscal_code, $last_name, $first_name, $phone_number, $address);
                edit_reservation($reservation_id, $fiscal_code, $date, $number_of_people, $notes, $status);

                delete_all_booked_tables($reservation_id);
                book_tables($reservation_id, $tables);
                
                break;

            case 'tables':
                $table_number = $_POST['id'];
                $number_of_seats = $_POST['n_posti'];
                $room = $_POST['sala'];

                edit_table($table_number, $number_of_seats, $room);
                break;
                
        }
    }
    
    exit();
?>