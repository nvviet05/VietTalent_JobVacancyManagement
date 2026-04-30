<?php
class DashboardController extends Controller {
    public function admin() {
        $this->view('layouts/dashboard', [
            'title' => t('admin_dashboard'),
            'content' => 'dashboard/admin',
            'role' => 'admin',
        ]);
    }

    public function employer() {
        $this->view('layouts/dashboard', [
            'title' => t('employer_dashboard'),
            'content' => 'dashboard/employer',
            'role' => 'employer',
        ]);
    }

    public function jobSeeker() {
        $this->view('layouts/dashboard', [
            'title' => t('job_seeker_dashboard'),
            'content' => 'dashboard/job-seeker',
            'role' => 'job_seeker',
        ]);
    }
}
