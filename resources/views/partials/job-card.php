<?php if (!isset($job)) return; ?>
<div class="card border-0 shadow-sm rounded p-4 h-100 mb-3 bg-white">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h3 class="h5 mb-1 fw-bold">
                <a href="<?= url('jobs/detail', ['id' => $job['id']]) ?>" class="text-decoration-none text-dark">
                    <?= e($job['job_title_name'] ?? 'Job Title') ?>
                </a>
            </h3>
            <div class="text-primary mb-2"><?= e($job['company_name'] ?? 'Company') ?></div>
        </div>
        <div class="text-success fw-bold bg-success bg-opacity-10 px-2 py-1 rounded small">
            <?= e($job['salary_range_label'] ?? 'Negotiable') ?>
        </div>
    </div>
    <div class="mb-3 mt-2 small">
        <span class="badge bg-light text-dark border me-1">Location: <?= e($job['city_name'] ?? 'N/A') ?></span>
        <span class="badge bg-light text-dark border me-1">Type: <?= e($job['employment_type_name'] ?? 'N/A') ?></span>
        <span class="badge bg-light text-dark border">Work: <?= e($job['work_arrangement_name'] ?? 'N/A') ?></span>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3 text-muted small">
        <span>Posted: <?= !empty($job['created_at']) ? date('M d, Y', strtotime($job['created_at'])) : 'Recently' ?></span>
        <a href="<?= url('jobs/detail', ['id' => $job['id']]) ?>" class="btn btn-primary btn-sm px-3">Detail &rarr;</a>
    </div>
</div>
