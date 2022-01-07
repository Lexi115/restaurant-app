<?php
    session_start();
    require_once 'includes/inc_auth.php';
    define('BOOKING', 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <?php 
        require 'includes/inc_header.php';
    ?>
    <section>
        <form class="form" action="api/api_set.php?q=reservations" method="post">
            <h1>Prenotazione</h1>
            <input type="text" name="cf" placeholder="Codice Fiscale" required>
            <input type="text" name="cognome" placeholder="Cognome" required>
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="tel" name="telefono" placeholder="Telefono" required>
            <input type="text" name="indirizzo" placeholder="Indirizzo">
            <input type="date" name="data" placeholder="Data" required>
            <input type="time" name="ora" placeholder="Ora" required>
            <input type="number" name="n_persone" placeholder="Numero persone" required>
            <textarea name="note_aggiuntive" placeholder="Note"></textarea>
            <button type="submit">Vai</button>
        </form>
    </section>
</body>
</html>