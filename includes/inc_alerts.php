<?php
    function print_alert($id) {
        switch ($id) {
            case 'invalid-password':
                echo err_alert('Password non valida!');
                break;
            
            case 'invalid-user':
                echo err_alert('Utente non trovato!');
                break;
        }
    }

    function err_alert($message) {
        return '<span class="alert err-alert">' . $message . '</span>';
    }
