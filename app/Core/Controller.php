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

    public function view($view, $data = []) {
        
        extract($data); 
        
        $viewPath = APP_ROOT . '/resources/views/' . $view . '.php';
        
        // Load the view if it exists
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Friendly error message if a file is missing
            http_response_code(404);
            die("<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>"
              . "<h2>View Error</h2>"
              . "<p>The view file <strong>" . e($view) . ".php</strong> was not found.</p>"
              . "</div>");
        }
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
