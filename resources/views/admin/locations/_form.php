<?php
$actionUrl = $formMode === 'edit'
    ? url($locationMeta['update_route'], ['id' => $recordId])
    : url($locationMeta['store_route']);
$cancelUrl = url($locationMeta['index_route']);
$isCity = $locationMeta['key'] === 'cities';
$isDistrict = $locationMeta['key'] === 'districts';
?>

<section class="dashboard-stack">
    <div class="section-toolbar">
        <div>
            <h2><?= e(t($formMode === 'edit' ? $locationMeta['edit_title_key'] : $locationMeta['create_title_key'])) ?></h2>
            <p><?= e(t('location_form_intro')) ?></p>
        </div>
        <a class="btn btn-outline" href="<?= $cancelUrl ?>"><?= e(t('cancel')) ?></a>
    </div>

    <form action="<?= $actionUrl ?>" method="POST" class="job-form">
        <section class="card section-card">
            <div class="section-card-header">
                <div>
                    <h3><?= e(t('location_management')) ?></h3>
                    <p><?= e(t($locationMeta['title_key'])) ?></p>
                </div>
            </div>

            <?php if ($isCity): ?>
                <div class="form-group">
                    <label for="country_id"><?= e(t('country')) ?></label>
                    <select id="country_id" name="country_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($parentOptions as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)($formData['country_id'] ?? '') === (string)$item['id'] ? 'selected' : '' ?>>
                                <?= e($item['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('country_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
            <?php elseif ($isDistrict): ?>
                <div class="form-group">
                    <label for="city_id"><?= e(t('city_province')) ?></label>
                    <select id="city_id" name="city_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($parentOptions as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)($formData['city_id'] ?? '') === (string)$item['id'] ? 'selected' : '' ?>>
                                <?= e($item['name']) ?> (<?= e($item['country_name']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('city_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="name"><?= e(t($locationMeta['name_label_key'])) ?></label>
                <input id="name" type="text" name="name" value="<?= e($formData['name']) ?>" required>
                <?php if ($error = validationError('name')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
            </div>

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
            <button class="btn btn-primary" type="submit"><?= e(t($formMode === 'edit' ? $locationMeta['update_title_key'] : $locationMeta['create_title_key'])) ?></button>
        </div>
    </form>
</section>
