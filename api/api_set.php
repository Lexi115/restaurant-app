<?php
    require_once __DIR__ . '/../includes/functions/inc_reservations.php';
    require_once __DIR__ . '/../includes/inc_auth.php';

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
                
                if (isset($_POST['id'])) {
                    if (no_permission('mostra_prenotazioni')) {
                        header('Location: errors/forbidden.php');
                        exit();
                    }
    
                    $reservation_id = $_POST['id'];
                    $tables = json_decode($_POST['tavoli_assegnati']);
                    $status = $_POST['cod_status'];

                    edit_customer($fiscal_code, $last_name, $first_name, $phone_number, $address);
                    edit_reservation($reservation_id, $fiscal_code, $date, $number_of_people, $notes, $status);
                    delete_all_booked_tables($reservation_id);
                    book_tables($reservation_id, $tables);
                    header('Location: ../reservations_list.php');
                } else {
                    do {
                        $reservation_id = bin2hex(random_bytes(5));
                    } while (!empty(get_reservations($reservation_id)));

                    create_customer($fiscal_code, $last_name, $first_name, $phone_number, $address);
                    create_reservation($reservation_id, $fiscal_code, $date, $number_of_people, $notes, 1);
                    header('Location: ../index.php');
                }

                break;

            case 'tables':
                if (no_permission('admin')) {
                    header('Location: errors/forbidden.php');
                    exit();
                }

                $table_number = $_POST['id'];
                $number_of_seats = $_POST['n_posti'];
                $room = $_POST['cod_sala'];

                if (empty(get_tables($table_number))) {
                    create_table($table_number, $number_of_seats, $room);
                } else {
                    edit_table($table_number, $number_of_seats, $room);
                }
                
                header('Location: ../tables_list.php');
                break;

            case 'rooms':
                if (no_permission('admin')) {
                    header('Location: errors/forbidden.php');
                    exit();
                }

                $room_name = $_POST['nome_sala'];
                $room_type = $_POST['cod_tipo_sala'];

                if (isset($_POST['id'])) {
                    $room_id = $_POST['id'];
                    edit_dining_room($room_id, $room_name, $room_type);
                } else {
                    create_dining_room($room_name, $room_type);
                }

                header('Location: ../rooms_list.php');
                break;
                
            case 'accounts':
                if (no_permission('admin')) {
                    header('Location: errors/forbidden.php');
                    exit();
                }
                
                $username = $_POST['username'];
                $password = $_POST['password'];
                $group = $_POST['cod_gruppo'];

                if (isset($_POST['id'])) {
                    $token = $_POST['id'];
                    edit_account($token, $username, $password, $group);
                    
                } else {
                    create_account($username, $password, $group);
                }
                
                header('Location: ../accounts_list.php');
                break;
        }
    }
    
    exit();
?>