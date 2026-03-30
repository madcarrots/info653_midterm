<?php
    // headers
    header('Access-Controll-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Allowed-Methods, Authorization, X-Requested_With');

    include_once '../config/Database.php';
    include_once '../models/Quote.php';

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote_obj = new Quote($db);

    // Get Raw Posted Data
    // $data = json_decode(file_get_contents("php://input"));

    // Set ID to update
    // $quote_obj->id = $data->id;

        // Get ID from URL
    $quote_obj->id = isset($_GET['id']) ? $_GET['id'] : die("Missing Required Parameters");


    // Delete quote
    if($quote_obj->delete()) {
        echo json_encode( $quote_obj->id
        );
    } else {
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }



?>
