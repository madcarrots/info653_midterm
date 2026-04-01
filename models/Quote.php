<?php
    class Quote {
        // DB stuff
        private $conn;
        private $table = 'quotes';

        // Post Properties (updated to match operation files)
        public $id;
        public $quote;           // was $body
        public $author_id;
        public $author;          // was $author_name
        public $category_id;
        public $category;        // was $category_name

        // Constructor
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get all quotes (with optional author_id / category_id filters)
        public function read($author_id = null, $category_id = null) {
            // Base query 
            $query = "SELECT 
                q.id, 
                q.quote, 
                q.author_id, 
                q.category_id,
                a.author,
                c.category
              FROM quotes q
              LEFT JOIN authors a ON q.author_id = a.id
              LEFT JOIN categories c ON q.category_id = c.id";
              
            // Add filters dynamically
            $where = [];
            
            if ($author_id !== null) {
                $where[] = "q.author_id = :author_id";
            }
            
            if ($category_id !== null) {
                $where[] = "q.category_id = :category_id";
            }
            
            if (count($where) > 0) {
                $query .= " WHERE " . implode(" AND ", $where);
            }
            
            $query .= " ORDER BY q.id DESC";
            
            // Prepare and bind
            $stmt = $this->conn->prepare($query);
        
            if ($author_id !== null) {
                $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
            }
            
            if ($category_id !== null) {
                $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            }
        
            $stmt->execute();
            return $stmt;
        }

        // Get single quote
        public function read_single() {
            $query = 'SELECT
                q.id,
                q.author_id, 
                a.author,
                q.category_id,
                c.category,
                q.quote
              FROM ' . $this->table . ' q
                INNER JOIN categories c ON q.category_id = c.id
                INNER JOIN authors a ON q.author_id = a.id
              WHERE q.id = ?
              LIMIT 0,1';
            
            // prepare statement
            $stmt = $this->conn->prepare($query);

            // bind ID
            $stmt->bindParam(1, $this->id);

            // execute query
            $stmt->execute();

            // prepare data to send back as response 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // set properties 
                $this->id          = $row['id'];
                $this->author_id   = $row['author_id'];
                $this->author      = $row['author'];
                $this->category_id = $row['category_id'];
                $this->category    = $row['category'];
                $this->quote       = $row['quote'];
            } else {
                $this->id = null;   // so read_single.php knows it was not found
            }
        }

        // Create quote
        public function create_quote() {
            $query = 'INSERT INTO ' . $this->table . ' 
                      SET 
                          category_id = :category_id,
                          quote       = :quote,
                          author_id   = :author_id';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->quote       = htmlspecialchars(strip_tags($this->quote));
            $this->author_id   = htmlspecialchars(strip_tags($this->author_id));

            // Bind data
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':quote',       $this->quote);
            $stmt->bindParam(':author_id',   $this->author_id);

            // execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error (fixed typo)
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Update quote
        public function update_quote() {
            $query = 'UPDATE ' . $this->table . ' 
                      SET 
                          category_id = :category_id,
                          quote       = :quote,
                          author_id   = :author_id
                      WHERE id = :id';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->quote       = htmlspecialchars(strip_tags($this->quote));
            $this->author_id   = htmlspecialchars(strip_tags($this->author_id));
            $this->id          = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':quote',       $this->quote);
            $stmt->bindParam(':author_id',   $this->author_id);
            $stmt->bindParam(':id',          $this->id);

            // execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error (fixed typo)
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Delete Quote
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

            // Print error (fixed typo)
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }
?>