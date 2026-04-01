<?php
    // headers 
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // includes relative to file location
    include_once(__DIR__ . '/../../config/Database.php');
    include_once(__DIR__ . '/../../models/Author.php');
    
    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $author_obj = new Author($db);

    // Get Raw Posted Data
    $data = json_decode(file_get_contents("php://input"));

    // Check parameters
    if (!isset($data->author) || trim($data->author) === '') {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Set property
    $author_obj->author = $data->author;

    // Create author
    if ($author_obj->create_author()) {
        // Get the auto-generated ID
        $new_id = $db->lastInsertId();

        // Return the created author 
        echo json_encode(array(
            'id'     => (int)$new_id,
            'author' => $author_obj->author
        ));
    } else {
        echo json_encode(
            array('message' => 'Author could not be added.')
        );
    }
?>