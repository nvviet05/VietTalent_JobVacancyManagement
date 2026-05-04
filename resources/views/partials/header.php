<header class="site-header">
    <div class="container header-inner">
        <?php include APP_ROOT . '/resources/views/partials/brand-logo.php'; ?>
        <nav class="site-nav" data-mobile-nav>
            <a href="<?= url('home') ?>"><?= e(t('home')) ?></a>
            <a href="<?= url('jobs') ?>"><?= e(t('browse_jobs')) ?></a>
            <a href="<?= url('locations') ?>"><?= e(t('locations_nav')) ?></a>
            <?php if (Auth::check()): ?>
                <?php if (Auth::isRole('admin')): ?>
                    <a href="<?= url('admin_dashboard') ?>"><?= e(t('dashboard')) ?></a>
                <?php elseif (Auth::isRole('employer')): ?>
                    <a href="<?= url('employer_dashboard') ?>"><?= e(t('dashboard')) ?></a>
                <?php else: ?>
                    <a href="<?= url('job_seeker_dashboard') ?>"><?= e(t('dashboard')) ?></a>
                <?php endif; ?>
                <a class="btn btn-outline btn-sm" href="<?= url('logout') ?>"><?= e(t('logout')) ?></a>
            <?php else: ?>
                <a href="<?= url('login') ?>"><?= e(t('login')) ?></a>
                <a class="btn btn-primary btn-sm" href="<?= url('register') ?>"><?= e(t('register')) ?></a>
            <?php endif; ?>
            <?php include APP_ROOT . '/resources/views/partials/language-switcher.php'; ?>
        </nav>
        <button class="icon-btn mobile-menu-btn" type="button" data-mobile-menu-toggle aria-label="Toggle menu">☰</button>
    </div>
</header>
