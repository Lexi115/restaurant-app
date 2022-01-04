<?php
    if (!isset($_GET['id'])) {
        die("ID mancante");
    }

    require_once __DIR__ . '/../includes/functions/inc_accounts.php';
    $account = get_accounts($_GET['id']);
    if (empty($account)) {
        die("ID non valido");
    }
    
    $account = $account[0];
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
    <form action="../api/api_set.php?q=accounts" method="post">
        <input class="hidden" type="text" name="id" value="<?php echo $account['token_accesso']; ?>" readonly>
        <input type="text" name="username" placeholder="Username" value="<?php echo $account['username']; ?>" required>
        <input type="password" name="password" placeholder="Nuova Password">
        <select name="gruppo">
            <?php 
                $groups = get('gruppi'); 
                foreach ($groups as $g) {
                    $selected = $g['cod_gruppo'] == $account['cod_gruppo'] ? 'selected' : '';
                    echo '<option value="' . $g['cod_gruppo'] . '" ' . $selected . '>' . $g['nome_gruppo'] . '</option>';
                }
            ?>
        </select>
        <button type="submit">VAI</button>
    </form>
    <form action="../api/api_delete.php?q=accounts" method="post">
        <input class="hidden" type="text" name="id" value="<?php echo $account['username']; ?>" readonly>
        <button type="submit">RIMUOVI</button>
    </form>
</body>
</html>