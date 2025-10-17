<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../models/Librarian.php';

class AuthController {
    private $db;
    private $librarian;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->librarian = new Librarian($this->db);
    }

    // Register
    public function register($data) {
        if (!$data || !isset($data['name'], $data['email'], $data['password'])) {
            Response::json(['message' => 'Invalid input data'], 400);
            return;
        }

        $this->librarian->name = $data['name'];
        $this->librarian->email = $data['email'];
        $this->librarian->password = password_hash($data['password'], PASSWORD_BCRYPT);

        if ($this->librarian->create()) {
            Response::json(['message' => 'Register success'], 201);
        } else {
            Response::json(['message' => 'Email already exists'], 400);
        }
    }

    // Login
    public function login($data) {
        if (!$data || !isset($data['email'], $data['password'])) {
            Response::json(['message' => 'Invalid input data'], 400);
            return;
        }

        $librarian = $this->librarian->getByEmail($data['email']);

        if (!$librarian) {
            // Email not found
            Response::json(['message' => 'Invalid email'], 401);
            return;
        }

        if (!password_verify($data['password'], $librarian['password'])) {
            // Password incorrect
            Response::json(['message' => 'Invalid password'], 401);
            return;
        }

        // ✅ If both email and password correct, generate token
        $token = bin2hex(random_bytes(16));
        Response::json([
            'message' => 'Login success',
            'token' => $token,
            'librarian' => [
                'id' => $librarian['id'],
                'name' => $librarian['name'],
                'email' => $librarian['email']
            ]
        ], 200);
    }
}
