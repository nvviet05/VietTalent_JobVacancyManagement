<?php
require_once APP_ROOT . '/app/Models/Lookup.php';
require_once APP_ROOT . '/app/Models/JobVacancy.php';
require_once APP_ROOT . '/app/Models/StoreLocation.php';

class HomeController extends Controller {
    public function index() {
        $jobModel = new JobVacancy();
        $lookup = new Lookup();

        $latestJobs = $jobModel->getLatestActiveJobs(6);
        $jobIds = array_column($latestJobs, 'id');
        $skillsMap = $jobModel->getSkillsByJobIds($jobIds);
        $categories = $lookup->getCategoriesWithJobCount();

        $this->view('layouts/main', [
            'title'            => t('hero_eyebrow'),
            'meta_description' => 'VietTalent — verified, structured job vacancies from trusted employers across Vietnam. Search by category, location, skill and salary.',
            'meta_keywords'    => 'jobs, vacancies, careers, VietTalent, Vietnam, hiring',
            'og_title'         => 'VietTalent — Find your next career opportunity',
            'content'          => 'dashboard/home',
            'breadcrumbs'      => [['label' => t('home'), 'url' => null]],
            'latestJobs'       => $latestJobs,
            'skillsMap'        => $skillsMap,
            'categories'       => $categories,
        ]);
    }

    /**
     * Store / branch locations page with embedded Google Maps (criterion #6).
     */
    public function locations() {
        $locModel = new StoreLocation();
        $locations = $locModel->getActive();

        $this->view('layouts/main', [
            'title'            => 'Our Locations',
            'meta_description' => 'Visit a VietTalent office. Find our offices and contact details across Vietnam.',
            'meta_keywords'    => 'VietTalent offices, store locations, contact, Vietnam',
            'og_title'         => 'VietTalent — Our Locations',
            'content'          => 'public/locations',
            'breadcrumbs'      => [
                ['label' => t('home'),  'url' => url('home')],
                ['label' => 'Our Locations', 'url' => null],
            ],
            'locations'        => $locations,
        ]);
    }

    /**
     * Dynamic sitemap.xml — counts every active job & lookup category so search
     * engines have a fresh URL list (SEO criterion #8).
     */
    public function sitemap() {
        $jobModel = new JobVacancy();
        $lookup   = new Lookup();
        $jobs     = $jobModel->searchActiveJobs([], 'newest', 1000, 0);
        $cats     = $lookup->getActiveJobCategories();

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $base = rtrim(BASE_URL, '/');
        $static = ['home', 'jobs', 'login', 'register', 'forgot_password', 'locations'];
        foreach ($static as $page) {
            echo "  <url><loc>{$base}/index.php?page=" . htmlspecialchars($page, ENT_XML1) . "</loc><changefreq>weekly</changefreq></url>\n";
        }
        foreach ($cats as $c) {
            echo "  <url><loc>{$base}/index.php?page=jobs&amp;category_id=" . (int)$c['id'] . "</loc><changefreq>daily</changefreq></url>\n";
        }
        foreach ($jobs as $j) {
            $lastmod = htmlspecialchars(substr($j['created_at'] ?? date('c'), 0, 10), ENT_XML1);
            echo "  <url><loc>{$base}/index.php?page=job_detail&amp;id=" . (int)$j['id'] . "</loc><lastmod>{$lastmod}</lastmod><changefreq>weekly</changefreq></url>\n";
        }
        echo '</urlset>';
    }

    public function robots() {
        header('Content-Type: text/plain; charset=utf-8');
        $base = rtrim(BASE_URL, '/');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /index.php?page=admin_*\n";
        echo "Disallow: /index.php?page=employer_*\n";
        echo "Sitemap: {$base}/index.php?page=sitemap\n";
    }
}
