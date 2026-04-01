<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    // Check Parameters
    if (!isset($data->id) || !isset($data->author) || trim($data->author) === '') {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Set properties
    $author_obj->id     = $data->id;
    $author_obj->author = $data->author;

    // Update author
    if ($author_obj->update_author()) {   
        // Return the updated author 
        echo json_encode(array(
            'id'     => (int)$author_obj->id,
            'author' => $author_obj->author
        ));
    } else {
        echo json_encode(array('message' => 'author_id Not Found'));
    }
?>