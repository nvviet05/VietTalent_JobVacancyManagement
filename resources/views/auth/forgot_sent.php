<section class="section">
    <div class="container" style="max-width: 720px;">
        <div class="card" style="padding: 2rem;">
            <h1><?= e(t('reset_link_sent')) ?></h1>
            <p><?= e(t('reset_link_sent_intro')) ?> <strong><?= e($email ?? '') ?></strong></p>

            <?php if (!empty($resetUrl)): ?>
                <div class="card" style="background: #fff7e6; border: 1px solid #f5c54e; padding: 1rem; margin-top: 1rem;">
                    <p style="margin: 0 0 0.5rem;">
                        <strong><?= e(t('demo_mode')) ?>:</strong> <?= e(t('demo_reset_note')) ?>
                    </p>
                    <p style="word-break: break-all;">
                        <a href="<?= e($resetUrl) ?>"><?= e($resetUrl) ?></a>
                    </p>
                </div>
            <?php endif; ?>

            <p style="margin-top: 1.5rem;">
                <a class="btn btn-outline" href="<?= url('login') ?>"><?= e(t('back_to_login')) ?></a>
            </p>
        </div>
    </div>
</section>
