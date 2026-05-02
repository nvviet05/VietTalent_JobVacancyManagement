<?php
class AdminController extends Controller {
    private $jobVacancyModel;
    private $jobVacancySkillModel;
    private $lookupModel;

    public function __construct() {
        parent::__construct();
        $this->jobVacancyModel = new JobVacancy();
        $this->jobVacancySkillModel = new JobVacancySkill();
        $this->lookupModel = new Lookup();
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

    public function lookupIndex() {
        $meta = $this->getLookupMetaOr404();

        $this->view('layouts/dashboard', [
            'title' => t($meta['title_key']),
            'subtitle' => t('lookup_management_subtitle'),
            'content' => 'admin/lookups/index',
            'role' => 'admin',
            'activeNav' => 'lookups',
            'activeLookupType' => $meta['key'],
            'styles' => ['css/jobs.css'],
            'lookupMeta' => $meta,
            'records' => $this->lookupModel->getLookupRecords($meta['key']),
        ]);
    }

    public function lookupCreate() {
        $meta = $this->getLookupMetaOr404();
        $oldInput = $_SESSION['old_input'] ?? [];

        $this->view('layouts/dashboard', [
            'title' => t('create_record'),
            'subtitle' => t('lookup_create_subtitle'),
            'content' => 'admin/lookups/create',
            'role' => 'admin',
            'activeNav' => 'lookups',
            'activeLookupType' => $meta['key'],
            'styles' => ['css/jobs.css'],
            'lookupMeta' => $meta,
            'formData' => $this->buildLookupFormData($meta, $oldInput),
        ]);

        clearOldInput();
    }

    public function lookupStore() {
        $meta = $this->getLookupMetaOr404();
        if (!$this->isPostRequest()) {
            $this->redirect('admin_lookup_create', ['type' => $meta['key']]);
        }

        $payload = $this->sanitizeLookupPayload($meta, $_POST);
        $errors = $this->validateLookupPayload($meta, $payload);

        if (!empty($errors)) {
            setOldInput($_POST);
            setValidationErrors($errors);
            $this->redirect('admin_lookup_create', ['type' => $meta['key']]);
        }

        $this->lookupModel->createLookupRecord($meta['key'], $payload);
        clearOldInput();
        $this->setFlash('success', t('record_created_successfully'));
        $this->redirect('admin_lookup', ['type' => $meta['key']]);
    }

    public function lookupEdit() {
        $meta = $this->getLookupMetaOr404();
        $recordId = $this->getLookupRecordIdOr404();
        $record = $this->lookupModel->findLookupRecord($meta['key'], $recordId);

        if (!$record) {
            $this->render404();
        }

        $oldInput = $_SESSION['old_input'] ?? [];
        $source = !empty($oldInput) ? $oldInput : $record;

        $this->view('layouts/dashboard', [
            'title' => t('edit_record'),
            'subtitle' => t('lookup_edit_subtitle'),
            'content' => 'admin/lookups/edit',
            'role' => 'admin',
            'activeNav' => 'lookups',
            'activeLookupType' => $meta['key'],
            'styles' => ['css/jobs.css'],
            'lookupMeta' => $meta,
            'recordId' => $recordId,
            'formData' => $this->buildLookupFormData($meta, $source),
        ]);

        clearOldInput();
    }

    public function lookupUpdate() {
        $meta = $this->getLookupMetaOr404();
        $recordId = $this->getLookupRecordIdOr404();
        $record = $this->lookupModel->findLookupRecord($meta['key'], $recordId);

        if (!$record) {
            $this->setFlash('error', t('invalid_record'));
            $this->redirect('admin_lookup', ['type' => $meta['key']]);
        }

        if (!$this->isPostRequest()) {
            $this->redirect('admin_lookup_edit', ['type' => $meta['key'], 'id' => $recordId]);
        }

        $payload = $this->sanitizeLookupPayload($meta, $_POST);
        $errors = $this->validateLookupPayload($meta, $payload, $recordId);

        if (!empty($errors)) {
            setOldInput($_POST);
            setValidationErrors($errors);
            $this->redirect('admin_lookup_edit', ['type' => $meta['key'], 'id' => $recordId]);
        }

        $this->lookupModel->updateLookupRecord($meta['key'], $recordId, $payload);
        clearOldInput();
        $this->setFlash('success', t('record_updated_successfully'));
        $this->redirect('admin_lookup', ['type' => $meta['key']]);
    }

    public function lookupToggleStatus() {
        $meta = $this->getLookupMetaOr404();
        $recordId = $this->getLookupRecordIdOr404();
        $record = $this->lookupModel->findLookupRecord($meta['key'], $recordId);

        if (!$record) {
            $this->setFlash('error', t('invalid_record'));
            $this->redirect('admin_lookup', ['type' => $meta['key']]);
        }

        $updated = $this->lookupModel->toggleLookupStatus($meta['key'], $recordId);
        if ($updated === false) {
            $this->setFlash('error', t('invalid_record'));
        } else {
            $this->setFlash('success', t('status_updated_successfully'));
        }

        $this->redirect('admin_lookup', ['type' => $meta['key']]);
    }

    private function getRouteJobIdOr404() {
        $jobId = $_GET['id'] ?? null;
        if (!ctype_digit((string)$jobId) || (int)$jobId < 1) {
            $this->render404();
        }

        return (int)$jobId;
    }

    private function getLookupMetaOr404() {
        $type = $_GET['type'] ?? '';
        $meta = $this->lookupModel->getLookupMeta($type);
        if (!$meta) {
            $this->render404();
        }

        return $meta;
    }

    private function getLookupRecordIdOr404() {
        $recordId = $_GET['id'] ?? null;
        if (!ctype_digit((string)$recordId) || (int)$recordId < 1) {
            $this->render404();
        }

        return (int)$recordId;
    }

    private function isPostRequest() {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    private function buildLookupFormData($meta, $source) {
        $default = ['status' => 'active'];

        if (($meta['special'] ?? null) === 'salary_range') {
            return [
                'label' => trim((string)($source['label'] ?? '')),
                'min_salary' => trim((string)($source['min_salary'] ?? '')),
                'max_salary' => trim((string)($source['max_salary'] ?? '')),
                'currency' => trim((string)($source['currency'] ?? 'USD')),
                'status' => trim((string)($source['status'] ?? $default['status'])),
            ];
        }

        return [
            'name' => trim((string)($source['name'] ?? '')),
            'status' => trim((string)($source['status'] ?? $default['status'])),
        ];
    }

    private function sanitizeLookupPayload($meta, $input) {
        $status = trim((string)($input['status'] ?? 'active'));

        if (($meta['special'] ?? null) === 'salary_range') {
            $maxSalary = trim((string)($input['max_salary'] ?? ''));

            return [
                'label' => trim((string)($input['label'] ?? '')),
                'min_salary' => trim((string)($input['min_salary'] ?? '')),
                'max_salary' => $maxSalary === '' ? null : $maxSalary,
                'currency' => trim((string)($input['currency'] ?? 'USD')),
                'status' => $status,
            ];
        }

        return [
            'name' => trim((string)($input['name'] ?? '')),
            'status' => $status,
        ];
    }

    private function validateLookupPayload($meta, $payload, $excludeId = null) {
        $errors = [];

        if (($meta['special'] ?? null) === 'salary_range') {
            if ($payload['label'] === '') {
                $errors['label'] = t('label_is_required');
            } elseif ($this->lookupModel->isDuplicateLookupName($meta['key'], $payload['label'], $excludeId)) {
                $errors['label'] = t('duplicate_record_not_allowed');
            }

            if ($payload['min_salary'] === '') {
                $errors['min_salary'] = t('minimum_salary_required');
            } elseif (!is_numeric($payload['min_salary'])) {
                $errors['min_salary'] = t('minimum_salary_must_be_numeric');
            }

            if ($payload['max_salary'] !== null && $payload['max_salary'] !== '' && !is_numeric($payload['max_salary'])) {
                $errors['max_salary'] = t('maximum_salary_must_be_numeric');
            }

            if (!isset($errors['min_salary']) && !isset($errors['max_salary']) && $payload['max_salary'] !== null && $payload['max_salary'] !== '') {
                if ((float)$payload['max_salary'] < (float)$payload['min_salary']) {
                    $errors['max_salary'] = t('maximum_salary_must_be_greater_or_equal');
                }
            }

            if (trim((string)$payload['currency']) === '') {
                $errors['currency'] = t('currency_is_required');
            }
        } else {
            if ($payload['name'] === '') {
                $errors['name'] = t('name_is_required');
            } elseif ($this->lookupModel->isDuplicateLookupName($meta['key'], $payload['name'], $excludeId)) {
                $errors['name'] = t('duplicate_record_not_allowed');
            }
        }

        if (!in_array($payload['status'], ['active', 'inactive'], true)) {
            $errors['status'] = t('invalid_status');
        }

        return $errors;
    }

    private function render404() {
        http_response_code(404);
        require APP_ROOT . '/resources/views/errors/404.php';
        exit;
    }
}
