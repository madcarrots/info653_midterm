<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: appplication/json');

    // instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    // instantiate new quote_object
    $author_obj = new Author($db);

    // Get ID from URL
    $author_obj->id = isset($_GET['id']) ? $_GET['id'] : die("Missing Required Parameters");

    // Get quote
    $author_obj->read_single();

    // null response? 
    if ($author_obj->id == null) {{
        $response_arr = array(
            'message:' => 'author_id Not Found'
        );

        echo json_encode($response_arr);
    }} else {
        // Create array
        $author_arr = array(
            'id' => $author_obj->id,
            'author' => $author_obj->author,
        );

        // make JSON
        echo json_encode($author_arr);
    }







?>
