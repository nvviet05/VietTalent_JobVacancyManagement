<section class="dashboard-stack">
    <div class="section-toolbar">
        <div>
            <h2><?= e(t('all_job_postings')) ?></h2>
            <p><?= e(t('admin_jobs_intro')) ?></p>
        </div>
        <a class="btn btn-outline" href="<?= url('admin_dashboard') ?>"><?= e(t('admin_dashboard')) ?></a>
    </div>

    <section class="card section-card">
        <?php if (empty($jobs)): ?>
            <div class="empty-state">
                <p><?= e(t('no_job_postings_available')) ?></p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th><?= e(t('job_title')) ?></th>
                            <th><?= e(t('company')) ?></th>
                            <th><?= e(t('job_category')) ?></th>
                            <th><?= e(t('location')) ?></th>
                            <th><?= e(t('salary_range')) ?></th>
                            <th><?= e(t('status')) ?></th>
                            <th><?= e(t('created_date')) ?></th>
                            <th><?= e(t('actions')) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><?= e((string)$job['id']) ?></td>
                                <td><?= e($job['job_title_name']) ?></td>
                                <td>
                                    <?= e($job['company_name']) ?>
                                    <br>
                                    <span class="table-note"><?= e($job['employer_name']) ?></span>
                                </td>
                                <td><?= e($job['job_category_name']) ?></td>
                                <td>
                                    <?= e(trim($job['district_name'] ? $job['district_name'] . ', ' : '') . $job['city_name'] . ', ' . $job['country_name']) ?>
                                    <br>
                                    <span class="table-note"><?= e($job['work_arrangement_name']) ?></span>
                                </td>
                                <td><?= e($job['salary_range_label']) ?></td>
                                <td><span class="status-badge status-<?= e($job['status']) ?>"><?= e(t($job['status'])) ?></span></td>
                                <td><?= e(date('Y-m-d', strtotime($job['created_at']))) ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a class="table-action" href="<?= url('admin_job_view', ['id' => $job['id']]) ?>"><?= e(t('view')) ?></a>
                                        <a class="table-action" href="<?= url('admin_job_set_status', ['id' => $job['id'], 'status' => 'active']) ?>"><?= e(t('set_active')) ?></a>
                                        <a class="table-action" href="<?= url('admin_job_set_status', ['id' => $job['id'], 'status' => 'inactive']) ?>"><?= e(t('set_inactive')) ?></a>
                                        <a class="table-action table-action-danger" href="<?= url('admin_job_set_status', ['id' => $job['id'], 'status' => 'removed']) ?>" onclick="return confirm('<?= e(t('remove_job_confirm')) ?>');"><?= e(t('remove')) ?></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</section>
