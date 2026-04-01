<?php
    // headers 
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    // Check parameters
    if (!isset($data->category) || trim($data->category) === '') {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // Set property
    $category_obj->category = $data->category;

    // Create category
    if ($category_obj->create_category()) {
        // Get the auto-generated ID
        $new_id = $db->lastInsertId();

        // Return the created category 
        echo json_encode(array(
            'id'       => (int)$new_id,
            'category' => $category_obj->category
        ));
    } else {
        echo json_encode(
            array('message' => 'Category could not be added.')
        );
    }
?>