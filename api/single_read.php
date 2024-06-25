<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';
    include_once '../class/employees.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Employee($db);

    $item->phone_id = isset($_GET['phone_id']) ? $_GET['phone_id'] : die();
  
    $item->getSingleEmployee();

    if($item->count != null){
        // create array
        $emp_arr = array(
            "id" =>  $item->id,
            "phone_id" => $item->phone_id,
            "count" => $item->count
        );
      
        http_response_code(200);
        echo json_encode($emp_arr);
    }
      
    else{
        http_response_code(404);
        echo json_encode("Employee not found.");
    }
?>