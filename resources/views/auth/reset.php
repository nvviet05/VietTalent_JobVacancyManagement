<section class="section">
    <div class="container" style="max-width: 540px;">
        <div class="card" style="padding: 2rem;">
            <h1><?= e(t('reset_password_title')) ?></h1>
            <p><?= e(t('reset_password_intro')) ?> <strong><?= e($email ?? '') ?></strong></p>

            <?php include APP_ROOT . '/resources/views/partials/flash-message.php'; ?>

            <form action="<?= url('reset_password_submit') ?>" method="POST" style="margin-top: 1rem;">
                <input type="hidden" name="token" value="<?= e($token ?? '') ?>">
                <div class="form-group">
                    <label for="password"><?= e(t('new_password')) ?></label>
                    <input id="password" type="password" name="password" minlength="6" required>
                    <?php if ($error = validationError('password')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password_confirm"><?= e(t('confirm_new_password')) ?></label>
                    <input id="password_confirm" type="password" name="password_confirm" minlength="6" required>
                    <?php if ($error = validationError('password_confirm')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <button class="btn btn-primary btn-block" type="submit"><?= e(t('reset_password_action')) ?></button>
            </form>

            <p class="auth-switch" style="margin-top: 1rem;">
                <a href="<?= url('login') ?>"><?= e(t('back_to_login')) ?></a>
            </p>
        </div>
    </div>
</section>
