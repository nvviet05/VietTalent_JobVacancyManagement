<section class="mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3">
        <div>
            <h1 class="h2 fw-bold mb-2">Browse Active Jobs</h1>
            <p class="text-muted mb-0">Search and filter active vacancies across employers.</p>
        </div>
        <div class="text-muted small">
            <?= count($jobs ?? []) ?> result(s)
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-4 col-xl-3">
        <?php include APP_ROOT . '/resources/views/partials/filter-sidebar.php'; ?>
    </div>
    <div class="col-lg-8 col-xl-9">
        <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <?php include APP_ROOT . '/resources/views/partials/job-card.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card border-0 shadow-sm p-4">
                <h2 class="h5 fw-bold mb-2">No active jobs matched your filters.</h2>
                <p class="text-muted mb-3">Try clearing some filters or searching with a broader keyword.</p>
                <a class="btn btn-outline-primary" href="<?= url('jobs') ?>">Clear Filters</a>
            </div>
        <?php endif; ?>
    </div>
</div>
