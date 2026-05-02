<?php
class PublicJobController extends Controller {

    public function index() {
        $jobModel = new JobVacancy();
        $lookup = new Lookup();

        $filters = [
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

        $sort = $_GET['sort'] ?? 'newest';
        if (!in_array($sort, ['newest', 'salary_asc', 'salary_desc', 'title_asc'], true)) {
            $sort = 'newest';
        }

        $jobs = $jobModel->searchActiveJobs($filters, $sort);

        $jobIds = array_column($jobs, 'id');
        $skillsMap = $jobModel->getSkillsByJobIds($jobIds);

        $this->view('layouts/main', [
            'title'            => t('browse_jobs'),
            'content'          => 'jobs/index',
            'scripts'          => ['js/filters.js'],
            'jobs'             => $jobs,
            'skillsMap'        => $skillsMap,
            'filters'          => $filters,
            'sort'             => $sort,
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

        $this->view('layouts/main', [
            'title'   => e($job['job_title_name']) . ' - ' . e($job['company_name']),
            'content' => 'jobs/detail',
            'job'     => $job,
            'skills'  => $skills,
        ]);
    }
}
