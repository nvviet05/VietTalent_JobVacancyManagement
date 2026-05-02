<?php
class Lookup {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getActiveJobTitles() {
        return $this->getActiveOptions('job_titles');
    }

    public function getActiveJobCategories() {
        return $this->getActiveOptions('job_categories');
    }

    public function getActiveIndustries() {
        return $this->getActiveOptions('industries');
    }

    public function getActiveEmploymentTypes() {
        return $this->getActiveOptions('employment_types');
    }

    public function getActiveJobLevels() {
        return $this->getActiveOptions('job_levels');
    }

    public function getActiveSalaryRanges() {
        return $this->getActiveOptions('salary_ranges', 'label');
    }

    public function getActiveSalaryTypes() {
        return $this->getActiveOptions('salary_types');
    }

    public function getActiveSkills() {
        return $this->getActiveOptions('skills');
    }

    public function getActiveProficiencyLevels() {
        return $this->getActiveOptions('proficiency_levels');
    }

    public function getActiveDegreeLevels() {
        return $this->getActiveOptions('degree_levels');
    }

    public function getActiveExperienceLevels() {
        return $this->getActiveOptions('experience_levels');
    }

    public function getActiveCountries() {
        return $this->getActiveOptions('countries');
    }

    public function getActiveCities() {
        return $this->db->fetchAll(
            'SELECT id, country_id, name
             FROM cities
             WHERE status = ?
             ORDER BY name ASC',
            ['active']
        );
    }

    public function getActiveDistricts() {
        return $this->db->fetchAll(
            'SELECT id, city_id, name
             FROM districts
             WHERE status = ?
             ORDER BY name ASC',
            ['active']
        );
    }

    public function getActiveWorkArrangements() {
        return $this->getActiveOptions('work_arrangements');
    }

    public function existsActive($table, $id) {
        return $this->db->count(
            "SELECT COUNT(*) FROM {$table} WHERE id = ? AND status = ?",
            [(int)$id, 'active']
        ) > 0;
    }

    public function cityBelongsToCountry($cityId, $countryId) {
        return $this->db->count(
            'SELECT COUNT(*) FROM cities WHERE id = ? AND country_id = ? AND status = ?',
            [(int)$cityId, (int)$countryId, 'active']
        ) > 0;
    }

    public function districtBelongsToCity($districtId, $cityId) {
        return $this->db->count(
            'SELECT COUNT(*) FROM districts WHERE id = ? AND city_id = ? AND status = ?',
            [(int)$districtId, (int)$cityId, 'active']
        ) > 0;
    }

    public function getAllowedLookupTypes() {
        return [
            'job_categories' => [
                'key' => 'job_categories',
                'table' => 'job_categories',
                'title_key' => 'job_categories_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
            'job_titles' => [
                'key' => 'job_titles',
                'table' => 'job_titles',
                'title_key' => 'job_titles_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
            'skills' => [
                'key' => 'skills',
                'table' => 'skills',
                'title_key' => 'skills_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
            'industries' => [
                'key' => 'industries',
                'table' => 'industries',
                'title_key' => 'industries_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
            'employment_types' => [
                'key' => 'employment_types',
                'table' => 'employment_types',
                'title_key' => 'employment_types_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
            'job_levels' => [
                'key' => 'job_levels',
                'table' => 'job_levels',
                'title_key' => 'job_levels_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
            'salary_ranges' => [
                'key' => 'salary_ranges',
                'table' => 'salary_ranges',
                'title_key' => 'salary_ranges_title',
                'label_column' => 'label',
                'value_key' => 'label',
                'fields' => ['label', 'min_salary', 'max_salary', 'currency', 'status'],
                'has_created_at' => false,
                'special' => 'salary_range',
            ],
            'salary_types' => [
                'key' => 'salary_types',
                'table' => 'salary_types',
                'title_key' => 'salary_types_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
            'degree_levels' => [
                'key' => 'degree_levels',
                'table' => 'degree_levels',
                'title_key' => 'degree_levels_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
            'experience_levels' => [
                'key' => 'experience_levels',
                'table' => 'experience_levels',
                'title_key' => 'experience_levels_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
            'work_arrangements' => [
                'key' => 'work_arrangements',
                'table' => 'work_arrangements',
                'title_key' => 'work_arrangements_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
            'proficiency_levels' => [
                'key' => 'proficiency_levels',
                'table' => 'proficiency_levels',
                'title_key' => 'proficiency_levels_title',
                'label_column' => 'name',
                'value_key' => 'name',
                'fields' => ['name', 'status'],
                'has_created_at' => false,
            ],
        ];
    }

    public function validateLookupType($type) {
        return $this->getLookupMeta($type) !== null;
    }

    public function getLookupMeta($type) {
        $types = $this->getAllowedLookupTypes();
        return $types[$type] ?? null;
    }

    public function getLookupRecords($type) {
        $meta = $this->getLookupMeta($type);
        if (!$meta) {
            return [];
        }

        $select = [
            'id',
            $meta['label_column'] . ' AS display_label',
            'status',
        ];

        if ($meta['special'] ?? null) {
            $select[] = 'min_salary';
            $select[] = 'max_salary';
            $select[] = 'currency';
        }

        if (!empty($meta['has_created_at'])) {
            $select[] = 'created_at';
        }

        return $this->db->fetchAll(
            'SELECT ' . implode(', ', $select) . '
             FROM ' . $meta['table'] . '
             ORDER BY ' . $meta['label_column'] . ' ASC, id ASC'
        );
    }

    public function findLookupRecord($type, $id) {
        $meta = $this->getLookupMeta($type);
        if (!$meta) {
            return null;
        }

        return $this->db->fetch(
            'SELECT *
             FROM ' . $meta['table'] . '
             WHERE id = ?',
            [(int)$id]
        );
    }

    public function createLookupRecord($type, $data) {
        $meta = $this->getLookupMeta($type);
        if (!$meta) {
            return 0;
        }

        $insertData = [];
        foreach ($meta['fields'] as $field) {
            $insertData[$field] = $data[$field] ?? null;
        }

        return $this->db->insert($meta['table'], $insertData);
    }

    public function updateLookupRecord($type, $id, $data) {
        $meta = $this->getLookupMeta($type);
        if (!$meta) {
            return false;
        }

        $assignments = [];
        $params = [];

        foreach ($meta['fields'] as $field) {
            if (array_key_exists($field, $data)) {
                $assignments[] = $field . ' = ?';
                $params[] = $data[$field];
            }
        }

        if (empty($assignments)) {
            return false;
        }

        $params[] = (int)$id;

        return $this->db->execute(
            'UPDATE ' . $meta['table'] . '
             SET ' . implode(', ', $assignments) . '
             WHERE id = ?',
            $params
        );
    }

    public function toggleLookupStatus($type, $id) {
        $record = $this->findLookupRecord($type, $id);
        if (!$record) {
            return false;
        }

        $nextStatus = ($record['status'] ?? 'inactive') === 'active' ? 'inactive' : 'active';
        $updated = $this->updateLookupRecord($type, $id, ['status' => $nextStatus]);

        return $updated ? $nextStatus : false;
    }

    public function isDuplicateLookupName($type, $labelValue, $excludeId = null) {
        $meta = $this->getLookupMeta($type);
        if (!$meta) {
            return false;
        }

        $sql = 'SELECT COUNT(*)
                FROM ' . $meta['table'] . '
                WHERE ' . $meta['label_column'] . ' = ?';
        $params = [$labelValue];

        if ($excludeId !== null) {
            $sql .= ' AND id <> ?';
            $params[] = (int)$excludeId;
        }

        return $this->db->count($sql, $params) > 0;
    }

    private function getActiveOptions($table, $labelColumn = 'name') {
        return $this->db->fetchAll(
            "SELECT id, {$labelColumn} AS name
             FROM {$table}
             WHERE status = ?
             ORDER BY {$labelColumn} ASC",
            ['active']
        );
    }
    public function getAllActive($table) {
    $allowed = ['job_categories', 'cities', 'skills', 'employment_types', 'job_levels', 'salary_ranges', 'work_arrangements'];
    if (!in_array($table, $allowed)) return [];
    
    // Determine the correct column to sort by
    // Your schema uses 'label' for salary_ranges, and 'name' for others
    $sortColumn = ($table === 'salary_ranges') ? 'label' : 'name';
    
    // Your schema uses 'status' as the column name
    return $this->db->fetchAll("SELECT * FROM {$table} WHERE status = 'active' ORDER BY {$sortColumn} ASC");
}
}
