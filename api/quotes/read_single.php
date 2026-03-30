<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: appplication/json');

    // instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    // instantiate new quote_object
    $quote_obj = new Quote($db);

    // Get ID from URL
    $quote_obj->id = isset($_GET['id']) ? $_GET['id'] : die("this tang ded");

    // Get quote
    $quote_obj->read_single();

    // Create array
    $quote_arr = array(
        'id' => $quote_obj->id,
    //    'author_id' => $quote_obj->author_id,
        'author' => $quote_obj->author,
    //    'category_id' => $quote_obj->category_id,
        'category' => $quote_obj->category,
        'quote' => $quote_obj->quote

    );

    // make JSON
    echo json_encode($quote_arr);





?>
