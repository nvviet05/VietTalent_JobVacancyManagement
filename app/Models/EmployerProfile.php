<?php
class EmployerProfile {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        return $this->db->insert('employer_profiles', $data);
    }
}
