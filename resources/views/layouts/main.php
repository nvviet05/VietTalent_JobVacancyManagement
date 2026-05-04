<?php
// Compute canonical URL for SEO (criterion #8)
$canonicalUrl = BASE_URL . '/index.php?' . http_build_query($_GET ?: ['page' => 'home']);
$pageTitle = ($title ?? APP_NAME) . ' - ' . APP_NAME;
?>
<!DOCTYPE html>
<html lang="<?= e(currentLang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>

    <!-- SEO meta (criterion #8) -->
    <meta name="description" content="<?= e($meta_description ?? 'VietTalent — verified job vacancies from trusted employers.') ?>">
    <meta name="keywords" content="<?= e($meta_keywords ?? 'jobs, careers, vacancies, VietTalent') ?>">
    <meta name="robots" content="index, follow">
    <meta name="author" content="VietTalent">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">

    <!-- Open Graph (social sharing) -->
    <meta property="og:title" content="<?= e($og_title ?? $pageTitle) ?>">
    <meta property="og:description" content="<?= e($meta_description ?? '') ?>">
    <meta property="og:type" content="<?= e($og_type ?? 'website') ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:site_name" content="VietTalent">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?= e($og_title ?? $pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($meta_description ?? '') ?>">

    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <?php foreach (($styles ?? []) as $s): ?>
        <link rel="stylesheet" href="<?= asset($s) ?>">
    <?php endforeach; ?>

    <!-- Schema.org JSON-LD for the site (helps search engines understand structure) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "VietTalent",
        "url": "<?= e(BASE_URL) ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?= e(BASE_URL) ?>/index.php?page=jobs&keyword={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
</head>
<body>
    <?php include APP_ROOT . '/resources/views/partials/header.php'; ?>
    <?php include APP_ROOT . '/resources/views/partials/flash-message.php'; ?>
    <?php if (!empty($breadcrumbs) && count($breadcrumbs) > 1): ?>
        <?php $bc = $breadcrumbs; include APP_ROOT . '/resources/views/partials/breadcrumb.php'; ?>
    <?php endif; ?>
    <main>
        <?php include APP_ROOT . '/resources/views/' . $content . '.php'; ?>
    </main>
    <?php include APP_ROOT . '/resources/views/partials/footer.php'; ?>
    <script src="<?= asset('js/main.js') ?>"></script>
    <?php foreach (($scripts ?? []) as $s): ?>
        <script src="<?= asset($s) ?>"></script>
    <?php endforeach; ?>
</body>
</html>
