<?php
class HomeController {
    public function index() {
        $jobModel = new JobVacancy();
        
        $data = [
            'title' => 'Home - Career Portal',
            'content' => 'home', 
            'jobs' => $jobModel->getLatestActive(6)
        ];

        // Unpack variables and load layout directly
        extract($data);
        require APP_ROOT . '/resources/views/layouts/main.php';
    }
}
