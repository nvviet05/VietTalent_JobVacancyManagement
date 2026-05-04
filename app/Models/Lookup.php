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

    public function getActiveJobCategoryById($id) {
        return $this->db->fetch(
            "SELECT id, name FROM job_categories WHERE id = ? AND status = 'active'",
            [(int)$id]
        );
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

    public function getCountries() {
        return $this->db->fetchAll(
            'SELECT id, name, status
             FROM countries
             ORDER BY name ASC, id ASC'
        );
    }

    public function getActiveCities() {
        return $this->db->fetchAll(
            'SELECT c.id, c.country_id, c.name
             FROM cities c
             INNER JOIN countries co ON co.id = c.country_id
             WHERE c.status = ? AND co.status = ?
             ORDER BY c.name ASC',
            ['active', 'active']
        );
    }

    public function getCities() {
        return $this->db->fetchAll(
            'SELECT c.id, c.country_id, c.name, c.status, co.name AS country_name
             FROM cities c
             INNER JOIN countries co ON co.id = c.country_id
             ORDER BY co.name ASC, c.name ASC, c.id ASC'
        );
    }

    public function getActiveDistricts() {
        return $this->db->fetchAll(
            'SELECT d.id, d.city_id, d.name
             FROM districts d
             INNER JOIN cities c ON c.id = d.city_id
             INNER JOIN countries co ON co.id = c.country_id
             WHERE d.status = ? AND c.status = ? AND co.status = ?
             ORDER BY d.name ASC',
            ['active', 'active', 'active']
        );
    }

    public function getDistricts() {
        return $this->db->fetchAll(
            'SELECT d.id, d.city_id, d.name, d.status,
                    c.name AS city_name,
                    co.name AS country_name
             FROM districts d
             INNER JOIN cities c ON c.id = d.city_id
             INNER JOIN countries co ON co.id = c.country_id
             ORDER BY co.name ASC, c.name ASC, d.name ASC, d.id ASC'
        );
    }

    public function getCategoriesWithJobCount() {
        return $this->db->fetchAll(
            'SELECT jc.id, jc.name, COUNT(jv.id) AS job_count
             FROM job_categories jc
             LEFT JOIN job_vacancies jv ON jv.job_category_id = jc.id AND jv.status = ?
             WHERE jc.status = ?
             GROUP BY jc.id, jc.name
             ORDER BY job_count DESC, jc.name ASC',
            ['active', 'active']
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
            'SELECT COUNT(*)
             FROM cities c
             INNER JOIN countries co ON co.id = c.country_id
             WHERE c.id = ? AND c.country_id = ? AND c.status = ? AND co.status = ?',
            [(int)$cityId, (int)$countryId, 'active', 'active']
        ) > 0;
    }

    public function districtBelongsToCity($districtId, $cityId) {
        return $this->db->count(
            'SELECT COUNT(*)
             FROM districts d
             INNER JOIN cities c ON c.id = d.city_id
             INNER JOIN countries co ON co.id = c.country_id
             WHERE d.id = ? AND d.city_id = ? AND d.status = ? AND c.status = ? AND co.status = ?',
            [(int)$districtId, (int)$cityId, 'active', 'active', 'active']
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

    public function findCountry($id) {
        return $this->db->fetch(
            'SELECT id, name, status
             FROM countries
             WHERE id = ?',
            [(int)$id]
        );
    }

    public function createCountry($data) {
        return $this->db->insert('countries', [
            'name' => $data['name'],
            'status' => $data['status'],
        ]);
    }

    public function updateCountry($id, $data) {
        return $this->db->execute(
            'UPDATE countries
             SET name = ?, status = ?
             WHERE id = ?',
            [$data['name'], $data['status'], (int)$id]
        );
    }

    public function toggleCountryStatus($id) {
        $country = $this->findCountry($id);
        if (!$country) {
            return false;
        }

        $nextStatus = ($country['status'] ?? 'inactive') === 'active' ? 'inactive' : 'active';
        $updated = $this->db->execute(
            'UPDATE countries
             SET status = ?
             WHERE id = ?',
            [$nextStatus, (int)$id]
        );

        return $updated ? $nextStatus : false;
    }

    public function findCity($id) {
        return $this->db->fetch(
            'SELECT c.id, c.country_id, c.name, c.status, co.name AS country_name
             FROM cities c
             INNER JOIN countries co ON co.id = c.country_id
             WHERE c.id = ?',
            [(int)$id]
        );
    }

    public function createCity($data) {
        return $this->db->insert('cities', [
            'country_id' => (int)$data['country_id'],
            'name' => $data['name'],
            'status' => $data['status'],
        ]);
    }

    public function updateCity($id, $data) {
        return $this->db->execute(
            'UPDATE cities
             SET country_id = ?, name = ?, status = ?
             WHERE id = ?',
            [(int)$data['country_id'], $data['name'], $data['status'], (int)$id]
        );
    }

    public function toggleCityStatus($id) {
        $city = $this->findCity($id);
        if (!$city) {
            return false;
        }

        $nextStatus = ($city['status'] ?? 'inactive') === 'active' ? 'inactive' : 'active';
        $updated = $this->db->execute(
            'UPDATE cities
             SET status = ?
             WHERE id = ?',
            [$nextStatus, (int)$id]
        );

        return $updated ? $nextStatus : false;
    }

    public function findDistrict($id) {
        return $this->db->fetch(
            'SELECT d.id, d.city_id, d.name, d.status,
                    c.name AS city_name,
                    c.country_id,
                    co.name AS country_name
             FROM districts d
             INNER JOIN cities c ON c.id = d.city_id
             INNER JOIN countries co ON co.id = c.country_id
             WHERE d.id = ?',
            [(int)$id]
        );
    }

    public function createDistrict($data) {
        return $this->db->insert('districts', [
            'city_id' => (int)$data['city_id'],
            'name' => $data['name'],
            'status' => $data['status'],
        ]);
    }

    public function updateDistrict($id, $data) {
        return $this->db->execute(
            'UPDATE districts
             SET city_id = ?, name = ?, status = ?
             WHERE id = ?',
            [(int)$data['city_id'], $data['name'], $data['status'], (int)$id]
        );
    }

    public function toggleDistrictStatus($id) {
        $district = $this->findDistrict($id);
        if (!$district) {
            return false;
        }

        $nextStatus = ($district['status'] ?? 'inactive') === 'active' ? 'inactive' : 'active';
        $updated = $this->db->execute(
            'UPDATE districts
             SET status = ?
             WHERE id = ?',
            [$nextStatus, (int)$id]
        );

        return $updated ? $nextStatus : false;
    }

    public function countryExists($id) {
        return $this->db->count(
            'SELECT COUNT(*) FROM countries WHERE id = ?',
            [(int)$id]
        ) > 0;
    }

    public function cityExists($id) {
        return $this->db->count(
            'SELECT COUNT(*) FROM cities WHERE id = ?',
            [(int)$id]
        ) > 0;
    }

    public function districtExists($id) {
        return $this->db->count(
            'SELECT COUNT(*) FROM districts WHERE id = ?',
            [(int)$id]
        ) > 0;
    }

    public function isDuplicateCountryName($name, $excludeId = null) {
        $sql = 'SELECT COUNT(*) FROM countries WHERE name = ?';
        $params = [$name];

        if ($excludeId !== null) {
            $sql .= ' AND id <> ?';
            $params[] = (int)$excludeId;
        }

        return $this->db->count($sql, $params) > 0;
    }

    public function isDuplicateCityName($countryId, $name, $excludeId = null) {
        $sql = 'SELECT COUNT(*) FROM cities WHERE country_id = ? AND name = ?';
        $params = [(int)$countryId, $name];

        if ($excludeId !== null) {
            $sql .= ' AND id <> ?';
            $params[] = (int)$excludeId;
        }

        return $this->db->count($sql, $params) > 0;
    }

    public function isDuplicateDistrictName($cityId, $name, $excludeId = null) {
        $sql = 'SELECT COUNT(*) FROM districts WHERE city_id = ? AND name = ?';
        $params = [(int)$cityId, $name];

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
}
