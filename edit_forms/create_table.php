<?php
    require_once __DIR__ . '/../includes/functions/inc_tables.php';
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
    <form action="../api/api_edit_table.php" method="post">
        <input type="text" name="id" required>
        <input type="text" name="n_posti" placeholder="Numero posti" required>
        <select name="sala">
            <?php 
                $rooms = get_dining_rooms(); 
                foreach ($rooms as $r) {
                    $selected = $r['cod_sala'] == $table['cod_sala'] ? 'selected' : '';
                    echo '<option value="' . $r['cod_sala'] . '" ' . $selected . '>' . $r['nome_sala'] . '</option>';
                }
            ?>
        </select>
        <button type="submit" name="submit">VAI</button>
    </form>
    <form action="../api/api_delete.php?q=table" method="post">
        <input class="hidden" type="text" name="id" value="<?php echo $table['numero_tavolo']; ?>" readonly>
        <button type="submit">RIMUOVI</button>
    </form>
</body>
</html>