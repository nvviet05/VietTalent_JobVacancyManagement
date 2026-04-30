<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <?php include APP_ROOT . '/resources/views/partials/brand-logo.php'; ?>
            <p><?= e(t('footer_desc')) ?></p>
        </div>
        <div>
            <h4><?= e(t('phase1')) ?></h4>
            <a href="<?= url('login') ?>"><?= e(t('login')) ?></a>
            <a href="<?= url('register') ?>"><?= e(t('register')) ?></a>
        </div>
        <div>
            <h4><?= e(t('scope_rules')) ?></h4>
            <p><?= e(t('scope_rules_text')) ?></p>
        </div>
    </div>
</footer>
