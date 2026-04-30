<?php if (hasFlash('success')): ?>
    <div class="alert alert-success"><?= e(flash('success')) ?></div>
<?php endif; ?>

<?php if (hasFlash('error')): ?>
    <div class="alert alert-error"><?= e(flash('error')) ?></div>
<?php endif; ?>
