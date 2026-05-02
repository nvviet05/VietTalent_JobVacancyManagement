<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <?php include APP_ROOT . '/resources/views/partials/brand-logo.php'; ?>
            <p><?= e(t('footer_desc')) ?></p>
        </div>
        <div>
            <h4><?= e(t('browse_jobs')) ?></h4>
            <a href="<?= url('jobs') ?>"><?= e(t('all_jobs')) ?></a>
            <a href="<?= url('login') ?>"><?= e(t('login')) ?></a>
            <a href="<?= url('register') ?>"><?= e(t('register')) ?></a>
        </div>
    </div>
</footer>
