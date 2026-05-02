<div class="card p-4 bg-light border-0 shadow-sm rounded">
    <h5 class="mb-4 fw-bold">Filter Jobs</h5>
    <form action="<?= url('jobs') ?>" method="GET">
        <input type="hidden" name="page" value="jobs">
        <input type="hidden" name="url" value="jobs">
        <div class="mb-3">
            <label class="form-label small fw-bold">Keyword</label>
            <input type="text" name="keyword" class="form-control" value="<?= e($_GET['keyword'] ?? '') ?>" placeholder="Search...">
        </div>
        <div class="mb-3">
            <label class="form-label small fw-bold">Category</label>
            <select name="category_id" class="form-select">
                <option value="">All Categories</option>
                <?php foreach ($categories ?? [] as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($_GET['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-bold"><?= e(t('country')) ?></label>
            <select name="country_id" class="form-select">
                <option value=""><?= e(t('all_countries')) ?></option>
                <?php foreach ($countries ?? [] as $country): ?>
                    <option value="<?= $country['id'] ?>" <?= ($_GET['country_id'] ?? '') == $country['id'] ? 'selected' : '' ?>><?= e($country['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-bold">City</label>
            <select name="city_id" class="form-select">
                <option value="">All Cities</option>
                <?php foreach ($cities ?? [] as $city): ?>
                    <option value="<?= $city['id'] ?>" <?= ($_GET['city_id'] ?? '') == $city['id'] ? 'selected' : '' ?>><?= e($city['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-bold">Sort By</label>
            <select name="sort" class="form-select">
                <option value="newest" <?= ($_GET['sort'] ?? '') == 'newest' ? 'selected' : '' ?>>Newest First</option>
                <option value="salary_desc" <?= ($_GET['sort'] ?? '') == 'salary_desc' ? 'selected' : '' ?>>Salary: High to Low</option>
                <option value="salary_asc" <?= ($_GET['sort'] ?? '') == 'salary_asc' ? 'selected' : '' ?>>Salary: Low to High</option>
                <option value="title_asc" <?= ($_GET['sort'] ?? '') == 'title_asc' ? 'selected' : '' ?>>Title: A-Z</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-2">Apply Filters</button>
        <a href="<?= url('jobs') ?>" class="btn btn-link w-100 text-decoration-none mt-2 text-muted">Clear Filters</a>
    </form>
</div>
