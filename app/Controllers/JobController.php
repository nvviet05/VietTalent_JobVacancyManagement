<?php
class JobController {
    private $jobModel;
    private $lookupModel;

    public function __construct() {
        $this->jobModel = new JobVacancy();
        $this->lookupModel = new Lookup(); 
    }

    public function index() {
        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'country_id' => $_GET['country_id'] ?? '',
            'city_id' => $_GET['city_id'] ?? '',
            'skill_id' => $_GET['skill_id'] ?? '',
            'employment_type_id' => $_GET['employment_type_id'] ?? '',
            'job_level_id' => $_GET['job_level_id'] ?? '',
            'salary_range_id' => $_GET['salary_range_id'] ?? '',
            'work_arrangement_id' => $_GET['work_arrangement_id'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        $data = [
            'title' => 'Search Jobs',
            'content' => 'jobs/index',
            'jobs' => $this->jobModel->searchActiveJobs($filters),
            'categories' => $this->lookupModel->getAllActive('job_categories'),
            'countries' => $this->lookupModel->getActiveCountries(),
            'cities' => $this->lookupModel->getActiveCities(),
            'skills' => $this->lookupModel->getAllActive('skills'),
            'employmentTypes' => $this->lookupModel->getAllActive('employment_types'),
            'jobLevels' => $this->lookupModel->getAllActive('job_levels'),
            'salaryRanges' => $this->lookupModel->getAllActive('salary_ranges'),
            'workArrangements' => $this->lookupModel->getAllActive('work_arrangements')
        ];

        // Unpack variables and load layout directly
        extract($data);
        require APP_ROOT . '/resources/views/layouts/main.php';
    }

    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: ' . url('jobs')); exit; }

        $job = $this->jobModel->getActiveJobById($id);
        if (!$job) die("Job not found. <a href='".url('jobs')."'>Back to search</a>");

        $data = [
            'title' => $job['job_title_name'], 
            'content' => 'jobs/detail',
            'job' => $job
        ];

        // Unpack variables and load layout directly
        extract($data);
        require APP_ROOT . '/resources/views/layouts/main.php';
    }
}
