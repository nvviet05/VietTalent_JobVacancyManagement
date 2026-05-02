<?php
require_once APP_ROOT . '/app/Models/User.php';
require_once APP_ROOT . '/app/Models/EmployerProfile.php';

class AuthController extends Controller {
    public function loginForm() {
        if (Auth::check()) {
            $this->redirectByRole();
        }
        $this->view('auth/login');
        clearOldInput();
    }

    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $validator = new Validator($_POST);
        $validator->required('email', 'Email')->email('email', 'Email')->required('password', 'Password');

        if ($validator->fails()) {
            setOldInput($_POST);
            setValidationErrors($validator->errors());
            $this->redirect('login');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            setOldInput($_POST);
            $this->setFlash('error', 'Invalid email or password.');
            $this->redirect('login');
        }

        if ($user['status'] !== 'active') {
            $this->setFlash('error', 'Your account is inactive.');
            $this->redirect('login');
        }

        Auth::login($user);
        clearOldInput();
        $this->setFlash('success', 'Welcome back, ' . $user['full_name'] . '.');
        $this->redirectByRole();
    }

    public function registerForm() {
        if (Auth::check()) {
            $this->redirectByRole();
        }
        $this->view('auth/register');
        clearOldInput();
    }

    public function register() {
        $role = $_POST['role'] ?? '';

        $validator = new Validator($_POST);
        $validator
            ->required('full_name', 'Full name')
            ->required('email', 'Email')
            ->email('email', 'Email')
            ->unique('email', 'users', 'email', 'Email')
            ->required('password', 'Password')
            ->minLength('password', 6, 'Password')
            ->required('password_confirm', 'Confirm password')
            ->match('password', 'password_confirm', 'Confirm password')
            ->required('role', 'Account type')
            ->inList('role', ['employer', 'job_seeker'], 'Account type');

        if ($role === 'employer') {
            $validator->required('company_name', 'Company name');
        }

        if ($validator->fails()) {
            setOldInput($_POST);
            setValidationErrors($validator->errors());
            $this->redirect('register');
        }

        $userModel = new User();
        $userId = $userModel->create([
            'full_name' => trim($_POST['full_name']),
            'email' => trim($_POST['email']),
            'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => $role,
            'status' => 'active',
        ]);

        if ($role === 'employer') {
            $profileModel = new EmployerProfile();
            $profileModel->create([
                'user_id' => $userId,
                'company_name' => trim($_POST['company_name']),
            ]);
        } else {
            $this->db()->insert('job_seeker_profiles', ['user_id' => $userId]);
        }

        $user = $userModel->findById($userId);
        Auth::login($user);
        clearOldInput();
        $this->setFlash('success', 'Account created successfully.');
        $this->redirectByRole();
    }

    public function logout() {
        Auth::logout();
        $this->setFlash('success', 'You have been logged out.');
        $this->redirect('home');
    }

    private function redirectByRole() {
        $user = Auth::user();
        $role = $user['role'] ?? null;
        if ($role === 'admin') {
            $this->redirect('admin_dashboard');
        }
        if ($role === 'employer') {
            $this->redirect('employer_dashboard');
        }
        $this->redirect('job_seeker_dashboard');
    }
}
