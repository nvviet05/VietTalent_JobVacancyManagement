<!DOCTYPE html>
<html lang="<?= e(function_exists('currentLang') ? currentLang() : 'en') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e((isset($errorTitle) ? $errorTitle : t('error_403_title'))) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>
<body class="error-body">
    <div class="error-card">
        <h1>403</h1>
        <h2><?= e(isset($errorTitle) ? $errorTitle : t('error_403_title')) ?></h2>
        <p><?= e(isset($errorMessage) ? $errorMessage : t('error_403_message')) ?></p>
        <a class="btn btn-primary" href="<?= url('home') ?>"><?= e(t('error_back_home')) ?></a>
    </div>
</body>
</html>
