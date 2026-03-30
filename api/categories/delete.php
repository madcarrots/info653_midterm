<?php
    // headers
    header('Access-Controll-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Allowed-Methods, Authorization, X-Requested_With');

    include_once '../config/Database.php';
    include_once '../models/Author.php';

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $category_obj = new Category($db);

    // Get Raw Posted Data
    //$data = json_decode(file_get_contents("php://input"));

    // Set ID to update
    //$category_obj->id = $data->id;

    // Get ID from URL
    $category_obj->id = isset($_GET['id']) ? $_GET['id'] : die("Missing Required Parameters");


    // Delete category
    if($category_obj->delete()) {
        echo json_encode( $category_obj->id
        );
    } else {
        echo json_encode(
            array('message' => 'category_id Not Found')
        );
    }



?>
