<?php
    session_start();
    require_once __DIR__ . '/../includes/inc_auth.php';
    require_once __DIR__ . '/../includes/functions/inc_tables.php';

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
    <title>Crea Sala</title>
</head>
<body>
    <?php 
        require_once '../includes/inc_header.php';
    ?>
    <form class="form" action="../api/api_set.php?q=rooms" method="post">
        <h1>Nuova Sala</h1>
        <input type="text" name="nome_sala" placeholder="Nome sala" required>
        <select name="cod_tipo_sala">
            <?php 
                $types = get('tipisala'); 
                foreach ($types as $t) {
                    echo '<option value="' . $t['cod_tipo_sala'] . '">' . $t['nome_tipo_sala'] . '</option>';
                }
            ?>
        </select>
        <button type="submit">VAI</button>
    </form>
</body>
</html>