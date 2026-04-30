<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow"><?= e(t('hero_eyebrow')) ?></span>
            <h1><?= e(t('hero_title')) ?></h1>
            <p><?= e(t('hero_intro')) ?></p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="<?= url('register') ?>"><?= e(t('create_account')) ?></a>
                <a class="btn btn-outline" href="<?= url('login') ?>"><?= e(t('login')) ?></a>
            </div>
        </div>
        <div class="hero-card">
            <h2><?= e(t('implemented_phase1')) ?></h2>
            <ul class="check-list">
                <li><?= e(t('item_structure')) ?></li>
                <li><?= e(t('item_database')) ?></li>
                <li><?= e(t('item_auth')) ?></li>
                <li><?= e(t('item_roles')) ?></li>
                <li><?= e(t('item_admin')) ?></li>
                <li><?= e(t('item_redirects')) ?></li>
            </ul>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow"><?= e(t('constraints')) ?></span>
            <h2><?= e(t('scope_title')) ?></h2>
        </div>
        <div class="feature-grid">
            <article class="card">
                <h3><?= e(t('normalized_data')) ?></h3>
                <p><?= e(t('normalized_data_text')) ?></p>
            </article>
            <article class="card">
                <h3><?= e(t('role_access')) ?></h3>
                <p><?= e(t('role_access_text')) ?></p>
            </article>
            <article class="card">
                <h3><?= e(t('no_out_scope')) ?></h3>
                <p><?= e(t('no_out_scope_text')) ?></p>
            </article>
        </div>
    </div>
</section>
