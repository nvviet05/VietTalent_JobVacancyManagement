<div class="job-card">
    <div class="job-card-header">
        <h3><a href="<?= url('job_detail', ['id' => $job['id']]) ?>"><?= e($job['job_title_name']) ?></a></h3>
        <span class="job-card-company"><?= e($job['company_name']) ?></span>
    </div>
    <div class="job-card-meta">
        <span class="meta-badge"><?= e($job['city_name']) ?><?= !empty($job['district_name']) ? ', ' . e($job['district_name']) : '' ?></span>
        <span class="meta-badge meta-salary"><?= e($job['salary_range_label']) ?></span>
        <span class="meta-badge"><?= e($job['employment_type_name']) ?></span>
        <span class="meta-badge"><?= e($job['work_arrangement_name']) ?></span>
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
