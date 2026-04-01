<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    
    // includes relative to file location
    include_once(__DIR__ . '/../../config/Database.php');
    include_once(__DIR__ . '/../../models/Quote.php');

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote_obj = new Quote($db);

    // Get Raw Posted Data
    $data = json_decode(file_get_contents("php://input"));

    // Check parameters
    if (!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id) ||
        trim($data->quote) === '') {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    // does author exist?
    $stmt = $db->prepare("SELECT id FROM authors WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $data->author_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit();
    }

    // does category exist?
    $stmt = $db->prepare("SELECT id FROM categories WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $data->category_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit();
    }

    // Set properties
    $quote_obj->id          = $data->id;
    $quote_obj->quote       = $data->quote;
    $quote_obj->author_id   = $data->author_id;
    $quote_obj->category_id = $data->category_id;

    // Update quote
    if ($quote_obj->update_quote()) {
        // Return the updated quote 
        echo json_encode(array(
            'id'          => (int)$quote_obj->id,
            'quote'       => $quote_obj->quote,
            'author_id'   => (int)$quote_obj->author_id,
            'category_id' => (int)$quote_obj->category_id
        ));
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
?>