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

    public function locationsIndex() {
        $this->redirect('admin_countries');
    }

    public function countriesIndex() {
        $this->renderLocationIndex('countries');
    }

    public function countryCreate() {
        $this->renderLocationCreate('countries');
    }

    public function countryStore() {
        $this->handleLocationStore('countries');
    }

    public function countryEdit() {
        $this->renderLocationEdit('countries');
    }

    public function countryUpdate() {
        $this->handleLocationUpdate('countries');
    }

    public function countryToggleStatus() {
        $this->handleLocationToggle('countries');
    }

    public function citiesIndex() {
        $this->renderLocationIndex('cities');
    }

    public function cityCreate() {
        $this->renderLocationCreate('cities');
    }

    public function cityStore() {
        $this->handleLocationStore('cities');
    }

    public function cityEdit() {
        $this->renderLocationEdit('cities');
    }

    public function cityUpdate() {
        $this->handleLocationUpdate('cities');
    }

    public function cityToggleStatus() {
        $this->handleLocationToggle('cities');
    }

    public function districtsIndex() {
        $this->renderLocationIndex('districts');
    }

    public function districtCreate() {
        $this->renderLocationCreate('districts');
    }

    public function districtStore() {
        $this->handleLocationStore('districts');
    }

    public function districtEdit() {
        $this->renderLocationEdit('districts');
    }

    public function districtUpdate() {
        $this->handleLocationUpdate('districts');
    }

    public function districtToggleStatus() {
        $this->handleLocationToggle('districts');
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

    private function renderLocationIndex($type) {
        $meta = $this->getLocationMetaOr404($type);

        $this->view('layouts/dashboard', [
            'title' => t($meta['title_key']),
            'subtitle' => t('location_management_subtitle'),
            'content' => 'admin/locations/index',
            'role' => 'admin',
            'activeNav' => 'locations',
            'activeLocationType' => $meta['key'],
            'styles' => ['css/jobs.css'],
            'locationMeta' => $meta,
            'records' => $this->loadLocationRecords($meta['key']),
        ]);
    }

    private function renderLocationCreate($type) {
        $meta = $this->getLocationMetaOr404($type);
        $oldInput = $_SESSION['old_input'] ?? [];

        $this->view('layouts/dashboard', [
            'title' => t($meta['create_title_key']),
            'subtitle' => t($meta['create_subtitle_key']),
            'content' => 'admin/locations/create',
            'role' => 'admin',
            'activeNav' => 'locations',
            'activeLocationType' => $meta['key'],
            'styles' => ['css/jobs.css'],
            'locationMeta' => $meta,
            'formData' => $this->buildLocationFormData($meta, $oldInput),
            'parentOptions' => $this->getLocationParentOptions($meta['key']),
        ]);

        clearOldInput();
    }

    private function handleLocationStore($type) {
        $meta = $this->getLocationMetaOr404($type);

        if (!$this->isPostRequest()) {
            $this->redirect($meta['create_route']);
        }

        $payload = $this->sanitizeLocationPayload($meta, $_POST);
        $errors = $this->validateLocationPayload($meta, $payload);

        if (!empty($errors)) {
            setOldInput($_POST);
            setValidationErrors($errors);
            $this->redirect($meta['create_route']);
        }

        $this->createLocationRecord($meta['key'], $payload);
        clearOldInput();
        $this->setFlash('success', t('location_record_created_successfully'));
        $this->redirect($meta['index_route']);
    }

    private function renderLocationEdit($type) {
        $meta = $this->getLocationMetaOr404($type);
        $recordId = $this->getLookupRecordIdOr404();
        $record = $this->findLocationRecord($meta['key'], $recordId);

        if (!$record) {
            $this->render404();
        }

        $oldInput = $_SESSION['old_input'] ?? [];
        $source = !empty($oldInput) ? $oldInput : $record;

        $this->view('layouts/dashboard', [
            'title' => t($meta['edit_title_key']),
            'subtitle' => t($meta['edit_subtitle_key']),
            'content' => 'admin/locations/edit',
            'role' => 'admin',
            'activeNav' => 'locations',
            'activeLocationType' => $meta['key'],
            'styles' => ['css/jobs.css'],
            'locationMeta' => $meta,
            'recordId' => $recordId,
            'formData' => $this->buildLocationFormData($meta, $source),
            'parentOptions' => $this->getLocationParentOptions($meta['key']),
        ]);

        clearOldInput();
    }

    private function handleLocationUpdate($type) {
        $meta = $this->getLocationMetaOr404($type);
        $recordId = $this->getLookupRecordIdOr404();
        $record = $this->findLocationRecord($meta['key'], $recordId);

        if (!$record) {
            $this->setFlash('error', t($meta['invalid_key']));
            $this->redirect($meta['index_route']);
        }

        if (!$this->isPostRequest()) {
            $this->redirect($meta['edit_route'], ['id' => $recordId]);
        }

        $payload = $this->sanitizeLocationPayload($meta, $_POST);
        $errors = $this->validateLocationPayload($meta, $payload, $recordId);

        if (!empty($errors)) {
            setOldInput($_POST);
            setValidationErrors($errors);
            $this->redirect($meta['edit_route'], ['id' => $recordId]);
        }

        $this->updateLocationRecord($meta['key'], $recordId, $payload);
        clearOldInput();
        $this->setFlash('success', t('location_record_updated_successfully'));
        $this->redirect($meta['index_route']);
    }

    private function handleLocationToggle($type) {
        $meta = $this->getLocationMetaOr404($type);
        $recordId = $this->getLookupRecordIdOr404();
        $record = $this->findLocationRecord($meta['key'], $recordId);

        if (!$record) {
            $this->setFlash('error', t($meta['invalid_key']));
            $this->redirect($meta['index_route']);
        }

        $updated = $this->toggleLocationStatus($meta['key'], $recordId);
        if ($updated === false) {
            $this->setFlash('error', t($meta['invalid_key']));
        } else {
            $this->setFlash('success', t('location_status_updated_successfully'));
        }

        $this->redirect($meta['index_route']);
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

    private function getLocationMetaOr404($type) {
        $meta = $this->getLocationMeta($type);
        if (!$meta) {
            $this->render404();
        }

        return $meta;
    }

    private function getLocationMeta($type) {
        $map = [
            'countries' => [
                'key' => 'countries',
                'title_key' => 'countries',
                'name_label_key' => 'country_name',
                'index_route' => 'admin_countries',
                'create_route' => 'admin_country_create',
                'store_route' => 'admin_country_store',
                'edit_route' => 'admin_country_edit',
                'update_route' => 'admin_country_update',
                'toggle_route' => 'admin_country_toggle_status',
                'create_title_key' => 'create_country',
                'create_subtitle_key' => 'location_create_subtitle',
                'edit_title_key' => 'edit_country',
                'update_title_key' => 'update_country',
                'edit_subtitle_key' => 'location_edit_subtitle',
                'back_key' => 'back_to_locations',
                'invalid_key' => 'invalid_country',
            ],
            'cities' => [
                'key' => 'cities',
                'title_key' => 'cities',
                'name_label_key' => 'city_province_name',
                'parent_type' => 'countries',
                'parent_field' => 'country_id',
                'parent_label_key' => 'country',
                'index_route' => 'admin_cities',
                'create_route' => 'admin_city_create',
                'store_route' => 'admin_city_store',
                'edit_route' => 'admin_city_edit',
                'update_route' => 'admin_city_update',
                'toggle_route' => 'admin_city_toggle_status',
                'create_title_key' => 'create_city',
                'create_subtitle_key' => 'location_create_subtitle',
                'edit_title_key' => 'edit_city',
                'update_title_key' => 'update_city',
                'edit_subtitle_key' => 'location_edit_subtitle',
                'back_key' => 'back_to_cities',
                'invalid_key' => 'invalid_city',
            ],
            'districts' => [
                'key' => 'districts',
                'title_key' => 'districts',
                'name_label_key' => 'district_name',
                'parent_type' => 'cities',
                'parent_field' => 'city_id',
                'parent_label_key' => 'city_province',
                'index_route' => 'admin_districts',
                'create_route' => 'admin_district_create',
                'store_route' => 'admin_district_store',
                'edit_route' => 'admin_district_edit',
                'update_route' => 'admin_district_update',
                'toggle_route' => 'admin_district_toggle_status',
                'create_title_key' => 'create_district',
                'create_subtitle_key' => 'location_create_subtitle',
                'edit_title_key' => 'edit_district',
                'update_title_key' => 'update_district',
                'edit_subtitle_key' => 'location_edit_subtitle',
                'back_key' => 'back_to_districts',
                'invalid_key' => 'invalid_district',
            ],
        ];

        return $map[$type] ?? null;
    }

    private function loadLocationRecords($type) {
        if ($type === 'countries') {
            return $this->lookupModel->getCountries();
        }

        if ($type === 'cities') {
            return $this->lookupModel->getCities();
        }

        return $this->lookupModel->getDistricts();
    }

    private function getLocationParentOptions($type) {
        if ($type === 'cities') {
            return $this->lookupModel->getCountries();
        }

        if ($type === 'districts') {
            return $this->lookupModel->getCities();
        }

        return [];
    }

    private function findLocationRecord($type, $id) {
        if ($type === 'countries') {
            return $this->lookupModel->findCountry($id);
        }

        if ($type === 'cities') {
            return $this->lookupModel->findCity($id);
        }

        return $this->lookupModel->findDistrict($id);
    }

    private function createLocationRecord($type, $payload) {
        if ($type === 'countries') {
            return $this->lookupModel->createCountry($payload);
        }

        if ($type === 'cities') {
            return $this->lookupModel->createCity($payload);
        }

        return $this->lookupModel->createDistrict($payload);
    }

    private function updateLocationRecord($type, $id, $payload) {
        if ($type === 'countries') {
            return $this->lookupModel->updateCountry($id, $payload);
        }

        if ($type === 'cities') {
            return $this->lookupModel->updateCity($id, $payload);
        }

        return $this->lookupModel->updateDistrict($id, $payload);
    }

    private function toggleLocationStatus($type, $id) {
        if ($type === 'countries') {
            return $this->lookupModel->toggleCountryStatus($id);
        }

        if ($type === 'cities') {
            return $this->lookupModel->toggleCityStatus($id);
        }

        return $this->lookupModel->toggleDistrictStatus($id);
    }

    private function buildLocationFormData($meta, $source) {
        $data = [
            'name' => trim((string)($source['name'] ?? '')),
            'status' => trim((string)($source['status'] ?? 'active')),
        ];

        if (!empty($meta['parent_field'])) {
            $data[$meta['parent_field']] = trim((string)($source[$meta['parent_field']] ?? ''));
        }

        return $data;
    }

    private function sanitizeLocationPayload($meta, $input) {
        $payload = [
            'name' => trim((string)($input['name'] ?? '')),
            'status' => trim((string)($input['status'] ?? 'active')),
        ];

        if (!empty($meta['parent_field'])) {
            $payload[$meta['parent_field']] = ctype_digit((string)($input[$meta['parent_field']] ?? ''))
                ? (int)$input[$meta['parent_field']]
                : null;
        }

        return $payload;
    }

    private function validateLocationPayload($meta, $payload, $excludeId = null) {
        $errors = [];

        if ($payload['name'] === '') {
            $errors['name'] = t('name_is_required');
        }

        if (!in_array($payload['status'], ['active', 'inactive'], true)) {
            $errors['status'] = t('invalid_status');
        }

        if ($meta['key'] === 'countries') {
            if ($payload['name'] !== '' && $this->lookupModel->isDuplicateCountryName($payload['name'], $excludeId)) {
                $errors['name'] = t('duplicate_country_not_allowed');
            }

            return $errors;
        }

        if ($meta['key'] === 'cities') {
            if (empty($payload['country_id'])) {
                $errors['country_id'] = t('country_is_required');
            } elseif (!$this->lookupModel->countryExists($payload['country_id'])) {
                $errors['country_id'] = t('invalid_country');
            }

            if ($payload['name'] !== '' && empty($errors['country_id']) && $this->lookupModel->isDuplicateCityName($payload['country_id'], $payload['name'], $excludeId)) {
                $errors['name'] = t('duplicate_city_not_allowed');
            }

            return $errors;
        }

        if (empty($payload['city_id'])) {
            $errors['city_id'] = t('city_is_required');
        } elseif (!$this->lookupModel->cityExists($payload['city_id'])) {
            $errors['city_id'] = t('invalid_city');
        }

        if ($payload['name'] !== '' && empty($errors['city_id']) && $this->lookupModel->isDuplicateDistrictName($payload['city_id'], $payload['name'], $excludeId)) {
            $errors['name'] = t('duplicate_district_not_allowed');
        }

        return $errors;
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
