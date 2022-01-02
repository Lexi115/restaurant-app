<?php
    require __DIR__ . '/../includes/functions/inc_reservations.php';

    if (isset($_POST['submit'])) {
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

        edit_customer($fiscal_code, $last_name, $first_name, $phone_number, $address);
        edit_reservation($reservation_id, $fiscal_code, $date, $number_of_people, $notes, $status);

        echo "<br><br>done";
    }
    
    exit();
?>