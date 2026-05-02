<!DOCTYPE html>
<html lang="<?= e(currentLang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(t('login')) ?> - <?= APP_NAME ?></title>
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
            <h1><?= e(t('welcome_back')) ?></h1>
            <p><?= e(t('login_intro')) ?></p>
        </section>
        <section class="auth-card">
            <h2><?= e(t('login')) ?></h2>
            <?php include APP_ROOT . '/resources/views/partials/flash-message.php'; ?>
            <form action="<?= url('login_submit') ?>" method="POST">
                <div class="form-group">
                    <label for="email"><?= e(t('email')) ?></label>
                    <input id="email" type="email" name="email" value="<?= old('email') ?>" required>
                    <?php if ($error = validationError('email')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password"><?= e(t('password')) ?></label>
                    <input id="password" type="password" name="password" required>
                    <?php if ($error = validationError('password')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <button class="btn btn-primary btn-block" type="submit"><?= e(t('login')) ?></button>
            </form>
            <p class="auth-switch"><?= e(t('no_account')) ?> <a href="<?= url('register') ?>"><?= e(t('register')) ?></a></p>
        </section>
    </main>
</body>
</html>
