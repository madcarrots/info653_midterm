<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: appplication/json');
    
    // instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    // instantiate new quote_object
    $category_obj = new Category($db);

    // Get ID from URL
    $category_obj->id = isset($_GET['id']) ? $_GET['id'] : die("Missing Required Parameters");

    // Get quote
    $category_obj->read_single();

    // null response? 
    if ($category_obj->id == null) {{
        $response_arr = array(
            'message:' => 'category_id Not Found'
        );

        echo json_encode($response_arr);
    }} else {
        // Create array
        $category_arr = array(
            'id' => $category_obj->id,
            'category' => $category_obj->category,
        );

        // make JSON
        echo json_encode($category_arr);
    }







?>
