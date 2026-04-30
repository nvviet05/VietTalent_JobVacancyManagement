<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once APP_ROOT . '/app/Core/Database.php';
require_once APP_ROOT . '/app/Core/Auth.php';
require_once APP_ROOT . '/app/Core/Controller.php';
require_once APP_ROOT . '/app/Core/Validator.php';
require_once APP_ROOT . '/app/Core/Router.php';
require_once APP_ROOT . '/app/Helpers/url.php';
require_once APP_ROOT . '/app/Helpers/session.php';
require_once APP_ROOT . '/app/Helpers/lang.php';

$router = new Router();

$router->add('home', 'HomeController', 'index');
$router->add('login', 'AuthController', 'loginForm');
$router->add('login_submit', 'AuthController', 'login');
$router->add('register', 'AuthController', 'registerForm');
$router->add('register_submit', 'AuthController', 'register');
$router->add('logout', 'AuthController', 'logout');
$router->add('set_language', 'LanguageController', 'set');

$router->add('admin_dashboard', 'DashboardController', 'admin', 'admin');
$router->add('employer_dashboard', 'DashboardController', 'employer', 'employer');
$router->add('job_seeker_dashboard', 'DashboardController', 'jobSeeker', 'job_seeker');

$router->dispatch();
