<?php
    // headers
    header('Access-Controll-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Allowed-Methods, Authorization, X-Requested_With');

    include_once '../config/Database.php';
    include_once '../models/Quote.php';

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote_obj = new Quote($db);

    // Get Raw Posted Data
    $data = json_decode(file_get_contents("php://input"));

    // Set ID to update
    $quote_obj->id = $data->id;

    $quote_obj->author_id = $data->author_id;
    $quote_obj->category_id = $data->category_id;
    $quote_obj->quote = $data->quote;

    // Update quote
    if($quote_obj->update_quote()) {
        echo json_encode(
            array('message' => 'Quote Successfully Updated')
        );
    } else {
        echo json_encode(
            array('message' => 'Quote could not be updated.')
        );
    }



?>
