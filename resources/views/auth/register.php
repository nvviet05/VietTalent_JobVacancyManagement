<!DOCTYPE html>
<html lang="<?= e(currentLang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(t('register')) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/auth.css') ?>">
</head>
<body class="auth-body">
    <main class="auth-shell">
        <section class="auth-panel">
            <div class="auth-panel-top">
                <?php include APP_ROOT . '/resources/views/partials/brand-logo.php'; ?>
                <?php include APP_ROOT . '/resources/views/partials/language-switcher.php'; ?>
            </div>
            <h1><?= e(t('create_your_account')) ?></h1>
            <p><?= e(t('register_intro')) ?></p>
            <ul class="check-list">
                <li><?= e(t('company_validation')) ?></li>
                <li><?= e(t('email_unique')) ?></li>
                <li><?= e(t('password_hashing')) ?></li>
            </ul>
        </section>
        <section class="auth-card">
            <h2><?= e(t('register')) ?></h2>
            <?php include APP_ROOT . '/resources/views/partials/flash-message.php'; ?>
            <form action="<?= url('register_submit') ?>" method="POST">
                <div class="form-group">
                    <label><?= e(t('account_type')) ?></label>
                    <div class="role-grid">
                        <label>
                            <input type="radio" name="role" value="employer" data-role-input <?= old('role') === 'employer' ? 'checked' : '' ?> required>
                            <span><?= e(t('employer')) ?></span>
                        </label>
                        <label>
                            <input type="radio" name="role" value="job_seeker" data-role-input <?= old('role') === 'job_seeker' ? 'checked' : '' ?> required>
                            <span><?= e(t('job_seeker')) ?></span>
                        </label>
                    </div>
                    <?php if ($error = validationError('role')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="full_name"><?= e(t('full_name')) ?></label>
                    <input id="full_name" type="text" name="full_name" value="<?= old('full_name') ?>" required>
                    <?php if ($error = validationError('full_name')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="email"><?= e(t('email')) ?></label>
                    <input id="email" type="email" name="email" value="<?= old('email') ?>" required>
                    <?php if ($error = validationError('email')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group <?= old('role') === 'employer' ? '' : 'is-hidden' ?>" id="companyField">
                    <label for="company_name"><?= e(t('company_name')) ?></label>
                    <input id="company_name" type="text" name="company_name" value="<?= old('company_name') ?>">
                    <?php if ($error = validationError('company_name')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password"><?= e(t('password')) ?></label>
                    <input id="password" type="password" name="password" minlength="6" required>
                    <?php if ($error = validationError('password')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password_confirm"><?= e(t('confirm_password')) ?></label>
                    <input id="password_confirm" type="password" name="password_confirm" required>
                    <?php if ($error = validationError('password_confirm')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <button class="btn btn-primary btn-block" type="submit"><?= e(t('create_account')) ?></button>
            </form>
            <p class="auth-switch"><?= e(t('already_account')) ?> <a href="<?= url('login') ?>"><?= e(t('login')) ?></a></p>
        </section>
    </main>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
