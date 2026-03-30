<?php
    // headers
    header('Access-Controll-Allow-Origin: *');
    header('Content-type: application/json');

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Author object
    $author = new Author($db);

    // Author read query
    $result = $author->read();
    // get row count
    $num = $result->rowCount();

    // Check if there are any quotes
    if($num > 0) {
        // initialize array
        $authors_arr = array();
        $authors_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $author_item = array (
                'id' => $id,
                'author' => $author
            );

            // push to "data"
            array_push($authors_arr['data'], $author_item);
        }    

        // encode to JSON and output
        echo json_encode($authors_arr);
    } else {
        // no authors?
        echo json_encode(
            array('message' => 'No authors found.')
        );
    }
    

?>
