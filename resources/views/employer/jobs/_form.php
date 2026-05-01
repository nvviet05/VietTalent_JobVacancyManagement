<?php
$formMode = $formMode ?? 'create';
$submitLabel = $formMode === 'edit' ? t('save_changes') : t('save');
$actionUrl = $formMode === 'edit'
    ? url('employer_job_update', ['id' => $jobId])
    : url('employer_job_store');
$pageTitle = $formMode === 'edit' ? t('edit_job') : t('create_new_job');
$cancelUrl = $formMode === 'edit'
    ? url('employer_job_view', ['id' => $jobId])
    : url('employer_jobs');
?>

<section class="dashboard-stack">
    <div class="section-toolbar">
        <div>
            <h2><?= e($pageTitle) ?></h2>
            <p><?= e($formMode === 'edit' ? t('edit_job_intro') : t('create_job_intro')) ?></p>
        </div>
        <a class="btn btn-outline" href="<?= $cancelUrl ?>"><?= e(t('cancel')) ?></a>
    </div>

    <form action="<?= $actionUrl ?>" method="POST" class="job-form" data-job-form>
        <section class="card section-card">
            <div class="section-card-header">
                <div>
                    <h3><?= e(t('basic_job_information')) ?></h3>
                    <p><?= e(t('basic_job_information_help')) ?></p>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="job_title_id"><?= e(t('job_title')) ?></label>
                    <select id="job_title_id" name="job_title_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['job_titles'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['job_title_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('job_title_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="job_category_id"><?= e(t('job_category')) ?></label>
                    <select id="job_category_id" name="job_category_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['job_categories'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['job_category_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('job_category_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="employment_type_id"><?= e(t('employment_type')) ?></label>
                    <select id="employment_type_id" name="employment_type_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['employment_types'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['employment_type_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('employment_type_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="industry_id"><?= e(t('industry')) ?></label>
                    <select id="industry_id" name="industry_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['industries'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['industry_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('industry_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="job_level_id"><?= e(t('job_level')) ?></label>
                    <select id="job_level_id" name="job_level_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['job_levels'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['job_level_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('job_level_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="number_of_openings"><?= e(t('number_of_openings')) ?></label>
                    <input id="number_of_openings" type="number" min="1" name="number_of_openings" value="<?= e($formData['number_of_openings']) ?>" required>
                    <?php if ($error = validationError('number_of_openings')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
            </div>
        </section>

        <section class="card section-card">
            <div class="section-card-header">
                <div>
                    <h3><?= e(t('job_location')) ?></h3>
                    <p><?= e(t('job_location_help')) ?></p>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="country_id"><?= e(t('country')) ?></label>
                    <select id="country_id" name="country_id" required data-country-select>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['countries'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['country_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('country_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="city_id"><?= e(t('city_province')) ?></label>
                    <select id="city_id" name="city_id" required data-city-select>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['cities'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" data-country-id="<?= e((string)$item['country_id']) ?>" <?= (string)$formData['city_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('city_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="district_id"><?= e(t('district')) ?></label>
                    <select id="district_id" name="district_id" data-district-select>
                        <option value=""><?= e(t('select_optional')) ?></option>
                        <?php foreach ($lookups['districts'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" data-city-id="<?= e((string)$item['city_id']) ?>" <?= (string)$formData['district_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('district_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="work_arrangement_id"><?= e(t('work_arrangement')) ?></label>
                    <select id="work_arrangement_id" name="work_arrangement_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['work_arrangements'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['work_arrangement_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('work_arrangement_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
            </div>
        </section>

        <section class="card section-card">
            <div class="section-card-header">
                <div>
                    <h3><?= e(t('salary_benefits')) ?></h3>
                    <p><?= e(t('salary_benefits_help')) ?></p>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="salary_range_id"><?= e(t('salary_range')) ?></label>
                    <select id="salary_range_id" name="salary_range_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['salary_ranges'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['salary_range_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('salary_range_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="salary_type_id"><?= e(t('salary_type')) ?></label>
                    <select id="salary_type_id" name="salary_type_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['salary_types'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['salary_type_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('salary_type_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="benefits"><?= e(t('benefits')) ?></label>
                <textarea id="benefits" name="benefits" rows="4"><?= e($formData['benefits']) ?></textarea>
                <?php if ($error = validationError('benefits')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
            </div>
        </section>

        <section class="card section-card">
            <div class="section-card-header">
                <div>
                    <h3><?= e(t('job_description')) ?></h3>
                    <p><?= e(t('job_description_help')) ?></p>
                </div>
            </div>
            <div class="form-group">
                <label for="responsibilities"><?= e(t('responsibilities')) ?></label>
                <textarea id="responsibilities" name="responsibilities" rows="5" required><?= e($formData['responsibilities']) ?></textarea>
                <?php if ($error = validationError('responsibilities')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="required_qualifications"><?= e(t('required_qualifications')) ?></label>
                <textarea id="required_qualifications" name="required_qualifications" rows="5" required><?= e($formData['required_qualifications']) ?></textarea>
                <?php if ($error = validationError('required_qualifications')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="preferred_skills"><?= e(t('preferred_skills')) ?></label>
                <textarea id="preferred_skills" name="preferred_skills" rows="4"><?= e($formData['preferred_skills']) ?></textarea>
                <?php if ($error = validationError('preferred_skills')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="additional_notes"><?= e(t('additional_notes')) ?></label>
                <textarea id="additional_notes" name="additional_notes" rows="4"><?= e($formData['additional_notes']) ?></textarea>
                <?php if ($error = validationError('additional_notes')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
            </div>
        </section>

        <section class="card section-card">
            <div class="section-card-header">
                <div>
                    <h3><?= e(t('required_skills')) ?></h3>
                    <p><?= e(t('select_1_to_5_required_skills')) ?></p>
                </div>
                <button class="btn btn-outline btn-sm" type="button" data-add-skill><?= e(t('add_skill')) ?></button>
            </div>
            <?php if ($error = validationError('skills')): ?><span class="form-error form-error-block"><?= e($error) ?></span><?php endif; ?>
            <div class="skills-builder" data-skills-container data-max-skills="5" data-max-message="<?= e(t('skill_limit_reached')) ?>" data-min-message="<?= e(t('at_least_one_skill_required')) ?>" data-duplicate-message="<?= e(t('duplicate_skills_not_allowed')) ?>">
                <?php foreach ($skillRows as $index => $skillRow): ?>
                    <div class="skill-row" data-skill-row>
                        <div class="form-group">
                            <label><?= e(t('skill')) ?></label>
                            <select name="skills[<?= e((string)$index) ?>][skill_id]" required data-skill-select>
                                <option value=""><?= e(t('select_option')) ?></option>
                                <?php foreach ($lookups['skills'] as $item): ?>
                                    <option value="<?= e((string)$item['id']) ?>" <?= (string)$skillRow['skill_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($error = validationError('skills_' . $index . '_skill_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label><?= e(t('minimum_proficiency')) ?></label>
                            <select name="skills[<?= e((string)$index) ?>][proficiency_level_id]" required>
                                <option value=""><?= e(t('select_option')) ?></option>
                                <?php foreach ($lookups['proficiency_levels'] as $item): ?>
                                    <option value="<?= e((string)$item['id']) ?>" <?= (string)$skillRow['proficiency_level_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($error = validationError('skills_' . $index . '_proficiency_level_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                        </div>
                        <button class="btn btn-outline btn-sm" type="button" data-remove-skill><?= e(t('remove')) ?></button>
                    </div>
                <?php endforeach; ?>
            </div>
            <template data-skill-template>
                <div class="skill-row" data-skill-row>
                    <div class="form-group">
                        <label><?= e(t('skill')) ?></label>
                        <select required data-skill-select>
                            <option value=""><?= e(t('select_option')) ?></option>
                            <?php foreach ($lookups['skills'] as $item): ?>
                                <option value="<?= e((string)$item['id']) ?>"><?= e($item['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?= e(t('minimum_proficiency')) ?></label>
                        <select required>
                            <option value=""><?= e(t('select_option')) ?></option>
                            <?php foreach ($lookups['proficiency_levels'] as $item): ?>
                                <option value="<?= e((string)$item['id']) ?>"><?= e($item['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-outline btn-sm" type="button" data-remove-skill><?= e(t('remove')) ?></button>
                </div>
            </template>
        </section>

        <section class="card section-card">
            <div class="section-card-header">
                <div>
                    <h3><?= e(t('education_experience')) ?></h3>
                    <p><?= e(t('education_experience_help')) ?></p>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="degree_level_id"><?= e(t('minimum_degree_level')) ?></label>
                    <select id="degree_level_id" name="degree_level_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['degree_levels'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['degree_level_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('degree_level_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="experience_level_id"><?= e(t('minimum_years_of_experience')) ?></label>
                    <select id="experience_level_id" name="experience_level_id" required>
                        <option value=""><?= e(t('select_option')) ?></option>
                        <?php foreach ($lookups['experience_levels'] as $item): ?>
                            <option value="<?= e((string)$item['id']) ?>" <?= (string)$formData['experience_level_id'] === (string)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($error = validationError('experience_level_id')): ?><span class="form-error"><?= e($error) ?></span><?php endif; ?>
                </div>
            </div>
        </section>

        <div class="form-actions">
            <a class="btn btn-outline" href="<?= $cancelUrl ?>"><?= e(t('cancel')) ?></a>
            <button class="btn btn-primary" type="submit"><?= e($submitLabel) ?></button>
        </div>
    </form>
</section>
