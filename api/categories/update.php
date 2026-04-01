<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // includes relative to file location
    include_once(__DIR__ . '/../../config/Database.php');
    include_once(__DIR__ . '/../../models/Category.php');

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate category object
    $category_obj = new Category($db);

    // Get Raw Posted Data
    $data = json_decode(file_get_contents("php://input"));

    // check parameters
    if (!isset($data->id) || !isset($data->category) || trim($data->category) === '') {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Set properties
    $category_obj->id       = $data->id;
    $category_obj->category = $data->category;

    // Update category
    if ($category_obj->update_category()) {  
        // Return the updated category 
        echo json_encode(array(
            'id'       => (int)$category_obj->id,
            'category' => $category_obj->category
        ));
    } else {
        echo json_encode(array('message' => 'category_id Not Found'));
    }
?>