<?php
class LanguageController extends Controller {
    public function set() {
        $lang = $_GET['lang'] ?? 'en';
        $_SESSION['lang'] = in_array($lang, ['en', 'vi'], true) ? $lang : 'en';

        $return = $_GET['return'] ?? url('home');
        if (strpos($return, '/VietTalent_JobVacancyManagement/public/') !== 0) {
            $return = url('home');
        }

        header('Location: ' . $return);
        exit;
    }
}
