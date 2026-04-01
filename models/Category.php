<?php
    class Category {
        // DB stuff
        private $conn;
        private $table = 'categories';

        // properties
        public $id;
        public $category;

        // constructor with database
        public function __construct($db) {
            $this->conn = $db;
        }  

        // get all categories
        public function read() {
            // create query
            $query = 'SELECT
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
        public function read_single() {
            $query = 'SELECT
                id,
                category
              FROM ' . $this->table . '
              WHERE id = :id
              LIMIT 0,1';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // bind ID
            $stmt->bindParam(':id', $this->id);

            // execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // set properties 
            if ($row) {
                $this->id       = $row['id'];
                $this->category = $row['category'];
            } else {
                $this->id = null;   // so read_single.php knows it was not found
            }
        }

        // Create category
        public function create_category() {
            $query = 'INSERT INTO ' . $this->table . ' 
                      SET 
                          category = :category';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            
            // Bind data
            $stmt->bindParam(':category', $this->category);

            // execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Update category
        public function update_category() {
            $query = 'UPDATE ' . $this->table . ' 
                      SET 
                          category = :category
                      WHERE id = :id';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id       = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id',       $this->id);

            // execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Delete category
        public function delete() {
            // create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // execute query
            if ($stmt->execute()) {
                return $stmt->rowCount() > 0;   // true only if a row was actually deleted
            }

            // Print error 
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }
?>