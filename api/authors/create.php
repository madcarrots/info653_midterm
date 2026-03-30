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
    $author_obj = new Author($db);

    // Get Raw Posted Data
    $data = json_decode(file_get_contents("php://input"));

    $author_obj->author = $data->author;


    // Create quote
    if($author_obj->create_author()) {
        echo json_encode(
            array('message' => 'Author Added to Database')
        );
    } else {
        echo json_encode(
            array('message' => 'Author could not be added.')
        );
    }



?>
