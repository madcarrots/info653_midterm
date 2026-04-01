<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // includes relative to file location
    include_once(__DIR__ . '/../../config/Database.php');
    include_once(__DIR__ . '/../../models/Category.php');
    
    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate category object
    $category_obj = new Category($db);

    // Get ID from URL (required for delete)
    $category_obj->id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null;

    if ($category_obj->id === null) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Delete category
    if ($category_obj->delete()) {
        echo json_encode(array('id' => (int)$category_obj->id));
    } else {
        echo json_encode(array('message' => 'category_id Not Found'));
    }
?>