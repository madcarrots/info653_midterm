<?php
    class Author {
        // DB stuff
        private $conn;
        private $table = 'authors';

        // properties
        public $id;
        public $author;

        // constructor with database
        public function __construct($db) {
            $this->conn = $db;
        }  

        // get all authors
        public function read() {
            // create query
            $query = 'SELECT
                id, 
                author 
            FROM ' . $this->table . ' 
            ORDER BY author ASC';

            // prepare the query
            $stmt = $this->conn->prepare($query);

            // execute the query
            $stmt->execute();

            return $stmt;
        }

        // Get single author
        public function read_single() {
            $query = 'SELECT
                id,
                author
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
                $this->id     = $row['id'];
                $this->author = $row['author'];
            } else {
                $this->id = null;   // so read_single.php knows it was not found
            }
        }

        // Create author
        public function create_author() {
            $query = 'INSERT INTO ' . $this->table . ' 
                      SET 
                          author = :author';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            
            // Bind data
            $stmt->bindParam(':author', $this->author);

            // execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error 
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Update author
        public function update_author() {
            $query = 'UPDATE ' . $this->table . ' 
                      SET 
                          author = :author
                      WHERE id = :id';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id     = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id',     $this->id);

            // execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error 
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Delete author
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