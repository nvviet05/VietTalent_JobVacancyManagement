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
