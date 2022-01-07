<?php
    require_once __DIR__ . '/../includes/inc_auth.php';
    if (!isset($_SESSION['account']) || !has_permission('admin', $_SESSION['account']['cod_gruppo'])) {
        header('Location: ../errors/forbidden.php');
        exit();
    }

    if (!isset($_GET['id'])) {
        die("ID mancante");
    }

    $account_to_edit = get_accounts($_GET['id']);
    if (empty($account_to_edit)) {
        die("ID non valido");
    }
    
    $account_to_edit = $account_to_edit[0];
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
    <form class="form" action="../api/api_set.php?q=accounts" method="post">
        <h1>Modifica Account</h1>
        <input class="hidden" type="text" name="id" value="<?php echo $account_to_edit['token_accesso']; ?>" readonly>
        <input type="text" name="username" placeholder="Username" value="<?php echo $account_to_edit['username']; ?>" required>
        <input type="password" name="password" placeholder="Nuova Password">
        <select name="gruppo">
            <?php 
                $groups = get('gruppi'); 
                foreach ($groups as $g) {
                    $selected = $g['cod_gruppo'] == $account_to_edit['cod_gruppo'] ? 'selected' : '';
                    echo '<option value="' . $g['cod_gruppo'] . '" ' . $selected . '>' . $g['nome_gruppo'] . '</option>';
                }
            ?>
        </select>
        <button type="submit">VAI</button>
    </form>
    <form class="form" action="../api/api_delete.php?q=accounts" method="post">
        <input class="hidden" type="text" name="id" value="<?php echo $account_to_edit['username']; ?>" readonly>
        <button type="submit">RIMUOVI</button>
    </form>
</body>
</html>