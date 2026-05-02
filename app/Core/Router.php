<?php
class Router {
    private $routes = [];

    public function add($page, $controller, $method, $role = null) {
        $this->routes[$page] = [
            'controller' => $controller,
            'method' => $method,
            'role' => $role,
        ];
    }

    public function dispatch() {
        $page = $_GET['page'] ?? 'home';

        if (!isset($this->routes[$page])) {
            http_response_code(404);
            require APP_ROOT . '/resources/views/errors/404.php';
            return;
        }

        $route = $this->routes[$page];
        if ($route['role'] !== null) {
            $roles = is_array($route['role']) ? $route['role'] : [$route['role']];
            if (!Auth::check()) {
                $_SESSION['flash_error'] = 'Please login to access this page.';
                header('Location: ' . url('login'));
                return;
            }
            $user = Auth::user();
            $userRole = $user['role'] ?? null;
            if ($userRole === null || !in_array($userRole, $roles, true)) {
                http_response_code(403);
                require APP_ROOT . '/resources/views/errors/403.php';
                return;
            }
        }

        $controllerName = $route['controller'];
        $controllerFile = APP_ROOT . '/app/Controllers/' . $controllerName . '.php';
        if (!file_exists($controllerFile)) {
            http_response_code(500);
            echo 'Controller not found.';
            return;
        }

        require_once $controllerFile;
        $controller = new $controllerName();
        $controller->{$route['method']}();
    }
}
