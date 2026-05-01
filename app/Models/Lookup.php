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
