<?php
    class Quote {
        // DB stuff
        private $conn;
        private $table = 'quotes';

        //Post Properties
        public $id;
        public $category_id;
        public $category_name;
        public $author_id;
        public $author_name;
        public $body;
        

        //modules
        //constructor
        public function __construct($db) {
            $this->conn = $db;
        }

        //get posts
        public function read() {
            // create query
            // Update the SELECT statement to match the columns in quotesdb
            $query = 'SELECT
                q.id,
                q.author_id, 
                a.author,
                q.category_id,
                c.category,
                q.quote

              FROM
                ' . $this->table . ' q
                INNER JOIN categories c on 
                    q.category_id = c.id
                INNER JOIN authors a on
                    q.author_id = a.id
                ORDER BY
                    q.id
                ';


        // prepare statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
        }

        // Get single quote
        public function read_single () {
            $query = 'SELECT
                q.id,
                q.author_id, 
                a.author,
                q.category_id,
                c.category,
                q.quote

              FROM
                ' . $this->table . ' q
                INNER JOIN categories c on 
                    q.category_id = c.id
                INNER JOIN authors a on
                    q.author_id = a.id
                WHERE q.id = ?
                LIMIT 0,1
                ';
            // prepare statement
            $stmt = $this->conn->prepare($query);

            // bind ID
            $stmt->bindParam(1, $this->id);

            // execute query
            $stmt->execute();

            // prepare data to send back as response 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // set properties of data to be sent (from the read_single file)
            $this->id = $row['id'];
            $this->author_id = $row['author_id'];
            $this->author = $row['author'];
            $this->category_id = $row['category_id'];
            $this->category = $row['category'];
            $this->quote = $row['quote'];
        }


        // create quote
        public function create_quote () {
            // Here's where you are.
            // have you considered what to do when a new author or category is added?
            // also, don't you have to join things to make them happen? 
            // work on that next time. 
            // create query
            $query = 'INSERT INTO ' . $this->table . ' 
            SET 
                category_id = :category_id,
                quote = :quote,
                author_id = :author_id
            ';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));

            // Bind data
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);

            // execute query
            if($stmt->execute()) {
                return true;
            }

            // Print error if something is afoul
            printf("ErrorLL %s.\n", $STMT->error);
            return false;
        }


        public function update_quote () {

            $query = 'UPDATE ' . $this->table . ' 
            SET 
                category_id = :category_id,
                quote = :quote,
                author_id = :author_id
            WHERE
                id=:id
            ';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':id', $this->id);

            // execute query
            if($stmt->execute()) {
                return true;
            }

            // Print error if something is afoul
            printf("ErrorLL %s.\n", $STMT->error);
            return false;
        }

        // Delete Quote
        public function delete(){
            // create query
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
    }