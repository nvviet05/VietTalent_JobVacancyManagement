<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>
<body class="error-body">
    <div class="error-card">
        <h1>403</h1>
        <h2>Access forbidden</h2>
        <p>Your current role does not have permission to open this page.</p>
        <a class="btn btn-primary" href="<?= url('home') ?>">Back Home</a>
    </div>
</body>
</html>
