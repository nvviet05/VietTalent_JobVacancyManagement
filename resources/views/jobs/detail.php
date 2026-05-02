<section class="section">
    <div class="container">
        <a class="btn btn-outline btn-sm" href="<?= url('jobs') ?>">&larr; <?= e(t('back_to_jobs')) ?></a>

        <div class="job-detail-layout">
            <div class="job-detail-main">
                <div class="card job-detail-card">
                    <div class="job-detail-header">
                        <div>
                            <h1><?= e($job['job_title_name'] ?? '') ?></h1>
                            <span class="job-detail-company"><?= e($job['company_name'] ?? '') ?></span>
                        </div>
                        <div class="job-detail-salary"><?= e($job['salary_range_label'] ?? t('negotiable')) ?></div>
                    </div>

                    <div class="job-detail-badges">
                        <span class="meta-badge"><strong><?= e(t('location')) ?>:</strong> <?= e($job['city_name'] ?? '') ?><?= !empty($job['district_name']) ? ', ' . e($job['district_name']) : '' ?>, <?= e($job['country_name'] ?? '') ?></span>
                        <span class="meta-badge"><strong><?= e(t('employment_type')) ?>:</strong> <?= e($job['employment_type_name'] ?? '') ?></span>
                        <span class="meta-badge"><strong><?= e(t('work_arrangement')) ?>:</strong> <?= e($job['work_arrangement_name'] ?? '') ?></span>
                        <span class="meta-badge"><strong><?= e(t('job_level')) ?>:</strong> <?= e($job['job_level_name'] ?? '') ?></span>
                        <span class="meta-badge"><strong><?= e(t('job_category')) ?>:</strong> <?= e($job['job_category_name'] ?? '') ?></span>
                        <span class="meta-badge"><strong><?= e(t('industry')) ?>:</strong> <?= e($job['industry_name'] ?? '') ?></span>
                    </div>

                    <?php if (!empty($skills)): ?>
                        <div class="job-detail-section">
                            <h2><?= e(t('required_skills')) ?></h2>
                            <div class="skill-pill-wrap">
                                <?php foreach ($skills as $sk): ?>
                                    <span class="skill-pill"><?= e($sk['skill_name']) ?> <small>(<?= e($sk['proficiency_name']) ?>)</small></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="job-detail-section">
                        <h2><?= e(t('responsibilities')) ?></h2>
                        <div class="job-detail-text"><?= nl2br(e($job['responsibilities'] ?? '')) ?></div>
                    </div>

                    <div class="job-detail-section">
                        <h2><?= e(t('required_qualifications')) ?></h2>
                        <div class="job-detail-text"><?= nl2br(e($job['required_qualifications'] ?? '')) ?></div>
                    </div>

                    <?php if (!empty($job['preferred_skills'])): ?>
                        <div class="job-detail-section">
                            <h2><?= e(t('preferred_skills')) ?></h2>
                            <div class="job-detail-text"><?= nl2br(e($job['preferred_skills'])) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($job['benefits'])): ?>
                        <div class="job-detail-section">
                            <h2><?= e(t('benefits')) ?></h2>
                            <div class="job-detail-text"><?= nl2br(e($job['benefits'])) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($job['additional_notes'])): ?>
                        <div class="job-detail-section">
                            <h2><?= e(t('additional_notes')) ?></h2>
                            <div class="job-detail-text"><?= nl2br(e($job['additional_notes'])) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <aside class="job-detail-sidebar">
                <div class="card job-sidebar-card">
                    <h2><?= e(t('company')) ?></h2>
                    <p class="job-sidebar-company"><?= e($job['company_name'] ?? '') ?></p>
                    <?php if (!empty($job['company_description'])): ?>
                        <p class="job-sidebar-desc"><?= nl2br(e($job['company_description'])) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($job['company_website'])): ?>
                        <a class="btn btn-outline btn-sm" href="<?= e($job['company_website']) ?>" target="_blank" rel="noopener noreferrer"><?= e(t('visit_website')) ?></a>
                    <?php endif; ?>
                </div>

                <div class="card job-sidebar-card">
                    <h2><?= e(t('job_summary')) ?></h2>
                    <dl class="job-summary-list">
                        <dt><?= e(t('posted_date')) ?></dt>
                        <dd><?= !empty($job['created_at']) ? e(date('M d, Y', strtotime($job['created_at']))) : 'N/A' ?></dd>

                        <dt><?= e(t('number_of_openings')) ?></dt>
                        <dd><?= e((string)($job['number_of_openings'] ?? 'N/A')) ?></dd>

                        <dt><?= e(t('employment_type')) ?></dt>
                        <dd><?= e($job['employment_type_name'] ?? 'N/A') ?></dd>

                        <dt><?= e(t('work_arrangement')) ?></dt>
                        <dd><?= e($job['work_arrangement_name'] ?? 'N/A') ?></dd>

                        <dt><?= e(t('job_level')) ?></dt>
                        <dd><?= e($job['job_level_name'] ?? 'N/A') ?></dd>

                        <dt><?= e(t('salary_type')) ?></dt>
                        <dd><?= e($job['salary_type_name'] ?? 'N/A') ?></dd>

                        <dt><?= e(t('minimum_degree_level')) ?></dt>
                        <dd><?= e($job['degree_level_name'] ?? 'N/A') ?></dd>

                        <dt><?= e(t('minimum_years_of_experience')) ?></dt>
                        <dd><?= e($job['experience_level_name'] ?? 'N/A') ?></dd>
                    </dl>
                </div>
            </aside>
        </div>
    </div>
</section>
