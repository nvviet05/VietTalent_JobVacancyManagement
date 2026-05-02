<section class="mb-4">
    <a class="btn btn-outline-secondary btn-sm" href="<?= url('jobs') ?>">&larr; Back to Jobs</a>
</section>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-3">
                <div>
                    <h1 class="h2 fw-bold mb-2"><?= e($job['job_title_name'] ?? 'Job Detail') ?></h1>
                    <div class="text-primary fw-semibold"><?= e($job['company_name'] ?? 'Company') ?></div>
                </div>
                <div class="text-success fw-bold bg-success bg-opacity-10 px-3 py-2 rounded">
                    <?= e($job['salary_range_label'] ?? 'Negotiable') ?>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="badge bg-light text-dark border">Location: <?= e($job['city_name'] ?? 'N/A') ?></span>
                <span class="badge bg-light text-dark border">Type: <?= e($job['employment_type_name'] ?? 'N/A') ?></span>
                <span class="badge bg-light text-dark border">Work: <?= e($job['work_arrangement_name'] ?? 'N/A') ?></span>
                <span class="badge bg-light text-dark border">Level: <?= e($job['job_level_name'] ?? 'N/A') ?></span>
            </div>

            <div class="mb-4">
                <h2 class="h5 fw-bold">Responsibilities</h2>
                <div class="text-muted"><?= nl2br(e($job['responsibilities'] ?? '')) ?></div>
            </div>

            <div class="mb-4">
                <h2 class="h5 fw-bold">Required Qualifications</h2>
                <div class="text-muted"><?= nl2br(e($job['required_qualifications'] ?? '')) ?></div>
            </div>

            <?php if (!empty($job['preferred_skills'])): ?>
                <div class="mb-4">
                    <h2 class="h5 fw-bold">Preferred Skills</h2>
                    <div class="text-muted"><?= nl2br(e($job['preferred_skills'])) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($job['benefits'])): ?>
                <div class="mb-4">
                    <h2 class="h5 fw-bold">Benefits</h2>
                    <div class="text-muted"><?= nl2br(e($job['benefits'])) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($job['additional_notes'])): ?>
                <div>
                    <h2 class="h5 fw-bold">Additional Notes</h2>
                    <div class="text-muted"><?= nl2br(e($job['additional_notes'])) ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h2 class="h5 fw-bold mb-3">Company</h2>
            <p class="fw-semibold mb-2"><?= e($job['company_name'] ?? 'Company') ?></p>
            <?php if (!empty($job['company_description'])): ?>
                <p class="text-muted small mb-3"><?= nl2br(e($job['company_description'])) ?></p>
            <?php endif; ?>
            <?php if (!empty($job['company_website'])): ?>
                <a class="btn btn-outline-primary btn-sm" href="<?= e($job['company_website']) ?>" target="_blank" rel="noopener noreferrer">Visit Website</a>
            <?php endif; ?>
        </div>

        <div class="card border-0 shadow-sm p-4">
            <h2 class="h5 fw-bold mb-3">Job Summary</h2>
            <dl class="mb-0">
                <dt class="small text-muted">Posted Date</dt>
                <dd class="mb-3"><?= !empty($job['created_at']) ? e(date('Y-m-d', strtotime($job['created_at']))) : 'N/A' ?></dd>
                <dt class="small text-muted">Openings</dt>
                <dd class="mb-3"><?= e((string)($job['number_of_openings'] ?? 'N/A')) ?></dd>
                <dt class="small text-muted">Employment Type</dt>
                <dd class="mb-3"><?= e($job['employment_type_name'] ?? 'N/A') ?></dd>
                <dt class="small text-muted">Work Arrangement</dt>
                <dd class="mb-3"><?= e($job['work_arrangement_name'] ?? 'N/A') ?></dd>
                <dt class="small text-muted">Job Level</dt>
                <dd class="mb-0"><?= e($job['job_level_name'] ?? 'N/A') ?></dd>
            </dl>
        </div>
    </div>
</div>
