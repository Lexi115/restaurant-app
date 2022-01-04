<?php
    require_once __DIR__ . '/../includes/functions/inc_accounts.php';
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
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="gruppo">
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