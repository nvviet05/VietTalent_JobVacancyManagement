<?php
class PublicJobController extends Controller {

    /** Items per page in the public job listing. */
    const PAGE_SIZE = 9;

    public function index() {
        $jobModel = new JobVacancy();
        $lookup   = new Lookup();

        $filters = $this->collectFilters();
        $sort    = $this->resolveSort($_GET['sort'] ?? 'newest');

        $page = max(1, (int)($_GET['p'] ?? 1));
        list($jobs, $total, $totalPages, $offset) = $this->paginate($jobModel, $filters, $sort, $page);

        // Clamp page if user navigated past the end.
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            list($jobs, $total, $totalPages, $offset) = $this->paginate($jobModel, $filters, $sort, $page);
        }

        $jobIds    = array_column($jobs, 'id');
        $skillsMap = $jobModel->getSkillsByJobIds($jobIds);

        // Build breadcrumb trail for criterion #5.
        $breadcrumbs = [
            ['label' => t('home'),        'url' => url('home')],
            ['label' => t('browse_jobs'), 'url' => null],
        ];
        if (!empty($filters['category_id'])) {
            $cat = $lookup->getActiveJobCategoryById((int)$filters['category_id']);
            if ($cat) {
                $breadcrumbs[1]['url']    = url('jobs');
                $breadcrumbs[]            = ['label' => $cat['name'], 'url' => null];
            }
        }

        $this->view('layouts/main', [
            'title'            => t('browse_jobs'),
            'meta_description' => 'Browse ' . $total . '+ verified job vacancies on VietTalent. Filter by category, location, skill, salary range and work arrangement.',
            'meta_keywords'    => 'jobs, vacancies, careers, Vietnam, hiring, employment, ' . t('browse_jobs'),
            'og_title'         => t('browse_jobs') . ' — VietTalent',
            'content'          => 'jobs/index',
            'scripts'          => ['js/filters.js'],
            'breadcrumbs'      => $breadcrumbs,
            'jobs'             => $jobs,
            'skillsMap'        => $skillsMap,
            'filters'          => $filters,
            'sort'             => $sort,
            'page'             => $page,
            'pageSize'         => self::PAGE_SIZE,
            'totalResults'     => $total,
            'totalPages'       => $totalPages,
            'offset'           => $offset,
            'categories'       => $lookup->getActiveJobCategories(),
            'countries'        => $lookup->getActiveCountries(),
            'cities'           => $lookup->getActiveCities(),
            'districts'        => $lookup->getActiveDistricts(),
            'employmentTypes'  => $lookup->getActiveEmploymentTypes(),
            'jobLevels'        => $lookup->getActiveJobLevels(),
            'salaryRanges'     => $lookup->getActiveSalaryRanges(),
            'workArrangements' => $lookup->getActiveWorkArrangements(),
            'skills'           => $lookup->getActiveSkills(),
        ]);
    }

    /**
     * AJAX endpoint: returns the job results as JSON so filters/sort/page can
     * update without a full page reload (criterion #4).
     */
    public function search() {
        $jobModel = new JobVacancy();

        $filters = $this->collectFilters();
        $sort    = $this->resolveSort($_GET['sort'] ?? 'newest');
        $page    = max(1, (int)($_GET['p'] ?? 1));

        list($jobs, $total, $totalPages, $offset) = $this->paginate($jobModel, $filters, $sort, $page);

        $jobIds    = array_column($jobs, 'id');
        $skillsMap = $jobModel->getSkillsByJobIds($jobIds);

        $items = [];
        foreach ($jobs as $job) {
            $items[] = [
                'id'              => (int)$job['id'],
                'job_title_name'  => $job['job_title_name'],
                'company_name'    => $job['company_name'],
                'category_name'   => $job['job_category_name'],
                'country_name'    => $job['country_name'],
                'city_name'       => $job['city_name'],
                'district_name'   => $job['district_name'],
                'employment_type' => $job['employment_type_name'],
                'work_arrangement'=> $job['work_arrangement_name'],
                'salary_label'    => $job['salary_range_label'],
                'created_at'      => $job['created_at'],
                'detail_url'      => url('job_detail', ['id' => $job['id']]),
                'skills'          => array_map(
                    fn($s) => $s['skill_name'] . ' (' . $s['proficiency_name'] . ')',
                    $skillsMap[$job['id']] ?? []
                ),
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'ok'          => true,
            'page'        => $page,
            'pageSize'    => self::PAGE_SIZE,
            'total'       => $total,
            'totalPages'  => $totalPages,
            'offset'      => $offset,
            'sort'        => $sort,
            'filters'     => $filters,
            'items'       => $items,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function show() {
        $jobId = $_GET['id'] ?? 0;
        if (!$jobId) {
            http_response_code(404);
            require APP_ROOT . '/resources/views/errors/404.php';
            return;
        }

        $jobModel = new JobVacancy();
        $job = $jobModel->getActiveJobDetail((int)$jobId);

        if (!$job) {
            http_response_code(404);
            require APP_ROOT . '/resources/views/errors/404.php';
            return;
        }

        $skillModel = new JobVacancySkill();
        $skills = $skillModel->getByJobVacancy($job['id']);

        // Breadcrumb trail: Home → Browse jobs → [Category] → Job title
        $breadcrumbs = [
            ['label' => t('home'),         'url' => url('home')],
            ['label' => t('browse_jobs'),  'url' => url('jobs')],
        ];
        if (!empty($job['job_category_name'])) {
            $breadcrumbs[] = ['label' => $job['job_category_name'], 'url' => url('jobs', ['category_id' => $job['job_category_id'] ?? ''])];
        }
        $breadcrumbs[] = ['label' => $job['job_title_name'], 'url' => null];

        $this->view('layouts/main', [
            'title'            => $job['job_title_name'] . ' - ' . $job['company_name'],
            'meta_description' => mb_substr(strip_tags($job['responsibilities'] ?? ''), 0, 155),
            'meta_keywords'    => $job['job_title_name'] . ', ' . $job['company_name'] . ', ' . $job['city_name'] . ', ' . $job['country_name'],
            'og_title'         => $job['job_title_name'] . ' at ' . $job['company_name'],
            'og_type'          => 'article',
            'content'          => 'jobs/detail',
            'breadcrumbs'      => $breadcrumbs,
            'job'              => $job,
            'skills'           => $skills,
            'scripts'          => [],
        ]);
    }

    private function collectFilters() {
        return [
            'keyword'            => trim($_GET['keyword'] ?? ''),
            'category_id'        => $_GET['category_id'] ?? '',
            'country_id'         => $_GET['country_id'] ?? '',
            'city_id'            => $_GET['city_id'] ?? '',
            'district_id'        => $_GET['district_id'] ?? '',
            'employment_type_id' => $_GET['employment_type_id'] ?? '',
            'job_level_id'       => $_GET['job_level_id'] ?? '',
            'salary_range_id'    => $_GET['salary_range_id'] ?? '',
            'work_arrangement_id'=> $_GET['work_arrangement_id'] ?? '',
            'skill_id'           => $_GET['skill_id'] ?? '',
        ];
    }

    private function resolveSort($sort) {
        return in_array($sort, ['newest', 'salary_asc', 'salary_desc', 'title_asc'], true)
            ? $sort
            : 'newest';
    }

    /**
     * Returns [$jobs, $total, $totalPages, $offset].
     */
    private function paginate(JobVacancy $jobModel, array $filters, string $sort, int $page) {
        $total      = $jobModel->countActiveJobs($filters);
        $totalPages = (int)max(1, ceil($total / self::PAGE_SIZE));
        $page       = max(1, $page);
        $offset     = ($page - 1) * self::PAGE_SIZE;
        $jobs       = $jobModel->searchActiveJobs($filters, $sort, self::PAGE_SIZE, $offset);
        return [$jobs, $total, $totalPages, $offset];
    }
}
