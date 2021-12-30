<?php
    require 'functions/inc_accounts.php';

    if (true) {
        $username = 'abc';
        $password = 'cde';
        $group = 'default';

        $user_created = create_user($username, $password, $group);
        if ($user_created) {
            echo "Creato";
        } else {
            echo "Non creato";
        }

    }

    exit();