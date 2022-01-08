<?php
    session_start();
    require_once __DIR__ . '/../includes/inc_auth.php';
    require_once __DIR__ . '/../includes/functions/inc_tables.php';

    // Vieta accesso ai non autorizzati
    if (no_permission('admin')) {
        header('Location: ../errors/forbidden.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Crea Tavolo</title>
</head>
<body>
    <?php 
        require_once '../includes/inc_header.php';
    ?>
    <form class="form" action="../api/api_set.php?q=tables" method="post">
        <h1>Nuovo Tavolo</h1>
        <input type="number" name="id" pattern="[0-9]+" placeholder="Numero tavolo" required>
        <input type="text" name="n_posti" placeholder="Numero posti" required>
        <select name="cod_sala">
            <?php 
                $rooms = get('sale'); 
                foreach ($rooms as $r) {
                    echo '<option value="' . $r['cod_sala'] . '">' . $r['nome_sala'] . '</option>';
                }
            ?>
        </select>
        <button type="submit">VAI</button>
    </form>
</body>
</html>
