<?php
session_start();

// 1. Load các cấu hình hệ thống
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// 2. Load các lớp Core (Nhân) của hệ thống
require_once APP_ROOT . '/app/Core/Database.php';
require_once APP_ROOT . '/app/Core/Auth.php';
require_once APP_ROOT . '/app/Core/Controller.php';
require_once APP_ROOT . '/app/Core/Validator.php';
require_once APP_ROOT . '/app/Core/Router.php';

// 3. Load các Helper (Hàm hỗ trợ)
require_once APP_ROOT . '/app/Helpers/url.php';
require_once APP_ROOT . '/app/Helpers/session.php';
require_once APP_ROOT . '/app/Helpers/lang.php';

// Hàm helper 'e' để bảo mật dữ liệu hiển thị
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
    }
}

// 4. Load các Models 
require_once APP_ROOT . '/app/Models/Lookup.php';
require_once APP_ROOT . '/app/Models/JobVacancy.php';
require_once APP_ROOT . '/app/Models/JobVacancySkill.php';
require_once APP_ROOT . '/app/Models/EmployerProfile.php';

// 5. Load các Controllers
require_once APP_ROOT . '/app/Controllers/HomeController.php';
require_once APP_ROOT . '/app/Controllers/JobController.php'; 
require_once APP_ROOT . '/app/Controllers/AuthController.php';
require_once APP_ROOT . '/app/Controllers/DashboardController.php';
require_once APP_ROOT . '/app/Controllers/LanguageController.php';
require_once APP_ROOT . '/app/Controllers/EmployerController.php';
require_once APP_ROOT . '/app/Controllers/AdminController.php';


// ====================================================================
// TÍCH HỢP LOGIC ROUTE THÔNG MINH TỪ PHRASE 3 VÀO HỆ THỐNG CỦA TEAM
// Giúp Router bỏ qua các biến query (?keyword=...) và nhận diện chuẩn xác
// ====================================================================
if (isset($_SERVER['REQUEST_URI'])) {
    $parsedUrl = parse_url($_SERVER['REQUEST_URI']);
    $path = $parsedUrl['path'] ?? '';
    
    // Tự động nhận diện và cắt bỏ thư mục gốc giống phrase3
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = dirname($scriptName);
    $route = str_replace([$scriptName, $basePath], '', $path);
    $route = ltrim($route, '/');

    // Nếu url trống, mặc định gọi 'home'
    if (empty($route)) {
        $route = 'home';
    }

    // Nếu Form Search gửi ?url=jobs, ta ưu tiên sử dụng nó
    if (!empty($_GET['url'])) {
        $route = $_GET['url'];
    }

    // Ép Router của team phải đọc đúng Route này!
    $_GET['url'] = $route;
}
// ====================================================================


// 6. Khởi tạo Router và đăng ký các đường dẫn
$router = new Router();

// Các Route tìm kiếm công việc (Job Portal)
$router->add('home', 'HomeController', 'index');
$router->add('jobs', 'JobController', 'index');        
$router->add('jobs/detail', 'JobController', 'detail'); 

// Các Route hiện có của team
$router->add('login', 'AuthController', 'loginForm');
$router->add('login_submit', 'AuthController', 'login');
$router->add('register', 'AuthController', 'registerForm');
$router->add('register_submit', 'AuthController', 'register');
$router->add('logout', 'AuthController', 'logout');
$router->add('set_language', 'LanguageController', 'set');

// Các Route cho Dashboard và Employer
$router->add('admin_dashboard', 'DashboardController', 'admin', 'admin');
$router->add('admin_jobs', 'AdminController', 'index', 'admin');
$router->add('admin_job_view', 'AdminController', 'show', 'admin');
$router->add('admin_job_set_status', 'AdminController', 'setStatus', 'admin');
$router->add('admin_lookup', 'AdminController', 'lookupIndex', 'admin');
$router->add('admin_lookup_create', 'AdminController', 'lookupCreate', 'admin');
$router->add('admin_lookup_store', 'AdminController', 'lookupStore', 'admin');
$router->add('admin_lookup_edit', 'AdminController', 'lookupEdit', 'admin');
$router->add('admin_lookup_update', 'AdminController', 'lookupUpdate', 'admin');
$router->add('admin_lookup_toggle_status', 'AdminController', 'lookupToggleStatus', 'admin');
$router->add('employer_dashboard', 'DashboardController', 'employer', 'employer');
$router->add('job_seeker_dashboard', 'DashboardController', 'jobSeeker', 'job_seeker');

$router->add('employer_jobs', 'EmployerController', 'index', 'employer');
$router->add('employer_job_create', 'EmployerController', 'create', 'employer');
$router->add('employer_job_store', 'EmployerController', 'store', 'employer');
$router->add('employer_job_view', 'EmployerController', 'show', 'employer');
$router->add('employer_job_edit', 'EmployerController', 'edit', 'employer');
$router->add('employer_job_update', 'EmployerController', 'update', 'employer');
$router->add('employer_job_delete', 'EmployerController', 'delete', 'employer');
$router->add('employer_job_toggle_status', 'EmployerController', 'toggleStatus', 'employer');

// 7. Thực thi Router
$router->dispatch();
