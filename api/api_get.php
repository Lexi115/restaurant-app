<?php
    session_start();
    require_once __DIR__ . '/../includes/inc_auth.php';
    require_once __DIR__ . '/../includes/functions/inc_reservations.php';
    
    $arr = array();

    if (isset($_GET['q'])) {
        switch ($_GET['q']) {

            case 'reservations':
                if (!isset($_SESSION['account']) || !has_permission('mostra_prenotazioni', $_SESSION['account']['cod_gruppo'])) {
                    header('Location: ../errors/forbidden.php');
                    exit();
                }
                $status = isset($_GET['status']) ? $_GET['status'] : '%';
                $columns = isset($_GET['columns']) ? $_GET['columns'] : '*';
                $page = isset($_GET['page']) ? $_GET['page'] : '1';
                $rows = isset($_GET['rows']) ? $_GET['rows'] : 5;
                $id = isset($_GET['id']) ? $_GET['id'] : '%';
            
                $count = get_count('prenotazioni', 'cod_status', $status);
                $records = get_reservations($id, $status, $rows, $page, $columns);
                $arr = array($count, $records);
                break;

            case 'tables':
                if (!isset($_SESSION['account']) || !has_permission('admin', $_SESSION['account']['cod_gruppo'])) {
                    header('Location: ../errors/forbidden.php');
                    exit();
                }

                $room = isset($_GET['room']) ? $_GET['room'] : '%';
                $columns = isset($_GET['columns']) ? $_GET['columns'] : '*';
                $page = isset($_GET['page']) ? $_GET['page'] : '1';
                $id = isset($_GET['id']) ? $_GET['id'] : '%';
                $rows = isset($_GET['rows']) ? $_GET['rows'] : 5;
                
                $count = get_count('tavoli', 'cod_sala', $room);
                $records = get_tables($id, $room, $rows, $page, $columns);
                $arr = array($count, $records);
                break;

            case 'rooms':
                if (!isset($_SESSION['account']) || !has_permission('admin', $_SESSION['account']['cod_gruppo'])) {
                    header('Location: ../errors/forbidden.php');
                    exit();
                }

                $type = isset($_GET['type']) ? $_GET['type'] : '%';
                $columns = isset($_GET['columns']) ? $_GET['columns'] : '*';
                $page = isset($_GET['page']) ? $_GET['page'] : '1';
                $id = isset($_GET['id']) ? $_GET['id'] : '%';
                $rows = isset($_GET['rows']) ? $_GET['rows'] : 5;
                
                $count = get_count('sale', 'cod_tipo_sala', $type);
                $records = get_dining_rooms($id, $type, $rows, $page, $columns);
                $arr = array($count, $records);
                break;
            
            case 'accounts':
                if (!isset($_SESSION['account']) || !has_permission('admin', $_SESSION['account']['cod_gruppo'])) {
                    header('Location: ../errors/forbidden.php');
                    exit();
                }

                $group = isset($_GET['group']) ? $_GET['group'] : '%';
                $columns = isset($_GET['columns']) ? $_GET['columns'] : '*';
                $page = isset($_GET['page']) ? $_GET['page'] : '1';
                $id = isset($_GET['id']) ? $_GET['id'] : '%';
                $rows = isset($_GET['rows']) ? $_GET['rows'] : 5;

                $count = get_count('accounts', 'cod_gruppo', $group);
                $records = get_accounts('', $id, $group, $rows, $page, $columns);
                $arr = array($count, $records);
                break;

        }
    }
    
    
    echo json_encode($arr);
    exit();
?>