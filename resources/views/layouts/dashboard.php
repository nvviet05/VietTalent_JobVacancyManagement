<!DOCTYPE html>
<html lang="<?= e(currentLang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? t('dashboard')) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
</head>
<body class="dashboard-body">
    <div class="dashboard-layout">
        <?php include APP_ROOT . '/resources/views/partials/sidebar.php'; ?>
        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <button class="icon-btn" type="button" data-sidebar-toggle aria-label="Toggle sidebar">☰</button>
                <div>
                    <h1><?= e($title) ?></h1>
                    <p><?= e(t('dashboard_subtitle')) ?></p>
                </div>
                <div class="topbar-user">
                    <?php include APP_ROOT . '/resources/views/partials/language-switcher.php'; ?>
                    <span><?= e(Auth::user()['full_name'] ?? '') ?></span>
                    <a href="<?= url('logout') ?>" class="btn btn-outline btn-sm"><?= e(t('logout')) ?></a>
                </div>
            </header>
            <?php include APP_ROOT . '/resources/views/partials/flash-message.php'; ?>
            <main class="dashboard-content">
                <?php include APP_ROOT . '/resources/views/' . $content . '.php'; ?>
            </main>
        </div>
    </div>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
