<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    
    // includes relative to file location
    include_once(__DIR__ . '/../../config/Database.php');
    include_once(__DIR__ . '/../../models/Author.php');

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $author_obj = new Author($db);

    // Get ID from URL (required for delete)
    $author_obj->id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null;

    if ($author_obj->id === null) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Delete author
    if ($author_obj->delete()) {
        echo json_encode(array('id' => (int)$author_obj->id));
    } else {
        echo json_encode(array('message' => 'author_id Not Found'));
    }
?>