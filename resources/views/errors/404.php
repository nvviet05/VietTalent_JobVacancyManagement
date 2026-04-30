<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>
<body class="error-body">
    <div class="error-card">
        <h1>404</h1>
        <h2>Page not found</h2>
        <p>The requested route does not exist in the Phase 1 route table.</p>
        <a class="btn btn-primary" href="<?= url('home') ?>">Back Home</a>
    </div>
</body>
</html>
