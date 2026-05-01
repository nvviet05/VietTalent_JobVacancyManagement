<section class="dashboard-stack">
    <div class="dashboard-welcome card">
        <div>
            <span class="eyebrow"><?= e(t('employer_dashboard')) ?></span>
            <h2><?= e(t('welcome_company')) ?> <?= e($companyName) ?></h2>
            <p><?= e(t('employer_dashboard_intro')) ?></p>
        </div>
        <div class="dashboard-actions">
            <a class="btn btn-primary" href="<?= url('employer_job_create') ?>"><?= e(t('create_new_job')) ?></a>
            <a class="btn btn-outline" href="<?= url('employer_jobs') ?>"><?= e(t('my_job_postings')) ?></a>
        </div>
    </div>

    <div class="stats-grid">
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
    </div>

    <section class="card section-card">
        <div class="section-card-header">
            <div>
                <h3><?= e(t('recent_job_postings')) ?></h3>
                <p><?= e(t('recent_jobs_helper')) ?></p>
            </div>
            <a class="btn btn-outline btn-sm" href="<?= url('employer_jobs') ?>"><?= e(t('view_all_jobs')) ?></a>
        </div>

        <?php if (empty($recentJobs)): ?>
            <div class="empty-state">
                <p><?= e(t('no_jobs_created_yet')) ?></p>
                <a class="btn btn-primary" href="<?= url('employer_job_create') ?>"><?= e(t('create_new_job')) ?></a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><?= e(t('job_title')) ?></th>
                            <th><?= e(t('status')) ?></th>
                            <th><?= e(t('created_date')) ?></th>
                            <th><?= e(t('actions')) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentJobs as $job): ?>
                            <tr>
                                <td><?= e($job['job_title_name']) ?></td>
                                <td><span class="status-badge status-<?= e($job['status']) ?>"><?= e(t($job['status'])) ?></span></td>
                                <td><?= e(date('Y-m-d', strtotime($job['created_at']))) ?></td>
                                <td><a class="table-action" href="<?= url('employer_job_view', ['id' => $job['id']]) ?>"><?= e(t('view_job')) ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</section>
