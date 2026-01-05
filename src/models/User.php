<?php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO users (
            email, password_hash, first_name, last_name, 
            birth_date, gender, interests, city, country
        ) VALUES (
            :email, :password_hash, :first_name, :last_name, 
            :birth_date, :gender, :interests, :city, :country
        ) RETURNING id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':email' => $data['email'],
            ':password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':birth_date' => $data['birth_date'],
            ':gender' => $data['gender'],
            ':interests' => $data['interests'] ?? null,
            ':city' => $data['city'] ?? null,
            ':country' => $data['country'] ?? null
        ]);

        return $stmt->fetch()['id'];
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $sql = "SELECT 
            id, email, first_name, last_name, 
            birth_date, gender, interests, city, country,
            created_at, updated_at
        FROM users WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}