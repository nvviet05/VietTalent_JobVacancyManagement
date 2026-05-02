<section class="dashboard-stack">
    <div class="section-toolbar">
        <div>
            <h2><?= e(t($lookupMeta['title_key'])) ?></h2>
            <p><?= e(t('lookup_management_intro')) ?></p>
        </div>
        <div class="toolbar-actions">
            <a class="btn btn-outline" href="<?= url('admin_dashboard') ?>"><?= e(t('admin_dashboard')) ?></a>
            <a class="btn btn-primary" href="<?= url('admin_lookup_create', ['type' => $lookupMeta['key']]) ?>"><?= e(t('add_new')) ?></a>
        </div>
    </div>

    <section class="card section-card">
        <?php if (empty($records)): ?>
            <div class="empty-state">
                <p><?= e(t('no_records_available')) ?></p>
                <a class="btn btn-primary" href="<?= url('admin_lookup_create', ['type' => $lookupMeta['key']]) ?>"><?= e(t('add_new')) ?></a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th><?= e(($lookupMeta['special'] ?? null) === 'salary_range' ? t('label') : t('name')) ?></th>
                            <?php if (($lookupMeta['special'] ?? null) === 'salary_range'): ?>
                                <th><?= e(t('minimum_salary')) ?></th>
                                <th><?= e(t('maximum_salary')) ?></th>
                                <th><?= e(t('currency')) ?></th>
                            <?php endif; ?>
                            <th><?= e(t('status')) ?></th>
                            <?php if (!empty($lookupMeta['has_created_at'])): ?>
                                <th><?= e(t('created_date')) ?></th>
                            <?php endif; ?>
                            <th><?= e(t('actions')) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?= e((string)$record['id']) ?></td>
                                <td><?= e($record['display_label']) ?></td>
                                <?php if (($lookupMeta['special'] ?? null) === 'salary_range'): ?>
                                    <td><?= e((string)$record['min_salary']) ?></td>
                                    <td><?= e($record['max_salary'] === null ? '' : (string)$record['max_salary']) ?></td>
                                    <td><?= e($record['currency']) ?></td>
                                <?php endif; ?>
                                <td><span class="status-badge status-<?= e($record['status']) ?>"><?= e(t($record['status'])) ?></span></td>
                                <?php if (!empty($lookupMeta['has_created_at'])): ?>
                                    <td><?= e(date('Y-m-d', strtotime($record['created_at']))) ?></td>
                                <?php endif; ?>
                                <td>
                                    <div class="table-actions">
                                        <a class="table-action" href="<?= url('admin_lookup_edit', ['type' => $lookupMeta['key'], 'id' => $record['id']]) ?>"><?= e(t('edit_record')) ?></a>
                                        <a class="table-action" href="<?= url('admin_lookup_toggle_status', ['type' => $lookupMeta['key'], 'id' => $record['id']]) ?>"><?= e($record['status'] === 'active' ? t('deactivate') : t('activate')) ?></a>
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
