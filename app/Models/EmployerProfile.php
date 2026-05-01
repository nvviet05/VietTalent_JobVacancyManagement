<?php
class EmployerProfile {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        return $this->db->insert('employer_profiles', $data);
    }

    public function findByUserId($userId) {
        return $this->db->fetch(
            'SELECT * FROM employer_profiles WHERE user_id = ?',
            [(int)$userId]
        );
    }
}
