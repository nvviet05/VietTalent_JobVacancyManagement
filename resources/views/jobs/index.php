<section class="jobs-search-section">
    <div class="container">
        <form class="jobs-search-form" action="<?= url('jobs') ?>" method="get" data-jobs-search-form>
            <input type="hidden" name="page" value="jobs">
            <div class="jobs-search-bar">
                <label class="visually-hidden" for="jobsKeyword"><?= e(t('search')) ?></label>
                <input id="jobsKeyword" type="text" name="keyword" value="<?= e($filters['keyword'] ?? '') ?>"
                       placeholder="<?= e(t('search_placeholder')) ?>" class="jobs-search-input"
                       autocomplete="off">
                <button type="submit" class="btn btn-primary"><?= e(t('search')) ?></button>
            </div>
            <p class="jobs-search-help" data-ajax-status aria-live="polite"></p>
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
                    <span class="jobs-count" data-jobs-count>
                        <?= (int)$totalResults ?> <?= e(t('results_label')) ?>
                    </span>
                    <div class="jobs-toolbar-right">
                        <label for="sortSelect" class="sort-label"><?= e(t('sort_by')) ?>:</label>
                        <select id="sortSelect" class="sort-select" data-sort-select>
                            <option value="newest"      <?= ($sort ?? '') === 'newest'      ? 'selected' : '' ?>><?= e(t('sort_newest')) ?></option>
                            <option value="salary_asc"  <?= ($sort ?? '') === 'salary_asc'  ? 'selected' : '' ?>><?= e(t('sort_salary_asc')) ?></option>
                            <option value="salary_desc" <?= ($sort ?? '') === 'salary_desc' ? 'selected' : '' ?>><?= e(t('sort_salary_desc')) ?></option>
                            <option value="title_asc"   <?= ($sort ?? '') === 'title_asc'   ? 'selected' : '' ?>><?= e(t('sort_title_az')) ?></option>
                        </select>
                    </div>
                </div>

                <div data-jobs-list>
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

                <?php if (($totalPages ?? 1) > 1): ?>
                    <?php
                        $currentPage = (int)($page ?? 1);
                        $base = $_GET; unset($base['p']);
                        $url = function ($p) use ($base) {
                            $q = $base;
                            $q['p'] = $p;
                            return BASE_URL . '/index.php?' . http_build_query($q);
                        };
                        $window = 2;
                        $start  = max(1, $currentPage - $window);
                        $end    = min($totalPages, $currentPage + $window);
                    ?>
                    <nav class="pagination" aria-label="Pagination" data-pagination>
                        <a class="page-link <?= $currentPage <= 1 ? 'is-disabled' : '' ?>"
                           href="<?= e($url(max(1, $currentPage - 1))) ?>"
                           data-page="<?= max(1, $currentPage - 1) ?>"
                           rel="prev"
                           <?= $currentPage <= 1 ? 'aria-disabled="true"' : '' ?>>
                            &laquo; Prev
                        </a>

                        <?php if ($start > 1): ?>
                            <a class="page-link" href="<?= e($url(1)) ?>" data-page="1">1</a>
                            <?php if ($start > 2): ?><span class="page-ellipsis">…</span><?php endif; ?>
                        <?php endif; ?>

                        <?php for ($p = $start; $p <= $end; $p++): ?>
                            <a class="page-link <?= $p === $currentPage ? 'is-current' : '' ?>"
                               href="<?= e($url($p)) ?>"
                               data-page="<?= $p ?>"
                               <?= $p === $currentPage ? 'aria-current="page"' : '' ?>>
                                <?= $p ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($end < $totalPages): ?>
                            <?php if ($end < $totalPages - 1): ?><span class="page-ellipsis">…</span><?php endif; ?>
                            <a class="page-link" href="<?= e($url($totalPages)) ?>" data-page="<?= $totalPages ?>"><?= $totalPages ?></a>
                        <?php endif; ?>

                        <a class="page-link <?= $currentPage >= $totalPages ? 'is-disabled' : '' ?>"
                           href="<?= e($url(min($totalPages, $currentPage + 1))) ?>"
                           data-page="<?= min($totalPages, $currentPage + 1) ?>"
                           rel="next"
                           <?= $currentPage >= $totalPages ? 'aria-disabled="true"' : '' ?>>
                            Next &raquo;
                        </a>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    // Expose state needed by AJAX search to the JS file (filters.js).
    window.JOBS_SEARCH_ENDPOINT = "<?= e(url('jobs_search')) ?>";
    window.JOBS_RESULTS_LABEL   = "<?= e(t('results_label')) ?>";
    window.JOBS_NO_RESULTS_HTML = <?= json_encode('<div class="jobs-empty card"><h2>' . e(t('no_jobs_found')) . '</h2><p>' . e(t('no_jobs_found_text')) . '</p><a class="btn btn-outline" href="' . url('jobs') . '">' . e(t('clear_filters')) . '</a></div>', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
