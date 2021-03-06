<?php
    session_start();
    require_once __DIR__ . '/../includes/inc_auth.php';

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
    <title>Crea Account</title>
</head>
<body>
    <?php 
        require_once '../includes/inc_header.php';
    ?>
    <form class="form" action="../api/api_set.php?q=accounts" method="post">
        <h1>Nuovo Account</h1>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="cod_gruppo">
            <?php 
                $groups = get('gruppi'); 
                foreach ($groups as $g) {
                    echo '<option value="' . $g['cod_gruppo'] . '">' . $g['nome_gruppo'] . '</option>';
                }
            ?>
        </select>
        <button type="submit">VAI</button>
    </form>
</body>
</html>
