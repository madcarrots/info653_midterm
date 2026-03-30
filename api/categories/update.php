<?php
    // headers
    header('Access-Controll-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Allowed-Methods, Authorization, X-Requested_With');

    include_once '../config/Database.php';
    include_once '../models/Author.php';

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $category_obj = new Quote($db);

    // Get Raw Posted Data
    $data = json_decode(file_get_contents("php://input"));

    // Set ID to update
    $category_obj->id = $data->id;

     // Update quote
    if($quote_obj->update_quote()) {
        echo json_encode(
            array('message' => 'Category Successfully Updated')
        );
    } else {
        echo json_encode(
            array('message' => 'Category could not be updated.')
        );
    }