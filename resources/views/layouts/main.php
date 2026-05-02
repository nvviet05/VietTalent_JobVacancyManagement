<!DOCTYPE html>
<html lang="<?= e(currentLang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? APP_NAME) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>
<body>
    <?php include APP_ROOT . '/resources/views/partials/header.php'; ?>
    <?php include APP_ROOT . '/resources/views/partials/flash-message.php'; ?>
    <main>
        <?php include APP_ROOT . '/resources/views/' . $content . '.php'; ?>
    </main>
    <?php include APP_ROOT . '/resources/views/partials/footer.php'; ?>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
