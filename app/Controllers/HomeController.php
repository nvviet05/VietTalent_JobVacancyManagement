<?php
class HomeController extends Controller {
    public function index() {
        $this->view('layouts/main', [
            'title' => t('hero_eyebrow'),
            'content' => 'dashboard/home',
        ]);
    }
}
