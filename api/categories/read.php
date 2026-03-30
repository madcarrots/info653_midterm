<?php
    // headers
    header('Access-Controll-Allow-Origin: *');
    header('Content-type: application/json');

    // instantiate database and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Category object
    $category = new Category($db);

    // Category read query
    $result = $category->read();
    // get row count
    $num = $result->rowCount();

    // Check if there are any quotes
    if($num > 0) {
        // initialize array
        $categories_arr = array();
        $categories_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $category_item = array (
                'id' => $id,
                'category' => $category
            );

            // push to "data"
            array_push($categories_arr['data'], $category_item);
        }    

        // encode to JSON and output
        echo json_encode($categories_arr);
    } else {
        // no categories?
        echo json_encode(
            array('message' => 'No categories found.')
        );
    }
    

?>
