<?php
    // headers
    header('Access-Controll-Allow-Origin: *');
    header('Content-type: application/json');
    
    // includes relative to file location
    include_once(__DIR__ . '/../../config/Database.php');
    include_once(__DIR__ . '/../../models/Quote.php');

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote_obj = new Quote($db);

    // Get optional filters from query string 
    $author_id   = isset($_GET['author_id']) && $_GET['author_id'] !== '' 
                   ? (int)$_GET['author_id'] 
                   : null;

    $category_id = isset($_GET['category_id']) && $_GET['category_id'] !== '' 
                   ? (int)$_GET['category_id'] 
                   : null;

    // Quote query 
    $result = $quote_obj->read($author_id, $category_id);

    // get row count
    $num = $result->rowCount();

    // Check if there are any quotes
    if($num > 0) {
        // initialize array
        $quotes_arr = array();
        $quotes_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array (
                'quotes.id' => $id,
                'author_id' => $author_id,
                'category_id' => $category_id,
                'author' => $author,
                'category' => $category,
                'quote' => $quote
            );

            // push to "data"
            array_push($quotes_arr['data'], $quote_item);
        }    

        // encode to JSON and output
        echo json_encode($quotes_arr);
    } else {
        // no quotes found 
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
?>