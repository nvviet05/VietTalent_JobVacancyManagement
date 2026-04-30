<?php
$logoUrl = $logoUrl ?? url('home');
$logoShowTagline = $logoShowTagline ?? true;
$logoCompact = $logoCompact ?? false;
$logoTagline = $logoTagline ?? t('brand_tagline');
?>
<a class="brand-logo <?= $logoCompact ? 'brand-logo-compact' : '' ?>" href="<?= e($logoUrl) ?>" aria-label="<?= APP_NAME ?>">
    <span class="brand-mark" aria-hidden="true">
        <span class="brand-v">V</span>
        <span class="brand-t">T</span>
        <span class="brand-spark"></span>
        <span class="brand-path"></span>
    </span>
    <span class="brand-text-wrap">
        <span class="brand-text"><?= APP_NAME ?></span>
        <?php if ($logoShowTagline): ?>
            <span class="brand-tagline"><?= e($logoTagline) ?></span>
        <?php endif; ?>
    </span>
</a>
<?php
unset($logoUrl, $logoShowTagline, $logoCompact, $logoTagline);
?>
