<?php
    require __DIR__ . '/../includes/functions/inc_accounts.php';
    require __DIR__ . '/../includes/functions/inc_reservations.php';

    $arr = array();

    if (isset($_GET['q'])) {
        switch ($_GET['q']) {

            case 'reservations':
                $status = isset($_GET['status']) ? $_GET['status'] : '%';
                $columns = isset($_GET['columns']) ? $_GET['columns'] : '*';
                $page = isset($_GET['page']) ? $_GET['page'] : '1';
                $rows = isset($_GET['rows']) ? $_GET['rows'] : 5;
                $id = isset($_GET['id']) ? $_GET['id'] : '%';
            
                $arr_count = get_reservations_count($status);
                $arr_reservations = get_reservations($id, $status, $rows, $page, $columns);
                $arr = array($arr_count, $arr_reservations);
                break;
            
            case 'accounts':
                $group = isset($_GET['group']) ? $_GET['group'] : '%';
                $columns = isset($_GET['columns']) ? $_GET['columns'] : '*';

                $arr = get_users('', '', $group, $columns);
                break;

        }
    }
    
    
    echo json_encode($arr);
    exit();
?>