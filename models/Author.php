<?php
    class Author {
        // database basics
        private $conn;
        private $table = 'authors';

        // properties
        public $id;
        public $author;

        // constructor with database
        public function __construct($db) {
            $this->conn = $db;
        }  

        // get authors
        public function read() {
            // create query
            $query = 'Select
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
        public function read_single () {
            $query = 'SELECT
                id,
                author
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
            $this->author = $row['author'];
        }

        // Delete Author
        public function delete(){
            // create author
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

        // create author
        public function create_author () {

            $query = 'INSERT IGNORE INTO ' . $this->table . ' 
            SET 
                author = :author
            ';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            
            // Bind data
            $stmt->bindParam(':author', $this->author);


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

        public function update_author () {

            $query = 'UPDATE ' . $this->table . ' 
            SET 
                author = :author
            WHERE
                id=:id
            ';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':author', $this->author);
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