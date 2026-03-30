<?php
    // headers
    header('Access-Controll-Allow-Origin: *');
    header('Content-type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote_obj = new Quote($db);

    // Quote query
    $result = $quote_obj->read();
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
        // no posts
        echo json_encode(
            array('message' => 'No quotes found.')
        );
    }
    

?>
