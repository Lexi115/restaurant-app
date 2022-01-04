<?php
    require_once __DIR__ . '/../includes/functions/inc_reservations.php';
    require_once __DIR__ . '/../includes/functions/inc_accounts.php';

    if (isset($_GET['q'])) {
        switch ($_GET['q']) {

            case 'reservations':
                $fiscal_code = $_POST['cf'];
                $last_name = $_POST['cognome'];
                $first_name = $_POST['nome'];
                $phone_number = $_POST['telefono'];
                $address = $_POST['indirizzo'];
                $date = $_POST['data'] . ' ' . $_POST['ora'] . ':00';
                $number_of_people = $_POST['n_persone'];
                $notes = $_POST['note_aggiuntive'];
                $status = $_POST['status'];
                $tables = json_decode($_POST['tavoli_assegnati']);

                if (isset($_POST['id'])) {
                    $reservation_id = $_POST['id'];

                    edit_customer($fiscal_code, $last_name, $first_name, $phone_number, $address);
                    edit_reservation($reservation_id, $fiscal_code, $date, $number_of_people, $notes, $status);
                    delete_all_booked_tables($reservation_id);
                    book_tables($reservation_id, $tables);
                } else {
                    do {
                        $reservation_id = bin2hex(random_bytes(5));
                    } while (!empty(get_reservations($reservation_id)));

                    create_customer($fiscal_code, $last_name, $first_name, $phone_number, $address);
                    create_reservation($reservation_id, $fiscal_code, $date, $number_of_people, $notes, $status);
                }

                break;

            case 'tables':
                $table_number = $_POST['id'];
                $number_of_seats = $_POST['n_posti'];
                $room = $_POST['sala'];

                if (empty(get_tables($table_number))) {
                    create_table($table_number, $number_of_seats, $room);
                } else {
                    edit_table($table_number, $number_of_seats, $room);
                }
                
                break;

            case 'rooms':
                $room_name = $_POST['nome_sala'];
                $room_type = $_POST['cod_tipo_sala'];

                if (isset($_POST['id'])) {
                    $room_id = $_POST['id'];
                    edit_dining_room($room_id, $room_name, $room_type);
                    
                } else {
                    create_dining_room($room_name, $room_type);
                }
                
                break;
                
            case 'accounts':
                $username = $_POST['username'];
                $password = $_POST['password'];
                $group = $_POST['gruppo'];

                if (isset($_POST['id'])) {
                    $token = $_POST['id'];
                    edit_account($token, $username, $password, $group);
                    
                } else {
                    create_account($username, $password, $group);
                }
                
                break;
        }
    }
    
    exit();
?>