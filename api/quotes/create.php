<?php
    // headers
    header('Access-Controll-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    $quote_obj->author_id = $data->author_id;
    $quote_obj->category_id = $data->category_id;
    $quote_obj->quote = $data->quote;

    // Create quote
    if($quote_obj->create_quote()) {
        echo json_encode(
            array('message' => 'Quote Added to Database')
        );
    } else {
        echo json_encode(
            array('message' => 'Quote could not be added.')
        );
    }



?>
