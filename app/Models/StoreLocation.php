<?php
class StoreLocation {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getActive() {
        return $this->db->fetchAll(
            "SELECT sl.*, c.name AS city_name, co.name AS country_name
               FROM store_locations sl
               LEFT JOIN cities c ON c.id = sl.city_id
               LEFT JOIN countries co ON co.id = c.country_id
              WHERE sl.status = 'active'
              ORDER BY sl.name ASC"
        );
    }
}
