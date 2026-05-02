<!DOCTYPE html>
<html lang="<?= e(currentLang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? APP_NAME) ?> - <?= APP_NAME ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">

    <style>
        /* Các style bổ sung từ phrase3 để các thẻ Job Card hiển thị đúng */
        body { background-color: #f8f9fa; display: flex; flex-direction: column; min-height: 100vh; }
        main { flex: 1; }
        .job-card:hover { 
            transform: translateY(-3px); 
            transition: 0.3s; 
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; 
        }
    </style>
</head>
<body>
    <?php include APP_ROOT . '/resources/views/partials/header.php'; ?>

    <?php include APP_ROOT . '/resources/views/partials/flash-message.php'; ?>

    <main class="py-4">
        <div class="container">
            <?php 
                if (isset($content)) {
                    include APP_ROOT . '/resources/views/' . $content . '.php';
                } 
            ?>
        </div>
    </main>

    <?php include APP_ROOT . '/resources/views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
