<?php
require_once __DIR__.'/../models/Book.php';
require_once __DIR__.'/../helpers/Response.php';

class BookController {
    private $book;
    
    public function __construct($db) { 
        $this->book = new Book($db); 
    }

    // POST - Create new book
    public function create($data){
        $this->book->title = $data['title'];
        $this->book->author = $data['author'];
        $this->book->ISBN = $data['ISBN'] ?? null;
        $this->book->publishYear = $data['publishYear'] ?? null;
        $this->book->page = $data['page'] ?? null;
        $this->book->status = $data['status'] ?? 'on_shelf';
        
        if($this->book->create()) {
            Response::json(['message' => 'Book added successfully'], 201);
        } else {
            Response::json(['message' => 'Failed to add book'], 400);
        }
    }

    // GET - Get all books
    public function getAll(){ 
        $books = $this->book->getAll();
        Response::json([
            'success' => true,
            'data' => $books,
            'count' => count($books)
        ]); 
    }

    // GET - Get book by ID
    public function getById($id){ 
        $book = $this->book->getById($id);
        if($book) {
            Response::json([
                'success' => true,
                'data' => $book
            ]);
        } else {
            Response::json(['message' => 'Book not found'], 404);
        }
    }

    // PUT - Update book
    public function update($data){
        $this->book->id = $data['id'];
        $this->book->title = $data['title'];
        $this->book->author = $data['author'];
        $this->book->ISBN = $data['ISBN'] ?? null;
        $this->book->publishYear = $data['publishYear'] ?? null;
        $this->book->page = $data['page'] ?? null;
        $this->book->status = $data['status'];
        
        if($this->book->update()) {
            Response::json(['message' => 'Book updated successfully'], 200);
        } else {
            Response::json(['message' => 'Failed to update book'], 400);
        }
    }

    // DELETE - Delete book
    public function delete($id){
        // Check if book exists first
        $existingBook = $this->book->getById($id);
        if(!$existingBook) {
            Response::json(['message' => 'Book not found'], 404);
            return;
        }
        
        if($this->book->delete($id)) {
            Response::json(['message' => 'Book deleted successfully'], 200);
        } else {
            Response::json(['message' => 'Failed to delete book'], 400);
        }
    }

    // GET - Filter books by status
    public function filterByStatus($status){ 
        $books = $this->book->filterByStatus($status);
        Response::json([
            'success' => true,
            'data' => $books,
            'count' => count($books)
        ]); 
    }

    // GET - Search books by title
    public function searchByTitle($title){ 
        $books = $this->book->searchByTitle($title);
        Response::json([
            'success' => true,
            'data' => $books,
            'count' => count($books)
        ]);
    }
}
?>