<section class="jobs-search-section">
    <div class="container">
        <form class="jobs-search-form" action="<?= url('jobs') ?>" method="get">
            <input type="hidden" name="page" value="jobs">
            <div class="jobs-search-bar">
                <input type="text" name="keyword" value="<?= e($filters['keyword'] ?? '') ?>" placeholder="<?= e(t('search_placeholder')) ?>" class="jobs-search-input">
                <button type="submit" class="btn btn-primary"><?= e(t('search')) ?></button>
            </div>
        </form>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="jobs-layout">
            <aside class="jobs-sidebar" id="filterSidebar">
                <?php include APP_ROOT . '/resources/views/partials/filter-sidebar.php'; ?>
            </aside>
            <div class="jobs-main">
                <div class="jobs-toolbar">
                    <span class="jobs-count"><?= count($jobs) ?> <?= e(t('results_label')) ?></span>
                    <div class="jobs-toolbar-right">
                        <label for="sortSelect" class="sort-label"><?= e(t('sort_by')) ?>:</label>
                        <select id="sortSelect" class="sort-select" data-sort-select>
                            <option value="newest" <?= ($sort ?? '') === 'newest' ? 'selected' : '' ?>><?= e(t('sort_newest')) ?></option>
                            <option value="salary_asc" <?= ($sort ?? '') === 'salary_asc' ? 'selected' : '' ?>><?= e(t('sort_salary_asc')) ?></option>
                            <option value="salary_desc" <?= ($sort ?? '') === 'salary_desc' ? 'selected' : '' ?>><?= e(t('sort_salary_desc')) ?></option>
                            <option value="title_asc" <?= ($sort ?? '') === 'title_asc' ? 'selected' : '' ?>><?= e(t('sort_title_az')) ?></option>
                        </select>
                    </div>
                </div>

                <?php if (!empty($jobs)): ?>
                    <div class="jobs-list">
                        <?php foreach ($jobs as $job): ?>
                            <?php $jobSkills = $skillsMap[$job['id']] ?? []; ?>
                            <?php include APP_ROOT . '/resources/views/partials/job-card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="jobs-empty card">
                        <h2><?= e(t('no_jobs_found')) ?></h2>
                        <p><?= e(t('no_jobs_found_text')) ?></p>
                        <a class="btn btn-outline" href="<?= url('jobs') ?>"><?= e(t('clear_filters')) ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
