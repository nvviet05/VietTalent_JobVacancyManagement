<?php
class HomeController extends Controller {
    public function index() {
        $jobModel = new JobVacancy();
        $lookup = new Lookup();

        $latestJobs = $jobModel->getLatestActiveJobs(6);
        $jobIds = array_column($latestJobs, 'id');
        $skillsMap = $jobModel->getSkillsByJobIds($jobIds);
        $categories = $lookup->getCategoriesWithJobCount();

        $this->view('layouts/main', [
            'title'     => t('hero_eyebrow'),
            'content'   => 'dashboard/home',
            'latestJobs' => $latestJobs,
            'skillsMap'  => $skillsMap,
            'categories' => $categories,
        ]);
    }
}
