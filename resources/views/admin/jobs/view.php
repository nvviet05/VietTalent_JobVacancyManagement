<section class="dashboard-stack">
    <div class="section-toolbar">
        <div>
            <h2><?= e($job['job_title_name']) ?></h2>
            <p><?= e(t('admin_job_detail_intro')) ?></p>
        </div>
        <div class="toolbar-actions">
            <a class="btn btn-outline" href="<?= url('admin_jobs') ?>"><?= e(t('back_to_admin_jobs')) ?></a>
        </div>
    </div>

    <section class="card section-card">
        <div class="detail-grid">
            <div class="detail-item">
                <span><?= e(t('job_title')) ?></span>
                <strong><?= e($job['job_title_name']) ?></strong>
            </div>
            <div class="detail-item">
                <span><?= e(t('company')) ?></span>
                <strong><?= e($job['company_name']) ?></strong>
                <small><?= e($job['employer_name']) ?></small>
            </div>
            <div class="detail-item">
                <span><?= e(t('employer_email')) ?></span>
                <strong><?= e($job['employer_email']) ?></strong>
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
            <div class="detail-item">
                <span><?= e(t('company_website')) ?></span>
                <strong><?= e($job['company_website']) ?></strong>
            </div>
        </div>
    </section>

    <section class="card section-card">
        <div class="detail-section">
            <h3><?= e(t('company_description')) ?></h3>
            <p><?= nl2br(e($job['company_description'])) ?></p>
        </div>
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
        <a class="btn btn-outline" href="<?= url('admin_jobs') ?>"><?= e(t('back_to_admin_jobs')) ?></a>
        <a class="btn btn-outline" href="<?= url('admin_job_set_status', ['id' => $job['id'], 'status' => 'active', 'return_page' => 'admin_job_view']) ?>"><?= e(t('set_active')) ?></a>
        <a class="btn btn-outline" href="<?= url('admin_job_set_status', ['id' => $job['id'], 'status' => 'inactive', 'return_page' => 'admin_job_view']) ?>"><?= e(t('set_inactive')) ?></a>
        <a class="btn btn-danger" href="<?= url('admin_job_set_status', ['id' => $job['id'], 'status' => 'removed', 'return_page' => 'admin_job_view']) ?>" onclick="return confirm('<?= e(t('remove_job_confirm')) ?>');"><?= e(t('remove')) ?></a>
    </div>
</section>
