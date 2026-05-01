<section class="dashboard-stack">
    <div class="section-toolbar">
        <div>
            <h2><?= e(t('my_job_postings')) ?></h2>
            <p><?= e(t('my_jobs_intro')) ?></p>
        </div>
        <a class="btn btn-primary" href="<?= url('employer_job_create') ?>"><?= e(t('create_new_job')) ?></a>
    </div>

    <section class="card section-card">
        <?php if (empty($jobs)): ?>
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
                            <th><?= e(t('job_category')) ?></th>
                            <th><?= e(t('employment_type')) ?></th>
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
                                <td><?= e($job['job_title_name']) ?></td>
                                <td><?= e($job['job_category_name']) ?></td>
                                <td><?= e($job['employment_type_name']) ?></td>
                                <td><?= e(trim($job['district_name'] ? $job['district_name'] . ', ' : '') . $job['city_name'] . ', ' . $job['country_name']) ?><br><span class="table-note"><?= e($job['work_arrangement_name']) ?></span></td>
                                <td><?= e($job['salary_range_label']) ?><br><span class="table-note"><?= e($job['salary_type_name']) ?></span></td>
                                <td><span class="status-badge status-<?= e($job['status']) ?>"><?= e(t($job['status'])) ?></span></td>
                                <td><?= e(date('Y-m-d', strtotime($job['created_at']))) ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a class="table-action" href="<?= url('employer_job_view', ['id' => $job['id']]) ?>"><?= e(t('view_job')) ?></a>
                                        <a class="table-action" href="<?= url('employer_job_edit', ['id' => $job['id']]) ?>"><?= e(t('edit_job')) ?></a>
                                        <form action="<?= url('employer_job_toggle_status', ['id' => $job['id']]) ?>" method="POST">
                                            <button class="table-action button-link" type="submit"><?= e($job['status'] === 'active' ? t('deactivate') : t('activate')) ?></button>
                                        </form>
                                        <form action="<?= url('employer_job_delete', ['id' => $job['id']]) ?>" method="POST" onsubmit="return confirm('<?= e(t('delete_job_confirm')) ?>');">
                                            <button class="table-action table-action-danger button-link" type="submit"><?= e(t('delete')) ?></button>
                                        </form>
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
