<form class="filter-form" action="<?= url('jobs') ?>" method="get" id="filterForm">
    <input type="hidden" name="page" value="jobs">
    <input type="hidden" name="sort" value="<?= e($sort ?? 'newest') ?>" id="filterSort">

    <div class="filter-header">
        <h3><?= e(t('filters')) ?></h3>
        <?php
            $hasActiveFilter = false;
            foreach ($filters as $v) {
                if ($v !== '') { $hasActiveFilter = true; break; }
            }
        ?>
        <?php if ($hasActiveFilter): ?>
            <a class="filter-clear" href="<?= url('jobs') ?>"><?= e(t('clear_filters')) ?></a>
        <?php endif; ?>
    </div>

    <div class="filter-group">
        <label for="filter_category"><?= e(t('job_category')) ?></label>
        <select name="category_id" id="filter_category">
            <option value=""><?= e(t('all_categories')) ?></option>
            <?php foreach (($categories ?? []) as $opt): ?>
                <option value="<?= (int)$opt['id'] ?>" <?= ($filters['category_id'] ?? '') == $opt['id'] ? 'selected' : '' ?>><?= e($opt['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-group">
        <label for="filter_country"><?= e(t('country')) ?></label>
        <select name="country_id" id="filter_country" data-filter-country>
            <option value=""><?= e(t('all_countries_filter')) ?></option>
            <?php foreach (($countries ?? []) as $opt): ?>
                <option value="<?= (int)$opt['id'] ?>" <?= ($filters['country_id'] ?? '') == $opt['id'] ? 'selected' : '' ?>><?= e($opt['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-group">
        <label for="filter_city"><?= e(t('city_province')) ?></label>
        <select name="city_id" id="filter_city" data-filter-city>
            <option value=""><?= e(t('all_cities_filter')) ?></option>
            <?php foreach (($cities ?? []) as $opt): ?>
                <option value="<?= (int)$opt['id'] ?>" data-country="<?= (int)$opt['country_id'] ?>" <?= ($filters['city_id'] ?? '') == $opt['id'] ? 'selected' : '' ?>><?= e($opt['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-group">
        <label for="filter_district"><?= e(t('district')) ?></label>
        <select name="district_id" id="filter_district" data-filter-district>
            <option value=""><?= e(t('all_districts_filter')) ?></option>
            <?php foreach (($districts ?? []) as $opt): ?>
                <option value="<?= (int)$opt['id'] ?>" data-city="<?= (int)$opt['city_id'] ?>" <?= ($filters['district_id'] ?? '') == $opt['id'] ? 'selected' : '' ?>><?= e($opt['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-group">
        <label for="filter_employment_type"><?= e(t('employment_type')) ?></label>
        <select name="employment_type_id" id="filter_employment_type">
            <option value=""><?= e(t('all_types')) ?></option>
            <?php foreach (($employmentTypes ?? []) as $opt): ?>
                <option value="<?= (int)$opt['id'] ?>" <?= ($filters['employment_type_id'] ?? '') == $opt['id'] ? 'selected' : '' ?>><?= e($opt['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-group">
        <label for="filter_job_level"><?= e(t('job_level')) ?></label>
        <select name="job_level_id" id="filter_job_level">
            <option value=""><?= e(t('all_levels')) ?></option>
            <?php foreach (($jobLevels ?? []) as $opt): ?>
                <option value="<?= (int)$opt['id'] ?>" <?= ($filters['job_level_id'] ?? '') == $opt['id'] ? 'selected' : '' ?>><?= e($opt['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-group">
        <label for="filter_salary_range"><?= e(t('salary_range')) ?></label>
        <select name="salary_range_id" id="filter_salary_range">
            <option value=""><?= e(t('all_salaries')) ?></option>
            <?php foreach (($salaryRanges ?? []) as $opt): ?>
                <option value="<?= (int)$opt['id'] ?>" <?= ($filters['salary_range_id'] ?? '') == $opt['id'] ? 'selected' : '' ?>><?= e($opt['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-group">
        <label for="filter_work_arrangement"><?= e(t('work_arrangement')) ?></label>
        <select name="work_arrangement_id" id="filter_work_arrangement">
            <option value=""><?= e(t('all_arrangements')) ?></option>
            <?php foreach (($workArrangements ?? []) as $opt): ?>
                <option value="<?= (int)$opt['id'] ?>" <?= ($filters['work_arrangement_id'] ?? '') == $opt['id'] ? 'selected' : '' ?>><?= e($opt['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-group">
        <label for="filter_skill"><?= e(t('required_skills')) ?></label>
        <select name="skill_id" id="filter_skill">
            <option value=""><?= e(t('all_skills')) ?></option>
            <?php foreach (($skills ?? []) as $opt): ?>
                <option value="<?= (int)$opt['id'] ?>" <?= ($filters['skill_id'] ?? '') == $opt['id'] ? 'selected' : '' ?>><?= e($opt['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary btn-block filter-apply"><?= e(t('apply_filters')) ?></button>
</form>
