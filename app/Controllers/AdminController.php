<?php
class AdminController extends Controller {
    private $jobVacancyModel;
    private $jobVacancySkillModel;

    public function __construct() {
        parent::__construct();
        $this->jobVacancyModel = new JobVacancy();
        $this->jobVacancySkillModel = new JobVacancySkill();
    }

    public function index() {
        $this->view('layouts/dashboard', [
            'title' => t('all_job_postings'),
            'subtitle' => t('admin_jobs_subtitle'),
            'content' => 'admin/jobs/index',
            'role' => 'admin',
            'activeNav' => 'jobs',
            'styles' => ['css/jobs.css'],
            'jobs' => $this->jobVacancyModel->getAllForAdmin(),
        ]);
    }

    public function show() {
        $jobId = $this->getRouteJobIdOr404();
        $job = $this->jobVacancyModel->getDetailForAdmin($jobId);

        if (!$job) {
            $this->render404();
        }

        $this->view('layouts/dashboard', [
            'title' => t('job_detail'),
            'subtitle' => t('admin_job_detail_subtitle'),
            'content' => 'admin/jobs/view',
            'role' => 'admin',
            'activeNav' => 'jobs',
            'styles' => ['css/jobs.css'],
            'job' => $job,
            'skills' => $this->jobVacancySkillModel->getByJobVacancy($jobId),
        ]);
    }

    public function setStatus() {
        $jobId = $this->getRouteJobIdOr404();
        $job = $this->jobVacancyModel->findById($jobId);

        if (!$job) {
            $this->setFlash('error', t('invalid_job_posting'));
            $this->redirect('admin_jobs');
        }

        $status = $_GET['status'] ?? '';
        if (!in_array($status, ['active', 'inactive', 'removed'], true)) {
            $this->setFlash('error', t('invalid_status'));
            $this->redirect('admin_jobs');
        }

        $updated = $this->jobVacancyModel->setStatusByAdmin($jobId, $status);
        if ($updated) {
            $this->setFlash('success', t('job_status_updated_successfully'));
        } else {
            $this->setFlash('error', t('job_status_update_failed'));
        }

        $returnPage = $_GET['return_page'] ?? 'admin_jobs';
        if ($returnPage === 'admin_job_view') {
            $this->redirect('admin_job_view', ['id' => $jobId]);
        }

        $this->redirect('admin_jobs');
    }

    private function getRouteJobIdOr404() {
        $jobId = $_GET['id'] ?? null;
        if (!ctype_digit((string)$jobId) || (int)$jobId < 1) {
            $this->render404();
        }

        return (int)$jobId;
    }

    private function render404() {
        http_response_code(404);
        require APP_ROOT . '/resources/views/errors/404.php';
        exit;
    }
}
