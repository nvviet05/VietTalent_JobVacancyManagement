<?php
class DashboardController extends Controller {
    public function admin() {
        $this->view('layouts/dashboard', [
            'title' => t('admin_dashboard'),
            'subtitle' => t('dashboard_subtitle'),
            'content' => 'dashboard/admin',
            'role' => 'admin',
            'activeNav' => 'dashboard',
        ]);
    }

    public function employer() {
        $jobVacancyModel = new JobVacancy();
        $employerProfileModel = new EmployerProfile();
        $user = Auth::user();
        $employerProfile = $employerProfileModel->findByUserId($user['id']);

        if (!$employerProfile) {
            http_response_code(403);
            require APP_ROOT . '/resources/views/errors/403.php';
            return;
        }

        $this->view('layouts/dashboard', [
            'title' => t('employer_dashboard'),
            'subtitle' => t('employer_dashboard_subtitle'),
            'content' => 'employer/dashboard',
            'role' => 'employer',
            'activeNav' => 'dashboard',
            'styles' => ['css/jobs.css'],
            'companyName' => $employerProfile['company_name'],
            'stats' => [
                'total' => $jobVacancyModel->countByEmployer($employerProfile['id']),
                'active' => $jobVacancyModel->countByEmployerAndStatus($employerProfile['id'], 'active'),
                'inactive' => $jobVacancyModel->countByEmployerAndStatus($employerProfile['id'], 'inactive'),
            ],
            'recentJobs' => $jobVacancyModel->getRecentByEmployer($employerProfile['id'], 5),
        ]);
    }

    public function jobSeeker() {
        $this->view('layouts/dashboard', [
            'title' => t('job_seeker_dashboard'),
            'subtitle' => t('dashboard_subtitle'),
            'content' => 'dashboard/job-seeker',
            'role' => 'job_seeker',
            'activeNav' => 'dashboard',
        ]);
    }
}
