<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // includes relative to file location
    include_once(__DIR__ . '/../../config/Database.php');
    include_once(__DIR__ . '/../../models/Category.php');
    
    // instantiate DB and Connect
    $database = new Database();
    $db = $database->connect();

    // instantiate new category_object
    $category_obj = new Category($db);

    // Get ID from URL
    $category_obj->id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null;

    if ($category_obj->id === null) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Get category
    $category_obj->read_single();

    // null response? 
    if ($category_obj->id == null) {
        echo json_encode(array('message' => 'category_id Not Found'));
    } else {
        // Create array
        $category_arr = array(
            'id'       => $category_obj->id,
            'category' => $category_obj->category,
        );

        // make JSON
        echo json_encode($category_arr);
    }
?>