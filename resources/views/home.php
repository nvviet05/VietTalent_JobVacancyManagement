<section class="hero-section bg-primary text-white py-5 mb-5 text-center">
    <div class="container py-5">
        <h1 class="display-4 fw-bold mb-3">Find Your Dream Job Today</h1>
        <p class="lead mb-4">Search through thousands of active listings from top employers.</p>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form action="<?= url('jobs') ?>" method="GET" class="d-flex bg-white p-2 rounded shadow-sm">
    
                <input type="hidden" name="page" value="jobs">
                <input type="hidden" name="url" value="jobs">
                
                <input type="text" name="keyword" class="form-control border-0 shadow-none" placeholder="Job title or company...">
                <button type="submit" class="btn btn-dark px-4">Search</button>
            </form>
            </div>
        </div>
    </div>
</section>

<section class="container pb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h2 class="h3 fw-bold mb-0">Latest Active Jobs</h2>
        <a href="<?= url('jobs') ?>" class="btn btn-outline-primary">View All Jobs &rarr;</a>
    </div>
    <div class="row">
        <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <?php include APP_ROOT . '/resources/views/partials/job-card.php'; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12"><p>No active jobs found.</p></div>
        <?php endif; ?>
    </div>
</section>