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
            <a class="active" href="<?= url('admin_dashboard') ?>"><?= e(t('admin_dashboard')) ?></a>
            <span><?= e(t('admin_phase_note')) ?></span>
        <?php elseif (($role ?? '') === 'employer'): ?>
            <a class="active" href="<?= url('employer_dashboard') ?>"><?= e(t('employer_dashboard')) ?></a>
            <span><?= e(t('employer_phase_note')) ?></span>
        <?php else: ?>
            <a class="active" href="<?= url('job_seeker_dashboard') ?>"><?= e(t('job_seeker_dashboard')) ?></a>
            <span><?= e(t('job_seeker_phase_note')) ?></span>
        <?php endif; ?>
        <a href="<?= url('home') ?>"><?= e(t('back_to_site')) ?></a>
    </nav>
</aside>
