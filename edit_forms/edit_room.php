<?php
    require_once __DIR__ . '/../includes/inc_auth.php';
    if (!isset($_SESSION['account']) || !has_permission('admin', $_SESSION['account']['cod_gruppo'])) {
        header('Location: ../errors/forbidden.php');
        exit();
    }

    if (!isset($_GET['id'])) {
        die("ID mancante");
    }

    require_once __DIR__ . '/../includes/functions/inc_rooms.php';
    $room = get_dining_rooms($_GET['id']);
    if (empty($room)) {
        die("ID non valido");
    }
    
    $room = $room[0];
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
    <form class="form" action="../api/api_set.php?q=rooms" method="post">
        <h1>Modifica Sala</h1>
        <input type="text" name="id" value="<?php echo $room['cod_sala']; ?>" readonly>
        <input type="text" name="nome_sala" placeholder="Nome sala" value="<?php echo $room['nome_sala']; ?>" required>
        <select name="cod_tipo_sala">
            <?php 
                $types = get('tipisala'); 
                foreach ($types as $t) {
                    $selected = $t['cod_tipo_sala'] == $room['cod_tipo_sala'] ? 'selected' : '';
                    echo '<option value="' . $t['cod_tipo_sala'] . '" ' . $selected . '>' . $t['nome_tipo_sala'] . '</option>';
                }
            ?>
        </select>
        <button type="submit">VAI</button>
    </form>
    <form class="form" action="../api/api_delete.php?q=rooms" method="post">
        <input class="hidden" type="text" name="id" value="<?php echo $room['cod_sala']; ?>" readonly>
        <button type="submit">RIMUOVI</button>
    </form>
</body>
</html>