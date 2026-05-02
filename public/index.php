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
$router->add('admin_jobs', 'AdminController', 'index', 'admin');
$router->add('admin_job_view', 'AdminController', 'show', 'admin');
$router->add('admin_job_set_status', 'AdminController', 'setStatus', 'admin');
$router->add('admin_lookup', 'AdminController', 'lookupIndex', 'admin');
$router->add('admin_lookup_create', 'AdminController', 'lookupCreate', 'admin');
$router->add('admin_lookup_store', 'AdminController', 'lookupStore', 'admin');
$router->add('admin_lookup_edit', 'AdminController', 'lookupEdit', 'admin');
$router->add('admin_lookup_update', 'AdminController', 'lookupUpdate', 'admin');
$router->add('admin_lookup_toggle_status', 'AdminController', 'lookupToggleStatus', 'admin');
$router->add('admin_locations', 'AdminController', 'locationsIndex', 'admin');
$router->add('admin_countries', 'AdminController', 'countriesIndex', 'admin');
$router->add('admin_country_create', 'AdminController', 'countryCreate', 'admin');
$router->add('admin_country_store', 'AdminController', 'countryStore', 'admin');
$router->add('admin_country_edit', 'AdminController', 'countryEdit', 'admin');
$router->add('admin_country_update', 'AdminController', 'countryUpdate', 'admin');
$router->add('admin_country_toggle_status', 'AdminController', 'countryToggleStatus', 'admin');
$router->add('admin_cities', 'AdminController', 'citiesIndex', 'admin');
$router->add('admin_city_create', 'AdminController', 'cityCreate', 'admin');
$router->add('admin_city_store', 'AdminController', 'cityStore', 'admin');
$router->add('admin_city_edit', 'AdminController', 'cityEdit', 'admin');
$router->add('admin_city_update', 'AdminController', 'cityUpdate', 'admin');
$router->add('admin_city_toggle_status', 'AdminController', 'cityToggleStatus', 'admin');
$router->add('admin_districts', 'AdminController', 'districtsIndex', 'admin');
$router->add('admin_district_create', 'AdminController', 'districtCreate', 'admin');
$router->add('admin_district_store', 'AdminController', 'districtStore', 'admin');
$router->add('admin_district_edit', 'AdminController', 'districtEdit', 'admin');
$router->add('admin_district_update', 'AdminController', 'districtUpdate', 'admin');
$router->add('admin_district_toggle_status', 'AdminController', 'districtToggleStatus', 'admin');
$router->add('employer_dashboard', 'DashboardController', 'employer', 'employer');
$router->add('job_seeker_dashboard', 'DashboardController', 'jobSeeker', 'job_seeker');

require_once APP_ROOT . '/app/Models/Lookup.php';
require_once APP_ROOT . '/app/Models/JobVacancy.php';
require_once APP_ROOT . '/app/Models/JobVacancySkill.php';
require_once APP_ROOT . '/app/Models/EmployerProfile.php';

$router->add('employer_jobs', 'EmployerController', 'index', 'employer');
$router->add('employer_job_create', 'EmployerController', 'create', 'employer');
$router->add('employer_job_store', 'EmployerController', 'store', 'employer');
$router->add('employer_job_view', 'EmployerController', 'show', 'employer');
$router->add('employer_job_edit', 'EmployerController', 'edit', 'employer');
$router->add('employer_job_update', 'EmployerController', 'update', 'employer');
$router->add('employer_job_delete', 'EmployerController', 'delete', 'employer');
$router->add('employer_job_toggle_status', 'EmployerController', 'toggleStatus', 'employer');

$router->dispatch();
