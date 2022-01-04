<?php
    if (!isset($_GET['id'])) {
        die("ID mancante");
    }

    require_once __DIR__ . '/../includes/functions/inc_reservations.php';
    $reservation = get_reservations($_GET['id'], '%');
    if (empty($reservation)) {
        die("ID non valido");
    }
    
    $reservation = $reservation[0];
    $date_time = explode(' ', $reservation['data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <form action="../api/api_set.php?q=reservations" method="post">
        <input type="text" name="id" value="<?php echo $reservation['cod_prenotazione']; ?>" readonly>
        <input type="text" name="cf" placeholder="Codice Fiscale" value="<?php echo $reservation['cf_cliente']; ?>" required>
        <input type="text" name="cognome" placeholder="Cognome" value="<?php echo $reservation['cognome']; ?>" required>
        <input type="text" name="nome" placeholder="Nome" value="<?php echo $reservation['nome']; ?>" required>
        <input type="tel" name="telefono" placeholder="Telefono" value="<?php echo $reservation['telefono']; ?>" required>
        <input type="text" name="indirizzo" placeholder="Indirizzo" value="<?php echo $reservation['indirizzo']; ?>" required>
        <input type="date" name="data" placeholder="Data" value="<?php echo $date_time[0]; ?>" required>
        <input type="time" name="ora" placeholder="Ora" value="<?php echo $date_time[1]; ?>" required>
        <input type="number" name="n_persone" placeholder="Numero persone" value="<?php echo $reservation['n_persone']; ?>" required>
        <select name="status">
            <?php 
                $states = get('statusprenotazione');
                foreach ($states as $s) {
                    $selected = $s['cod_status'] == $reservation['cod_status'] ? 'selected' : '';
                    echo '<option value="' . $s['cod_status'] . '" ' . $selected . '>' . $s['descrizione_status'] . '</option>';
                }
            ?>
        </select>
        <div id="tables-list-container"></div>
        <input class="hidden" type="text" name="tavoli_assegnati" id="tables-json-string" value='<?php echo json_encode($reservation['tavoli_assegnati']); ?>'>
        <textarea name="note_aggiuntive" placeholder="Note"><?php echo $reservation['note_aggiuntive']; ?></textarea>
        <button type="submit" name="submit">VAI</button>
    </form>
    <form action="../api/api_delete.php?q=reservations" method="post">
        <input class="hidden" type="text" name="id" value="<?php echo $reservation['cod_prenotazione']; ?>" readonly>
        <button type="submit">RIMUOVI</button>
    </form>

    
    <script src="../js/edit_reservation.js"></script>
</body>
</html>