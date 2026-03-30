<?php
    class Category {
        // database basics
        private $conn;
        private $table = 'categories';

        // properties
        public $id;
        public $category;

        // constructor with database
        public function __construct($db) {
            $this->conn = $db;
        }  

        // get categories
        public function read() {
            // create query
            $query = 'Select
                id, 
                category 
            FROM ' . $this->table . ' 
            ORDER BY category ASC';

            // prepare the query
            $stmt = $this->conn->prepare($query);

            // execute the query
            $stmt->execute();

            return $stmt;
        }

        // Get single category
        public function read_single () {
            $query = 'SELECT
                id,
                category
                FROM
                ' . $this->table . // ' 
                // WHERE EXISTS (SELECT 1 FROM  ' . $this->table . ' WHERE id = :id)
                ' WHERE id = :id
                ';
            // prepare statement
            $stmt = $this->conn->prepare($query);

            // bind ID
            $stmt->bindParam(':id', $this->id);

            // execute query
            $stmt->execute();


            $row = $stmt->fetch(PDO::FETCH_ASSOC);


            // set properties
            $this->id = $row['id'];
            $this->category = $row['category'];
        }

        // Delete Category
        public function delete(){
            // create category
            $query  = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // execute query

            if($stmt->execute()) {

                $affected_rows = $stmt->rowCount();

                if ($affected_rows > 0) {
                    return true;
                } else {
                    return false;
                }
            }
            
            // Print error if something is afoul
            printf("ErrorLL %s.\n", $STMT->error);
            return false;
            
        }

        // create category
        public function create_category () {

            $query = 'INSERT IGNORE INTO ' . $this->table . ' 
            SET 
                category = :category
            ';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            
            // Bind data
            $stmt->bindParam(':category', $this->category);


            if($stmt->execute()) {

                $affected_rows = $stmt->rowCount();

                if ($affected_rows > 0) {
                    return true;
                } else {
                    return false;
                }
            }

            // Print error if something is afoul
            printf("ErrorLL %s.\n", $STMT->error);
            return false;
        }

        public function update_category () {

            $query = 'UPDATE ' . $this->table . ' 
            SET 
                category = :category
            WHERE
                id=:id
            ';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);

            // execute query
            if($stmt->execute()) {
                return true;
            }

            // Print error if something is afoul
            printf("ErrorLL %s.\n", $STMT->error);
            return false;
        }


}