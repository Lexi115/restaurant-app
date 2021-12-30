<?php
    require __DIR__ . '/../includes/functions/inc_reservations.php';

    if (isset($_POST['submit'])) {
        $fiscal_code = $_POST['cf'];
        $last_name = $_POST['cognome'];
        $first_name = $_POST['nome'];
        $phone_number = $_POST['telefono'];
        $address = $_POST['indirizzo'];
        $date = $_POST['data'];
        $time = $_POST['ora'];
        $number_of_people = $_POST['n_persone'];
        $notes = $_POST['note_aggiuntive'];

        create_customer($fiscal_code, $last_name, $first_name, $phone_number, $address);
        create_reservation($fiscal_code, $date, $time, $number_of_people, $notes);

        echo "<br><br>done";
    }
?>