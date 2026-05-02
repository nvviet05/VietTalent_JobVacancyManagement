<section class="dashboard-stack">
    <div class="section-toolbar">
        <div>
            <h2><?= e(t($locationMeta['title_key'])) ?></h2>
            <p><?= e(t('location_management_intro')) ?></p>
        </div>
        <div class="toolbar-actions">
            <a class="btn btn-outline" href="<?= url('admin_dashboard') ?>"><?= e(t('admin_dashboard')) ?></a>
            <a class="btn btn-primary" href="<?= url($locationMeta['create_route']) ?>"><?= e(t($locationMeta['create_title_key'])) ?></a>
        </div>
    </div>

    <section class="card section-card">
        <?php if (empty($records)): ?>
            <div class="empty-state">
                <p><?= e(t('no_records_available')) ?></p>
                <a class="btn btn-primary" href="<?= url($locationMeta['create_route']) ?>"><?= e(t($locationMeta['create_title_key'])) ?></a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th><?= e(t($locationMeta['name_label_key'])) ?></th>
                            <?php if ($locationMeta['key'] === 'cities'): ?>
                                <th><?= e(t('country')) ?></th>
                            <?php elseif ($locationMeta['key'] === 'districts'): ?>
                                <th><?= e(t('city_province')) ?></th>
                                <th><?= e(t('country')) ?></th>
                            <?php endif; ?>
                            <th><?= e(t('status')) ?></th>
                            <th><?= e(t('actions')) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?= e((string)$record['id']) ?></td>
                                <td><?= e($record['name']) ?></td>
                                <?php if ($locationMeta['key'] === 'cities'): ?>
                                    <td><?= e($record['country_name']) ?></td>
                                <?php elseif ($locationMeta['key'] === 'districts'): ?>
                                    <td><?= e($record['city_name']) ?></td>
                                    <td><?= e($record['country_name']) ?></td>
                                <?php endif; ?>
                                <td><span class="status-badge status-<?= e($record['status']) ?>"><?= e(t($record['status'])) ?></span></td>
                                <td>
                                    <div class="table-actions">
                                        <a class="table-action" href="<?= url($locationMeta['edit_route'], ['id' => $record['id']]) ?>"><?= e(t($locationMeta['edit_title_key'])) ?></a>
                                        <a class="table-action" href="<?= url($locationMeta['toggle_route'], ['id' => $record['id']]) ?>"><?= e($record['status'] === 'active' ? t('deactivate') : t('activate')) ?></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</section>
