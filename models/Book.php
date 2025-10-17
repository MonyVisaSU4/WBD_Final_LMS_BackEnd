<?php
class Book {
    private $conn;
    private $table = 'books';

    public $id;
    public $title;
    public $author;
    public $created_at;
    public $ISBN;
    public $publishYear;
    public $page;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 (title, author, created_at, ISBN, publishYear, page, status) 
                 VALUES 
                 (:title, :author, :created_at, :ISBN, :publishYear, :page, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->created_at = date('Y-m-d H:i:s');
        
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':ISBN', $this->ISBN);
        $stmt->bindParam(':publishYear', $this->publishYear);
        $stmt->bindParam(':page', $this->page);
        $stmt->bindParam(':status', $this->status);
        
        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                 SET title = :title, 
                     author = :author, 
                     ISBN = :ISBN, 
                     publishYear = :publishYear, 
                     page = :page, 
                     status = :status 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':ISBN', $this->ISBN);
        $stmt->bindParam(':publishYear', $this->publishYear);
        $stmt->bindParam(':page', $this->page);
        $stmt->bindParam(':status', $this->status);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function filterByStatus($status) {
        $query = "SELECT * FROM " . $this->table . " WHERE status = :status ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByTitle($title) {
        $query = "SELECT * FROM " . $this->table . " WHERE title LIKE :title ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $title . '%';
        $stmt->bindParam(':title', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>