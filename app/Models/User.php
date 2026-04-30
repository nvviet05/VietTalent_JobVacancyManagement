<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail($email) {
        return $this->db->fetch('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public function findById($id) {
        return $this->db->fetch('SELECT * FROM users WHERE id = ?', [$id]);
    }

    public function create($data) {
        return $this->db->insert('users', $data);
    }
}
