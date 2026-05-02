<?php
$isSalaryRange = ($lookupMeta['special'] ?? null) === 'salary_range';
$actionUrl = $formMode === 'edit'
    ? url('admin_lookup_update', ['type' => $lookupMeta['key'], 'id' => $recordId])
    : url('admin_lookup_store', ['type' => $lookupMeta['key']]);
$pageTitle = $formMode === 'edit' ? t('edit_record') : t('create_record');
$submitLabel = $formMode === 'edit' ? t('update_record') : t('save');
$cancelUrl = $formMode === 'edit'
    ? url('admin_lookup', ['type' => $lookupMeta['key']])
    : url('admin_lookup', ['type' => $lookupMeta['key']]);
?>

<section class="dashboard-stack">
    <div class="section-toolbar">
        <div>
            <h2><?= e($pageTitle) ?></h2>
            <p><?= e(t($lookupMeta['title_key'])) ?></p>
        </div>
        <a class="btn btn-outline" href="<?= $cancelUrl ?>"><?= e(t('cancel')) ?></a>
    </div>

    <form action="<?= $actionUrl ?>" method="POST" class="job-form">
        <section class="card section-card">
            <div class="section-card-header">
                <div>
                    <h3><?= e(t('reference_data')) ?></h3>
                    <p><?= e(t('lookup_form_intro')) ?></p>
                </div>
            </div>

            <?php if ($isSalaryRange): ?>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="label"><?= e(t('label')) ?></label>
                        <input id="label" type="text" name="label" value="<?= e($formData['label']) ?>" required>
                        <?php if ($error = validationError('label')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="currency"><?= e(t('currency')) ?></label>
                        <input id="currency" type="text" name="currency" value="<?= e($formData['currency']) ?>" required>
                        <?php if ($error = validationError('currency')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="min_salary"><?= e(t('minimum_salary')) ?></label>
                        <input id="min_salary" type="number" step="0.01" min="0" name="min_salary" value="<?= e($formData['min_salary']) ?>" required>
                        <?php if ($error = validationError('min_salary')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="max_salary"><?= e(t('maximum_salary')) ?></label>
                        <input id="max_salary" type="number" step="0.01" min="0" name="max_salary" value="<?= e($formData['max_salary']) ?>">
                        <?php if ($error = validationError('max_salary')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="form-group">
                    <label for="name"><?= e(t('name')) ?></label>
                    <input id="name" type="text" name="name" value="<?= e($formData['name']) ?>" required>
                    <?php if ($error = validationError('name')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="status"><?= e(t('status')) ?></label>
                <select id="status" name="status" required>
                    <option value="active" <?= $formData['status'] === 'active' ? 'selected' : '' ?>><?= e(t('active')) ?></option>
                    <option value="inactive" <?= $formData['status'] === 'inactive' ? 'selected' : '' ?>><?= e(t('inactive')) ?></option>
                </select>
                <?php if ($error = validationError('status')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
            </div>
        </section>

        <div class="form-actions">
            <a class="btn btn-outline" href="<?= $cancelUrl ?>"><?= e(t('cancel')) ?></a>
            <button class="btn btn-primary" type="submit"><?= e($submitLabel) ?></button>
        </div>
    </form>
</section>
