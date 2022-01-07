<?php
    session_start();
    require_once __DIR__ . '/../includes/inc_auth.php';
    require_once __DIR__ . '/../includes/functions/inc_tables.php';

    if (!isset($_SESSION['account']) || !has_permission('admin', $_SESSION['account']['cod_gruppo'])) {
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
</head>
<body>
    <?php 
        require_once '../includes/inc_header.php';
    ?>
    <form class="form" action="../api/api_set.php?q=tables" method="post">
        <h1>Nuovo Tavolo</h1>
        <input type="number" name="id" pattern="[0-9]+" placeholder="Numero tavolo" required>
        <input type="text" name="n_posti" placeholder="Numero posti" required>
        <select name="sala">
            <?php 
                $rooms = get('sale'); 
                foreach ($rooms as $r) {
                    echo '<option value="' . $r['cod_sala'] . '">' . $r['nome_sala'] . '</option>';
                }
            ?>
        </select>
        <button type="submit" name="submit">VAI</button>
    </form>
</body>
</html>