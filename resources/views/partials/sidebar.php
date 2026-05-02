<aside class="dashboard-sidebar" id="dashboardSidebar">
    <div class="sidebar-header">
        <?php
        $logoShowTagline = false;
        $logoCompact = true;
        include APP_ROOT . '/resources/views/partials/brand-logo.php';
        ?>
    </div>
    <nav class="sidebar-nav">
        <?php if (($role ?? '') === 'admin'): ?>
            <a class="<?= ($activeNav ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= url('admin_dashboard') ?>"><?= e(t('admin_dashboard')) ?></a>
            <span><?= e(t('admin_quick_links')) ?></span>
            <a class="<?= ($activeNav ?? '') === 'jobs' ? 'active' : '' ?>" href="<?= url('admin_jobs') ?>"><?= e(t('manage_job_postings')) ?></a>
            <a class="<?= ($activeLookupType ?? '') === 'job_categories' ? 'active' : '' ?>" href="<?= url('admin_lookup', ['type' => 'job_categories']) ?>"><?= e(t('manage_job_categories')) ?></a>
            <a class="<?= ($activeLookupType ?? '') === 'job_titles' ? 'active' : '' ?>" href="<?= url('admin_lookup', ['type' => 'job_titles']) ?>"><?= e(t('manage_job_titles')) ?></a>
            <a class="<?= ($activeLookupType ?? '') === 'skills' ? 'active' : '' ?>" href="<?= url('admin_lookup', ['type' => 'skills']) ?>"><?= e(t('manage_skills')) ?></a>
            <a class="<?= ($activeLookupType ?? '') === 'industries' ? 'active' : '' ?>" href="<?= url('admin_lookup', ['type' => 'industries']) ?>"><?= e(t('manage_industries')) ?></a>
            <a href="<?= url('admin_locations') ?>"><?= e(t('manage_locations')) ?></a>
            <a class="<?= ($activeLookupType ?? '') === 'employment_types' ? 'active' : '' ?>" href="<?= url('admin_lookup', ['type' => 'employment_types']) ?>"><?= e(t('manage_employment_types')) ?></a>
            <a class="<?= ($activeLookupType ?? '') === 'job_levels' ? 'active' : '' ?>" href="<?= url('admin_lookup', ['type' => 'job_levels']) ?>"><?= e(t('manage_job_levels')) ?></a>
            <a class="<?= ($activeLookupType ?? '') === 'salary_ranges' ? 'active' : '' ?>" href="<?= url('admin_lookup', ['type' => 'salary_ranges']) ?>"><?= e(t('manage_salary_ranges')) ?></a>
            <span><?= e(t('admin_phase_note')) ?></span>
        <?php elseif (($role ?? '') === 'employer'): ?>
            <a class="<?= ($activeNav ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= url('employer_dashboard') ?>"><?= e(t('employer_dashboard')) ?></a>
            <a class="<?= ($activeNav ?? '') === 'jobs' ? 'active' : '' ?>" href="<?= url('employer_jobs') ?>"><?= e(t('my_job_postings')) ?></a>
            <a class="<?= ($activeNav ?? '') === 'create_job' ? 'active' : '' ?>" href="<?= url('employer_job_create') ?>"><?= e(t('create_new_job')) ?></a>
        <?php else: ?>
            <a class="<?= ($activeNav ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= url('job_seeker_dashboard') ?>"><?= e(t('job_seeker_dashboard')) ?></a>
            <span><?= e(t('job_seeker_phase_note')) ?></span>
        <?php endif; ?>
        <a href="<?= url('home') ?>"><?= e(t('back_to_site')) ?></a>
    </nav>
</aside>
