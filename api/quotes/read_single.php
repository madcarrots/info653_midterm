<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    // includes relative to file location
    include_once(__DIR__ . '/../../config/Database.php');
    include_once(__DIR__ . '/../../models/Quote.php');

    // instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    // instantiate new quote_object
    $quote_obj = new Quote($db);

    // Get ID from URL
    $quote_obj->id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null;

    if ($quote_obj->id === null) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Get quote
    $quote_obj->read_single();

    // null response? 
    if ($quote_obj->id == null) {
        echo json_encode(array('message' => 'No Quotes Found'));
    } else {
        // Create array
        $quote_arr = array(
            'id'       => $quote_obj->id,
            'author'   => $quote_obj->author,
            'category' => $quote_obj->category,
            'quote'    => $quote_obj->quote
        );

        // make JSON
        echo json_encode($quote_arr);
    }
?>