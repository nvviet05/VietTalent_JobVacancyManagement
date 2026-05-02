<?php
class Controller {
    protected $db;

    public function __construct() {
        $this->db = null;
    }

    protected function db() {
        if ($this->db === null) {
            $this->db = Database::getInstance();
        }
        return $this->db;
    }

    protected function view($path, $data = []) {
        extract($data);
        require APP_ROOT . '/resources/views/' . $path . '.php';
    }

    protected function redirect($page, $params = []) {
        $url = url($page, $params);
        header('Location: ' . $url);
        exit;
    }

    protected function setFlash($type, $message) {
        $_SESSION['flash_' . $type] = $message;
    }
}
