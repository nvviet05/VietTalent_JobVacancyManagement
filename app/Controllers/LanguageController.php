<?php
class LanguageController extends Controller {
    public function set() {
        $lang = $_GET['lang'] ?? 'en';
        $_SESSION['lang'] = in_array($lang, ['en', 'vi'], true) ? $lang : 'en';

        $return = $_GET['return'] ?? url('home');
        $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?: '';
        if ($basePath === '' || strpos($return, $basePath . '/') !== 0) {
            $return = url('home');
        }

        header('Location: ' . $return);
        exit;
    }
}
