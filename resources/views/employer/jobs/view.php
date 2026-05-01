<section class="dashboard-stack">
    <div class="section-toolbar">
        <div>
            <h2><?= e($job['job_title_name']) ?></h2>
            <p><?= e(t('view_job_intro')) ?></p>
        </div>
        <div class="toolbar-actions">
            <a class="btn btn-outline" href="<?= url('employer_jobs') ?>"><?= e(t('back')) ?></a>
            <a class="btn btn-primary" href="<?= url('employer_job_edit', ['id' => $job['id']]) ?>"><?= e(t('edit_job')) ?></a>
        </div>
    </div>

    <section class="card section-card">
        <div class="detail-grid">
            <div class="detail-item">
                <span><?= e(t('job_title')) ?></span>
                <strong><?= e($job['job_title_name']) ?></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('job_category')) ?></span>
                <strong><?= e($job['job_category_name']) ?></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('employment_type')) ?></span>
                <strong><?= e($job['employment_type_name']) ?></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('industry')) ?></span>
                <strong><?= e($job['industry_name']) ?></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('job_level')) ?></span>
                <strong><?= e($job['job_level_name']) ?></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('number_of_openings')) ?></span>
                <strong><?= e((string)$job['number_of_openings']) ?></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('location')) ?></span>
                <strong><?= e(trim($job['district_name'] ? $job['district_name'] . ', ' : '') . $job['city_name'] . ', ' . $job['country_name']) ?></strong>
                <small><?= e($job['work_arrangement_name']) ?></small>
            </div>
            <div class="detail-item">
                <span><?= e(t('salary_range')) ?></span>
                <strong><?= e($job['salary_range_label']) ?></strong>
                <small><?= e($job['salary_type_name']) ?></small>
            </div>
            <div class="detail-item">
                <span><?= e(t('minimum_degree_level')) ?></span>
                <strong><?= e($job['degree_level_name']) ?></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('minimum_years_of_experience')) ?></span>
                <strong><?= e($job['experience_level_name']) ?></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('status')) ?></span>
                <strong><span class="status-badge status-<?= e($job['status']) ?>"><?= e(t($job['status'])) ?></span></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('created_date')) ?></span>
                <strong><?= e(date('Y-m-d H:i', strtotime($job['created_at']))) ?></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('updated_date')) ?></span>
                <strong><?= e(date('Y-m-d H:i', strtotime($job['updated_at']))) ?></strong>
            </div>
        </div>
    </section>

    <section class="card section-card">
        <div class="detail-section">
            <h3><?= e(t('benefits')) ?></h3>
            <p><?= nl2br(e($job['benefits'])) ?></p>
        </div>
        <div class="detail-section">
            <h3><?= e(t('responsibilities')) ?></h3>
            <p><?= nl2br(e($job['responsibilities'])) ?></p>
        </div>
        <div class="detail-section">
            <h3><?= e(t('required_qualifications')) ?></h3>
            <p><?= nl2br(e($job['required_qualifications'])) ?></p>
        </div>
        <div class="detail-section">
            <h3><?= e(t('preferred_skills')) ?></h3>
            <p><?= nl2br(e($job['preferred_skills'])) ?></p>
        </div>
        <div class="detail-section">
            <h3><?= e(t('additional_notes')) ?></h3>
            <p><?= nl2br(e($job['additional_notes'])) ?></p>
        </div>
        <div class="detail-section">
            <h3><?= e(t('required_skills')) ?></h3>
            <?php if (empty($skills)): ?>
                <p><?= e(t('no_skills_added')) ?></p>
            <?php else: ?>
                <div class="skill-pill-wrap">
                    <?php foreach ($skills as $skill): ?>
                        <span class="skill-pill"><?= e($skill['skill_name']) ?> <small><?= e($skill['proficiency_name']) ?></small></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <div class="form-actions">
        <a class="btn btn-outline" href="<?= url('employer_jobs') ?>"><?= e(t('back_to_my_job_postings')) ?></a>
        <form action="<?= url('employer_job_toggle_status', ['id' => $job['id']]) ?>" method="POST">
            <input type="hidden" name="return_page" value="employer_job_view">
            <button class="btn <?= $job['status'] === 'active' ? 'btn-warning' : 'btn-outline' ?>" type="submit"><?= e($job['status'] === 'active' ? t('deactivate') : t('activate')) ?></button>
        </form>
        <form action="<?= url('employer_job_delete', ['id' => $job['id']]) ?>" method="POST" onsubmit="return confirm('<?= e(t('delete_job_confirm')) ?>');">
            <button class="btn btn-danger" type="submit"><?= e(t('delete')) ?></button>
        </form>
    </div>
</section>
