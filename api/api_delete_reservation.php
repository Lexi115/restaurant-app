<?php 
    require __DIR__ . '/../includes/functions/inc_reservations.php';
    if (isset($_POST['delete'])) {
        $reservation_id = $_POST['id'];
        delete_reservation($reservation_id);
    }

    exit();
?>