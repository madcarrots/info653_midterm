<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // includes relative to file location
    include_once(__DIR__ . '/../../config/Database.php');
    include_once(__DIR__ . '/../../models/Author.php');
    
    // instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    // instantiate new author_object
    $author_obj = new Author($db);

    // Get ID from URL
    $author_obj->id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null;

    if ($author_obj->id === null) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Get author
    $author_obj->read_single();

    // null response? 
    if ($author_obj->id == null) {
        echo json_encode(array('message' => 'author_id Not Found'));
    } else {
        // Create array
        $author_arr = array(
            'id'     => $author_obj->id,
            'author' => $author_obj->author,
        );

        // make JSON
        echo json_encode($author_arr);
    }
?>