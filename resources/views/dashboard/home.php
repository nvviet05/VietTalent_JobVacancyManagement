<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow"><?= e(t('hero_eyebrow')) ?></span>
            <h1><?= e(t('home_hero_title')) ?></h1>
            <p><?= e(t('home_hero_intro')) ?></p>
            <form class="hero-search-form" action="<?= url('jobs') ?>" method="get">
                <input type="hidden" name="page" value="jobs">
                <div class="hero-search-bar">
                    <input type="text" name="keyword" placeholder="<?= e(t('search_placeholder')) ?>" class="hero-search-input">
                    <button type="submit" class="btn btn-primary"><?= e(t('search')) ?></button>
                </div>
            </form>
        </div>
        <div class="hero-card">
            <h2><?= e(t('home_why_title')) ?></h2>
            <ul class="check-list">
                <li><?= e(t('home_why_1')) ?></li>
                <li><?= e(t('home_why_2')) ?></li>
                <li><?= e(t('home_why_3')) ?></li>
                <li><?= e(t('home_why_4')) ?></li>
                <li><?= e(t('home_why_5')) ?></li>
            </ul>
        </div>
    </div>
</section>

<?php if (!empty($categories)): ?>
<section class="section">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow"><?= e(t('explore')) ?></span>
            <h2><?= e(t('featured_categories')) ?></h2>
        </div>
        <div class="categories-grid">
            <?php foreach (array_slice($categories, 0, 6) as $cat): ?>
                <a class="category-card" href="<?= url('jobs', ['category_id' => $cat['id']]) ?>">
                    <strong><?= e($cat['name']) ?></strong>
                    <span><?= (int)$cat['job_count'] ?> <?= e(t('jobs_count_label')) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($latestJobs)): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow"><?= e(t('new_opportunities')) ?></span>
            <h2><?= e(t('latest_jobs')) ?></h2>
        </div>
        <div class="latest-jobs-grid">
            <?php foreach ($latestJobs as $job): ?>
                <?php $jobSkills = $skillsMap[$job['id']] ?? []; ?>
                <div class="job-card">
                    <div class="job-card-header">
                        <h3><a href="<?= url('job_detail', ['id' => $job['id']]) ?>"><?= e($job['job_title_name']) ?></a></h3>
                        <span class="job-card-company"><?= e($job['company_name']) ?></span>
                    </div>
                    <div class="job-card-meta">
                        <span class="meta-badge"><?= e($job['city_name']) ?></span>
                        <span class="meta-badge meta-salary"><?= e($job['salary_range_label']) ?></span>
                        <span class="meta-badge"><?= e($job['employment_type_name']) ?></span>
                    </div>
                    <?php if (!empty($jobSkills)): ?>
                        <div class="job-card-skills">
                            <?php foreach (array_slice($jobSkills, 0, 3) as $sk): ?>
                                <span class="skill-tag"><?= e($sk['skill_name']) ?></span>
                            <?php endforeach; ?>
                            <?php if (count($jobSkills) > 3): ?>
                                <span class="skill-tag skill-tag-more">+<?= count($jobSkills) - 3 ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="job-card-footer">
                        <span class="job-card-date"><?= e(date('M d, Y', strtotime($job['created_at']))) ?></span>
                        <a class="btn btn-outline btn-sm" href="<?= url('job_detail', ['id' => $job['id']]) ?>"><?= e(t('view_detail')) ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="section-cta">
            <a class="btn btn-primary" href="<?= url('jobs') ?>"><?= e(t('view_all_jobs')) ?></a>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section employer-cta-section">
    <div class="container employer-cta">
        <div>
            <span class="eyebrow"><?= e(t('for_employers')) ?></span>
            <h2><?= e(t('employer_cta_title')) ?></h2>
            <p><?= e(t('employer_cta_text')) ?></p>
        </div>
        <div class="employer-cta-actions">
            <?php if (Auth::check() && Auth::isRole('employer')): ?>
                <a class="btn btn-primary" href="<?= url('employer_job_create') ?>"><?= e(t('create_new_job')) ?></a>
            <?php else: ?>
                <a class="btn btn-primary" href="<?= url('register') ?>"><?= e(t('register_as_employer')) ?></a>
                <a class="btn btn-outline" href="<?= url('login') ?>"><?= e(t('login')) ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>
