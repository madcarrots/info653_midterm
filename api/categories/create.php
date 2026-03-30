<?php
    // headers
    header('Access-Controll-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Allowed-Methods, Authorization, X-Requested_With');

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $category_obj = new Category($db);

    // Get Raw Posted Data
    $data = json_decode(file_get_contents("php://input"));

    $category_obj->category = $data->category;


    // Create quote
    if($category_obj->create_category()) {
        echo json_encode(
            array('message' => 'Category Added to Database')
        );
    } else {
        echo json_encode(
            array('message' => 'Category could not be added.')
        );
    }



?>
