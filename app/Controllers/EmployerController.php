<?php
class EmployerController extends Controller {
    private $jobVacancyModel;
    private $jobVacancySkillModel;
    private $lookupModel;
    private $employerProfileModel;

    public function __construct() {
        parent::__construct();
        $this->jobVacancyModel = new JobVacancy();
        $this->jobVacancySkillModel = new JobVacancySkill();
        $this->lookupModel = new Lookup();
        $this->employerProfileModel = new EmployerProfile();
    }

    public function index() {
        $employerProfile = $this->getEmployerProfileOrFail();

        $this->view('layouts/dashboard', [
            'title' => t('my_job_postings'),
            'subtitle' => t('employer_jobs_subtitle'),
            'content' => 'employer/jobs/index',
            'role' => 'employer',
            'activeNav' => 'jobs',
            'styles' => ['css/jobs.css'],
            'jobs' => $this->jobVacancyModel->getAllByEmployer($employerProfile['id']),
        ]);
    }

    public function create() {
        $this->getEmployerProfileOrFail();

        $oldInput = $_SESSION['old_input'] ?? [];
        $formData = $this->buildFormData($oldInput);
        $skillRows = $this->buildSkillRows($oldInput['skills'] ?? []);

        $this->view('layouts/dashboard', [
            'title' => t('create_new_job'),
            'subtitle' => t('create_job_subtitle'),
            'content' => 'employer/jobs/create',
            'role' => 'employer',
            'activeNav' => 'create_job',
            'styles' => ['css/jobs.css'],
            'scripts' => ['js/job-form.js'],
            'lookups' => $this->getLookupData(),
            'formData' => $formData,
            'skillRows' => $skillRows,
        ]);

        clearOldInput();
    }

    public function store() {
        $employerProfile = $this->getEmployerProfileOrFail();

        if (!$this->isPostRequest()) {
            $this->redirect('employer_job_create');
        }

        $payload = $this->sanitizeJobPayload($_POST);
        $errors = $this->validateJobPayload($payload);

        if (!empty($errors)) {
            setOldInput($_POST);
            setValidationErrors($errors);
            $this->redirect('employer_job_create');
        }

        $db = $this->db();

        try {
            $db->beginTransaction();

            $jobId = $this->jobVacancyModel->create([
                'employer_id' => (int)$employerProfile['id'],
                'job_title_id' => $payload['job_title_id'],
                'job_category_id' => $payload['job_category_id'],
                'employment_type_id' => $payload['employment_type_id'],
                'industry_id' => $payload['industry_id'],
                'job_level_id' => $payload['job_level_id'],
                'number_of_openings' => $payload['number_of_openings'],
                'country_id' => $payload['country_id'],
                'city_id' => $payload['city_id'],
                'district_id' => $payload['district_id'],
                'work_arrangement_id' => $payload['work_arrangement_id'],
                'salary_range_id' => $payload['salary_range_id'],
                'salary_type_id' => $payload['salary_type_id'],
                'benefits' => $payload['benefits'],
                'responsibilities' => $payload['responsibilities'],
                'required_qualifications' => $payload['required_qualifications'],
                'preferred_skills' => $payload['preferred_skills'],
                'additional_notes' => $payload['additional_notes'],
                'degree_level_id' => $payload['degree_level_id'],
                'experience_level_id' => $payload['experience_level_id'],
                'status' => 'inactive',
            ]);

            $this->jobVacancySkillModel->replaceSkills($jobId, $payload['skills']);
            $db->commit();
        } catch (Throwable $exception) {
            $db->rollBack();
            setOldInput($_POST);
            $this->setFlash('error', t('job_save_failed'));
            $this->redirect('employer_job_create');
        }

        clearOldInput();
        $this->setFlash('success', t('job_created_successfully'));
        $this->redirect('employer_job_view', ['id' => $jobId]);
    }

    public function show() {
        $employerProfile = $this->getEmployerProfileOrFail();
        $jobId = $this->getRouteJobId();
        $job = $this->jobVacancyModel->getDetailByEmployer($jobId, $employerProfile['id']);

        if (!$job) {
            $this->denyJobAccess();
        }

        $this->view('layouts/dashboard', [
            'title' => t('view_job'),
            'subtitle' => t('view_job_subtitle'),
            'content' => 'employer/jobs/view',
            'role' => 'employer',
            'activeNav' => 'jobs',
            'styles' => ['css/jobs.css'],
            'job' => $job,
            'skills' => $this->jobVacancySkillModel->getByJobVacancy($jobId),
        ]);
    }

    public function edit() {
        $employerProfile = $this->getEmployerProfileOrFail();
        $jobId = $this->getRouteJobId();
        $job = $this->jobVacancyModel->findByIdAndEmployer($jobId, $employerProfile['id']);

        if (!$job) {
            $this->denyJobAccess();
        }

        $oldInput = $_SESSION['old_input'] ?? [];
        $formData = !empty($oldInput) ? $this->buildFormData($oldInput) : $this->buildFormData($job);
        $skillRows = !empty($oldInput['skills'] ?? [])
            ? $this->buildSkillRows($oldInput['skills'])
            : $this->buildSkillRows($this->jobVacancySkillModel->getByJobVacancy($jobId));

        $this->view('layouts/dashboard', [
            'title' => t('edit_job'),
            'subtitle' => t('edit_job_subtitle'),
            'content' => 'employer/jobs/edit',
            'role' => 'employer',
            'activeNav' => 'jobs',
            'styles' => ['css/jobs.css'],
            'scripts' => ['js/job-form.js'],
            'lookups' => $this->getLookupData(),
            'formData' => $formData,
            'skillRows' => $skillRows,
            'jobId' => $jobId,
        ]);

        clearOldInput();
    }

    public function update() {
        $employerProfile = $this->getEmployerProfileOrFail();
        $jobId = $this->getRouteJobId();
        $job = $this->jobVacancyModel->findByIdAndEmployer($jobId, $employerProfile['id']);

        if (!$job) {
            $this->denyJobAccess();
        }

        if (!$this->isPostRequest()) {
            $this->redirect('employer_job_edit', ['id' => $jobId]);
        }

        $payload = $this->sanitizeJobPayload($_POST);
        $errors = $this->validateJobPayload($payload);

        if (!empty($errors)) {
            setOldInput($_POST);
            setValidationErrors($errors);
            $this->redirect('employer_job_edit', ['id' => $jobId]);
        }

        $db = $this->db();

        try {
            $db->beginTransaction();

            $this->jobVacancyModel->update($jobId, $employerProfile['id'], [
                'job_title_id' => $payload['job_title_id'],
                'job_category_id' => $payload['job_category_id'],
                'employment_type_id' => $payload['employment_type_id'],
                'industry_id' => $payload['industry_id'],
                'job_level_id' => $payload['job_level_id'],
                'number_of_openings' => $payload['number_of_openings'],
                'country_id' => $payload['country_id'],
                'city_id' => $payload['city_id'],
                'district_id' => $payload['district_id'],
                'work_arrangement_id' => $payload['work_arrangement_id'],
                'salary_range_id' => $payload['salary_range_id'],
                'salary_type_id' => $payload['salary_type_id'],
                'benefits' => $payload['benefits'],
                'responsibilities' => $payload['responsibilities'],
                'required_qualifications' => $payload['required_qualifications'],
                'preferred_skills' => $payload['preferred_skills'],
                'additional_notes' => $payload['additional_notes'],
                'degree_level_id' => $payload['degree_level_id'],
                'experience_level_id' => $payload['experience_level_id'],
            ]);

            $this->jobVacancySkillModel->replaceSkills($jobId, $payload['skills']);
            $db->commit();
        } catch (Throwable $exception) {
            $db->rollBack();
            setOldInput($_POST);
            $this->setFlash('error', t('job_save_failed'));
            $this->redirect('employer_job_edit', ['id' => $jobId]);
        }

        clearOldInput();
        $this->setFlash('success', t('job_updated_successfully'));
        $this->redirect('employer_job_view', ['id' => $jobId]);
    }

    public function delete() {
        $employerProfile = $this->getEmployerProfileOrFail();
        $jobId = $this->getRouteJobId();
        $job = $this->jobVacancyModel->findByIdAndEmployer($jobId, $employerProfile['id']);

        if (!$job) {
            $this->denyJobAccess();
        }

        if (!$this->isPostRequest()) {
            $this->redirect('employer_job_view', ['id' => $jobId]);
        }

        $db = $this->db();

        try {
            $db->beginTransaction();
            $this->jobVacancySkillModel->deleteByJobVacancy($jobId);
            $this->jobVacancyModel->delete($jobId, $employerProfile['id']);
            $db->commit();
        } catch (Throwable $exception) {
            $db->rollBack();
            $this->setFlash('error', t('job_delete_failed'));
            $this->redirect('employer_job_view', ['id' => $jobId]);
        }

        $this->setFlash('success', t('job_deleted_successfully'));
        $this->redirect('employer_jobs');
    }

    public function toggleStatus() {
        $employerProfile = $this->getEmployerProfileOrFail();
        $jobId = $this->getRouteJobId();
        $job = $this->jobVacancyModel->findByIdAndEmployer($jobId, $employerProfile['id']);

        if (!$job) {
            $this->denyJobAccess();
        }

        if (!$this->isPostRequest()) {
            $this->redirect('employer_job_view', ['id' => $jobId]);
        }

        $nextStatus = $this->jobVacancyModel->toggleStatus($jobId, $employerProfile['id']);
        if ($nextStatus === false) {
            $this->setFlash('error', t('job_status_update_failed'));
        } else {
            $this->setFlash('success', t('job_status_updated_successfully'));
        }

        $returnPage = $_POST['return_page'] ?? 'employer_jobs';
        if ($returnPage === 'employer_job_view') {
            $this->redirect('employer_job_view', ['id' => $jobId]);
        }

        $this->redirect('employer_jobs');
    }

    private function getEmployerProfileOrFail() {
        $user = Auth::user();
        $profile = $this->employerProfileModel->findByUserId($user['id']);

        if (!$profile) {
            http_response_code(403);
            require APP_ROOT . '/resources/views/errors/403.php';
            exit;
        }

        return $profile;
    }

    private function getRouteJobId() {
        return (int)($_GET['id'] ?? 0);
    }

    private function denyJobAccess() {
        http_response_code(403);
        $errorTitle = t('error_403_title');
        $errorMessage = t('job_permission_denied');
        require APP_ROOT . '/resources/views/errors/403.php';
        exit;
    }

    private function isPostRequest() {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    private function getLookupData() {
        return [
            'job_titles' => $this->lookupModel->getActiveJobTitles(),
            'job_categories' => $this->lookupModel->getActiveJobCategories(),
            'employment_types' => $this->lookupModel->getActiveEmploymentTypes(),
            'industries' => $this->lookupModel->getActiveIndustries(),
            'job_levels' => $this->lookupModel->getActiveJobLevels(),
            'countries' => $this->lookupModel->getActiveCountries(),
            'cities' => $this->lookupModel->getActiveCities(),
            'districts' => $this->lookupModel->getActiveDistricts(),
            'work_arrangements' => $this->lookupModel->getActiveWorkArrangements(),
            'salary_ranges' => $this->lookupModel->getActiveSalaryRanges(),
            'salary_types' => $this->lookupModel->getActiveSalaryTypes(),
            'skills' => $this->lookupModel->getActiveSkills(),
            'proficiency_levels' => $this->lookupModel->getActiveProficiencyLevels(),
            'degree_levels' => $this->lookupModel->getActiveDegreeLevels(),
            'experience_levels' => $this->lookupModel->getActiveExperienceLevels(),
        ];
    }

    private function buildFormData($source) {
        return [
            'job_title_id' => $this->normalizeId($source['job_title_id'] ?? null),
            'job_category_id' => $this->normalizeId($source['job_category_id'] ?? null),
            'employment_type_id' => $this->normalizeId($source['employment_type_id'] ?? null),
            'industry_id' => $this->normalizeId($source['industry_id'] ?? null),
            'job_level_id' => $this->normalizeId($source['job_level_id'] ?? null),
            'number_of_openings' => trim((string)($source['number_of_openings'] ?? '1')),
            'country_id' => $this->normalizeId($source['country_id'] ?? null),
            'city_id' => $this->normalizeId($source['city_id'] ?? null),
            'district_id' => $this->normalizeId($source['district_id'] ?? null),
            'work_arrangement_id' => $this->normalizeId($source['work_arrangement_id'] ?? null),
            'salary_range_id' => $this->normalizeId($source['salary_range_id'] ?? null),
            'salary_type_id' => $this->normalizeId($source['salary_type_id'] ?? null),
            'benefits' => trim((string)($source['benefits'] ?? '')),
            'responsibilities' => trim((string)($source['responsibilities'] ?? '')),
            'required_qualifications' => trim((string)($source['required_qualifications'] ?? '')),
            'preferred_skills' => trim((string)($source['preferred_skills'] ?? '')),
            'additional_notes' => trim((string)($source['additional_notes'] ?? '')),
            'degree_level_id' => $this->normalizeId($source['degree_level_id'] ?? null),
            'experience_level_id' => $this->normalizeId($source['experience_level_id'] ?? null),
        ];
    }

    private function buildSkillRows($skillSource) {
        if (empty($skillSource)) {
            return [['skill_id' => '', 'proficiency_level_id' => '']];
        }

        $rows = [];
        foreach ($skillSource as $skill) {
            $rows[] = [
                'skill_id' => (string)($skill['skill_id'] ?? ''),
                'proficiency_level_id' => (string)($skill['proficiency_level_id'] ?? ''),
            ];
        }

        return $rows;
    }

    private function sanitizeJobPayload($input) {
        $skills = [];
        foreach (($input['skills'] ?? []) as $skill) {
            $skills[] = [
                'skill_id' => $this->normalizeId($skill['skill_id'] ?? null),
                'proficiency_level_id' => $this->normalizeId($skill['proficiency_level_id'] ?? null),
            ];
        }

        return [
            'job_title_id' => $this->normalizeId($input['job_title_id'] ?? null),
            'job_category_id' => $this->normalizeId($input['job_category_id'] ?? null),
            'employment_type_id' => $this->normalizeId($input['employment_type_id'] ?? null),
            'industry_id' => $this->normalizeId($input['industry_id'] ?? null),
            'job_level_id' => $this->normalizeId($input['job_level_id'] ?? null),
            'number_of_openings' => $this->normalizeOpenings($input['number_of_openings'] ?? null),
            'country_id' => $this->normalizeId($input['country_id'] ?? null),
            'city_id' => $this->normalizeId($input['city_id'] ?? null),
            'district_id' => $this->normalizeId($input['district_id'] ?? null),
            'work_arrangement_id' => $this->normalizeId($input['work_arrangement_id'] ?? null),
            'salary_range_id' => $this->normalizeId($input['salary_range_id'] ?? null),
            'salary_type_id' => $this->normalizeId($input['salary_type_id'] ?? null),
            'benefits' => trim((string)($input['benefits'] ?? '')),
            'responsibilities' => trim((string)($input['responsibilities'] ?? '')),
            'required_qualifications' => trim((string)($input['required_qualifications'] ?? '')),
            'preferred_skills' => trim((string)($input['preferred_skills'] ?? '')),
            'additional_notes' => trim((string)($input['additional_notes'] ?? '')),
            'degree_level_id' => $this->normalizeId($input['degree_level_id'] ?? null),
            'experience_level_id' => $this->normalizeId($input['experience_level_id'] ?? null),
            'skills' => $skills,
        ];
    }

    private function validateJobPayload($payload) {
        $errors = [];

        $requiredSelects = [
            'job_title_id' => 'job_title',
            'job_category_id' => 'job_category',
            'employment_type_id' => 'employment_type',
            'industry_id' => 'industry',
            'job_level_id' => 'job_level',
            'country_id' => 'country',
            'city_id' => 'city_province',
            'work_arrangement_id' => 'work_arrangement',
            'salary_range_id' => 'salary_range',
            'salary_type_id' => 'salary_type',
            'degree_level_id' => 'minimum_degree_level',
            'experience_level_id' => 'minimum_years_of_experience',
        ];

        foreach ($requiredSelects as $field => $labelKey) {
            if (empty($payload[$field])) {
                $errors[$field] = t('validation_required_prefix') . t($labelKey) . t('validation_required_suffix');
            }
        }

        $requiredTextFields = [
            'responsibilities' => 'responsibilities',
            'required_qualifications' => 'required_qualifications',
        ];

        foreach ($requiredTextFields as $field => $labelKey) {
            if ($payload[$field] === '') {
                $errors[$field] = t('validation_required_prefix') . t($labelKey) . t('validation_required_suffix');
            }
        }

        if ($payload['number_of_openings'] === '') {
            $errors['number_of_openings'] = t('validation_required_prefix') . t('number_of_openings') . t('validation_required_suffix');
        } elseif (!is_int($payload['number_of_openings']) || $payload['number_of_openings'] < 1) {
            $errors['number_of_openings'] = t('validation_openings_min');
        }

        $activeMap = [
            'job_title_id' => 'job_titles',
            'job_category_id' => 'job_categories',
            'employment_type_id' => 'employment_types',
            'industry_id' => 'industries',
            'job_level_id' => 'job_levels',
            'country_id' => 'countries',
            'work_arrangement_id' => 'work_arrangements',
            'salary_range_id' => 'salary_ranges',
            'salary_type_id' => 'salary_types',
            'degree_level_id' => 'degree_levels',
            'experience_level_id' => 'experience_levels',
        ];

        foreach ($activeMap as $field => $table) {
            if (!empty($payload[$field]) && !$this->lookupModel->existsActive($table, $payload[$field])) {
                $errors[$field] = t('validation_invalid_selection');
            }
        }

        if (!empty($payload['city_id'])) {
            if (!$this->lookupModel->existsActive('cities', $payload['city_id'])) {
                $errors['city_id'] = t('validation_invalid_city');
            } elseif (!empty($payload['country_id']) && !$this->lookupModel->cityBelongsToCountry($payload['city_id'], $payload['country_id'])) {
                $errors['city_id'] = t('validation_city_country_mismatch');
            }
        }

        if (!empty($payload['district_id'])) {
            if (!$this->lookupModel->existsActive('districts', $payload['district_id'])) {
                $errors['district_id'] = t('validation_invalid_district');
            } elseif (!empty($payload['city_id']) && !$this->lookupModel->districtBelongsToCity($payload['district_id'], $payload['city_id'])) {
                $errors['district_id'] = t('validation_district_city_mismatch');
            }
        }

        $skills = $payload['skills'];
        if (count($skills) < 1 || count($skills) > 5) {
            $errors['skills'] = t('please_select_1_to_5_required_skills');
        }

        $skillIds = [];
        foreach ($skills as $index => $skill) {
            $skillField = 'skills_' . $index . '_skill_id';
            $proficiencyField = 'skills_' . $index . '_proficiency_level_id';

            if (empty($skill['skill_id'])) {
                $errors[$skillField] = t('validation_skill_required');
            } elseif (!$this->lookupModel->existsActive('skills', $skill['skill_id'])) {
                $errors[$skillField] = t('validation_invalid_selection');
            } else {
                $skillIds[] = (int)$skill['skill_id'];
            }

            if (empty($skill['proficiency_level_id'])) {
                $errors[$proficiencyField] = t('validation_proficiency_required');
            } elseif (!$this->lookupModel->existsActive('proficiency_levels', $skill['proficiency_level_id'])) {
                $errors[$proficiencyField] = t('validation_invalid_selection');
            }
        }

        if (count($skillIds) !== count(array_unique($skillIds))) {
            $errors['skills'] = t('duplicate_skills_not_allowed');
        }

        return $errors;
    }

    private function normalizeId($value) {
        if ($value === null || $value === '') {
            return null;
        }

        return ctype_digit((string)$value) ? (int)$value : null;
    }

    private function normalizeOpenings($value) {
        $value = trim((string)($value ?? ''));
        if ($value === '') {
            return '';
        }

        return ctype_digit($value) ? (int)$value : $value;
    }
}
