<?php
require_once APP_ROOT . '/app/Models/User.php';

class DashboardController extends Controller {
    public function admin() {
        $jobVacancyModel = new JobVacancy();
        $userModel = new User();

        $this->view('layouts/dashboard', [
            'title' => t('admin_dashboard'),
            'subtitle' => t('admin_dashboard_subtitle'),
            'content' => 'dashboard/admin',
            'role' => 'admin',
            'activeNav' => 'dashboard',
            'styles' => ['css/jobs.css'],
            'stats' => [
                'total' => $jobVacancyModel->countAllJobs(),
                'active' => $jobVacancyModel->countJobsByStatus('active'),
                'inactive' => $jobVacancyModel->countJobsByStatus('inactive'),
                'removed' => $jobVacancyModel->countJobsByStatus('removed'),
                'employers' => $userModel->countByRole('employer'),
                'job_seekers' => $userModel->countByRole('job_seeker'),
            ],
            'recentJobs' => $jobVacancyModel->getRecentForAdmin(6),
            'quickLinks' => [
                [
                    'label' => t('manage_job_postings'),
                    'description' => t('manage_job_postings_intro'),
                    'url' => url('admin_jobs'),
                ],
                [
                    'label' => t('manage_job_categories'),
                    'description' => t('manage_job_categories_intro'),
                    'url' => url('admin_lookup', ['type' => 'job_categories']),
                ],
                [
                    'label' => t('manage_job_titles'),
                    'description' => t('manage_job_titles_intro'),
                    'url' => url('admin_lookup', ['type' => 'job_titles']),
                ],
                [
                    'label' => t('manage_skills'),
                    'description' => t('manage_skills_intro'),
                    'url' => url('admin_lookup', ['type' => 'skills']),
                ],
                [
                    'label' => t('manage_industries'),
                    'description' => t('manage_industries_intro'),
                    'url' => url('admin_lookup', ['type' => 'industries']),
                ],
                [
                    'label' => t('manage_locations'),
                    'description' => t('manage_locations_intro'),
                    'url' => url('admin_locations'),
                ],
                [
                    'label' => t('manage_employment_types'),
                    'description' => t('manage_employment_types_intro'),
                    'url' => url('admin_lookup', ['type' => 'employment_types']),
                ],
                [
                    'label' => t('manage_job_levels'),
                    'description' => t('manage_job_levels_intro'),
                    'url' => url('admin_lookup', ['type' => 'job_levels']),
                ],
                [
                    'label' => t('manage_salary_ranges'),
                    'description' => t('manage_salary_ranges_intro'),
                    'url' => url('admin_lookup', ['type' => 'salary_ranges']),
                ],
            ],
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
