<section class="dashboard-stack">
    <div class="dashboard-welcome card">
        <div>
            <span class="eyebrow"><?= e(t('admin_dashboard')) ?></span>
            <h2><?= e(t('welcome_administrator')) ?> <?= e(Auth::user()['full_name'] ?? '') ?></h2>
            <p><?= e(t('admin_dashboard_intro')) ?></p>
        </div>
        <div class="dashboard-actions">
            <a class="btn btn-primary" href="<?= url('admin_jobs') ?>"><?= e(t('manage_job_postings')) ?></a>
            <a class="btn btn-outline" href="#adminQuickLinks"><?= e(t('admin_quick_links')) ?></a>
        </div>
    </div>

    <div class="stats-grid stats-grid-admin">
        <article class="stat-card card">
            <span><?= e(t('total_jobs')) ?></span>
            <strong><?= e((string)$stats['total']) ?></strong>
        </article>
        <article class="stat-card card">
            <span><?= e(t('active_jobs')) ?></span>
            <strong><?= e((string)$stats['active']) ?></strong>
        </article>
        <article class="stat-card card">
            <span><?= e(t('inactive_jobs')) ?></span>
            <strong><?= e((string)$stats['inactive']) ?></strong>
        </article>
        <article class="stat-card card">
            <span><?= e(t('removed_jobs')) ?></span>
            <strong><?= e((string)$stats['removed']) ?></strong>
        </article>
        <article class="stat-card card">
            <span><?= e(t('total_employers')) ?></span>
            <strong><?= e((string)$stats['employers']) ?></strong>
        </article>
        <article class="stat-card card">
            <span><?= e(t('total_job_seekers')) ?></span>
            <strong><?= e((string)$stats['job_seekers']) ?></strong>
        </article>
    </div>

    <section class="card section-card" id="adminQuickLinks">
        <div class="section-card-header">
            <div>
                <h3><?= e(t('admin_quick_links')) ?></h3>
                <p><?= e(t('admin_quick_links_intro')) ?></p>
            </div>
        </div>
        <div class="quick-links-grid">
            <?php foreach ($quickLinks as $link): ?>
                <a class="quick-link-card" href="<?= e($link['url']) ?>">
                    <strong><?= e($link['label']) ?></strong>
                    <span><?= e($link['description']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="card section-card">
        <div class="section-card-header">
            <div>
                <h3><?= e(t('recent_job_postings')) ?></h3>
                <p><?= e(t('admin_recent_jobs_helper')) ?></p>
            </div>
        </div>

        <?php if (empty($recentJobs)): ?>
            <div class="empty-state">
                <p><?= e(t('no_job_postings_available')) ?></p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><?= e(t('job_title')) ?></th>
                            <th><?= e(t('company')) ?></th>
                            <th><?= e(t('status')) ?></th>
                            <th><?= e(t('created_date')) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentJobs as $job): ?>
                            <tr>
                                <td><?= e($job['job_title_name']) ?></td>
                                <td>
                                    <?= e($job['company_name']) ?>
                                    <br>
                                    <span class="table-note"><?= e($job['employer_name']) ?></span>
                                </td>
                                <td><span class="status-badge status-<?= e($job['status']) ?>"><?= e(t($job['status'])) ?></span></td>
                                <td><?= e(date('Y-m-d', strtotime($job['created_at']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</section>
