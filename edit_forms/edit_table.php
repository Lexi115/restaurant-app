<?php
    require_once __DIR__ . '/../includes/inc_auth.php';

    // Vieta accesso ai non autorizzati
    if (no_permission('admin')) {
        header('Location: ../errors/forbidden.php');
        exit();
    }

    // Controlla che sia stato passato il parametro di ricerca
    if (!isset($_GET['id'])) {
        die("ID mancante");
    }

    require_once __DIR__ . '/../includes/functions/inc_tables.php';
    $table = get_tables($_GET['id'], '%');
    if (empty($table)) {
        die("ID non valido");
    }

    $table = $table[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Modifica Tavolo <?php echo $_GET['id']; ?></title>
</head>
<body>
    <?php 
        require_once '../includes/inc_header.php';
    ?>
    <form class="form" action="../api/api_set.php?q=tables" method="post">
        <h1>Modifica Tavolo</h1>
        <input type="text" name="id" value="<?php echo $table['n_tavolo']; ?>" readonly>
        <input type="text" name="n_posti" placeholder="Numero posti" value="<?php echo $table['n_posti']; ?>" required>
        <select name="cod_sala">
            <?php 
                $rooms = get('sale'); 
                foreach ($rooms as $r) {
                    $selected = $r['cod_sala'] == $table['cod_sala'] ? 'selected' : '';
                    echo '<option value="' . $r['cod_sala'] . '" ' . $selected . '>' . $r['nome_sala'] . '</option>';
                }
            ?>
        </select>
        <button type="submit">VAI</button>
    </form>
    <form class="form" action="../api/api_delete.php?q=tables" method="post">
        <input class="hidden" type="text" name="id" value="<?php echo $table['n_tavolo']; ?>" readonly>
        <button type="submit">RIMUOVI</button>
    </form>
</body>
</html>
