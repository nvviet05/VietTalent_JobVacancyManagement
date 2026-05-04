<?php
class JobVacancy {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getEmployerProfileByUserId($userId) {
        return $this->db->fetch(
            'SELECT id, user_id, company_name, company_website, company_description
             FROM employer_profiles
             WHERE user_id = ?',
            [(int)$userId]
        );
    }

    public function countByEmployer($employerId) {
        return $this->db->count(
            'SELECT COUNT(*) FROM job_vacancies WHERE employer_id = ? AND status <> ?',
            [(int)$employerId, 'removed']
        );
    }

    public function countByEmployerAndStatus($employerId, $status) {
        return $this->db->count(
            'SELECT COUNT(*) FROM job_vacancies WHERE employer_id = ? AND status = ?',
            [(int)$employerId, $status]
        );
    }

    public function countAllJobs() {
        return $this->db->count('SELECT COUNT(*) FROM job_vacancies');
    }

    public function countJobsByStatus($status) {
        return $this->db->count(
            'SELECT COUNT(*) FROM job_vacancies WHERE status = ?',
            [$status]
        );
    }

    public function getRecentForAdmin($limit = 6) {
        $limit = max(1, (int)$limit);

        return $this->db->fetchAll(
            "SELECT jv.id,
                    jt.name AS job_title_name,
                    ep.company_name,
                    u.full_name AS employer_name,
                    jv.status,
                    jv.created_at
             FROM job_vacancies jv
             INNER JOIN job_titles jt ON jt.id = jv.job_title_id
             INNER JOIN employer_profiles ep ON ep.id = jv.employer_id
             INNER JOIN users u ON u.id = ep.user_id
             ORDER BY jv.created_at DESC, jv.id DESC
             LIMIT {$limit}"
        );
    }

    public function getAllForAdmin() {
        return $this->db->fetchAll(
            'SELECT jv.id,
                    jt.name AS job_title_name,
                    jc.name AS job_category_name,
                    ep.company_name,
                    u.full_name AS employer_name,
                    co.name AS country_name,
                    c.name AS city_name,
                    d.name AS district_name,
                    wa.name AS work_arrangement_name,
                    sr.label AS salary_range_label,
                    jv.status,
                    jv.created_at
             FROM job_vacancies jv
             INNER JOIN employer_profiles ep ON ep.id = jv.employer_id
             INNER JOIN users u ON u.id = ep.user_id
             INNER JOIN job_titles jt ON jt.id = jv.job_title_id
             INNER JOIN job_categories jc ON jc.id = jv.job_category_id
             INNER JOIN countries co ON co.id = jv.country_id
             INNER JOIN cities c ON c.id = jv.city_id
             LEFT JOIN districts d ON d.id = jv.district_id
             INNER JOIN work_arrangements wa ON wa.id = jv.work_arrangement_id
             INNER JOIN salary_ranges sr ON sr.id = jv.salary_range_id
             ORDER BY jv.created_at DESC, jv.id DESC'
        );
    }

    public function findById($jobId) {
        return $this->db->fetch(
            'SELECT * FROM job_vacancies WHERE id = ?',
            [(int)$jobId]
        );
    }

    public function getDetailForAdmin($jobId) {
        return $this->db->fetch(
            'SELECT jv.*,
                    u.full_name AS employer_name,
                    u.email AS employer_email,
                    ep.company_name,
                    ep.company_website,
                    ep.company_description,
                    jt.name AS job_title_name,
                    jc.name AS job_category_name,
                    et.name AS employment_type_name,
                    i.name AS industry_name,
                    jl.name AS job_level_name,
                    co.name AS country_name,
                    c.name AS city_name,
                    d.name AS district_name,
                    wa.name AS work_arrangement_name,
                    sr.label AS salary_range_label,
                    st.name AS salary_type_name,
                    dl.name AS degree_level_name,
                    el.name AS experience_level_name
             FROM job_vacancies jv
             INNER JOIN employer_profiles ep ON ep.id = jv.employer_id
             INNER JOIN users u ON u.id = ep.user_id
             INNER JOIN job_titles jt ON jt.id = jv.job_title_id
             INNER JOIN job_categories jc ON jc.id = jv.job_category_id
             INNER JOIN employment_types et ON et.id = jv.employment_type_id
             INNER JOIN industries i ON i.id = jv.industry_id
             INNER JOIN job_levels jl ON jl.id = jv.job_level_id
             INNER JOIN countries co ON co.id = jv.country_id
             INNER JOIN cities c ON c.id = jv.city_id
             LEFT JOIN districts d ON d.id = jv.district_id
             INNER JOIN work_arrangements wa ON wa.id = jv.work_arrangement_id
             INNER JOIN salary_ranges sr ON sr.id = jv.salary_range_id
             INNER JOIN salary_types st ON st.id = jv.salary_type_id
             INNER JOIN degree_levels dl ON dl.id = jv.degree_level_id
             INNER JOIN experience_levels el ON el.id = jv.experience_level_id
             WHERE jv.id = ?',
            [(int)$jobId]
        );
    }

    public function setStatusByAdmin($jobId, $status) {
        return $this->db->execute(
            'UPDATE job_vacancies SET status = ? WHERE id = ?',
            [$status, (int)$jobId]
        );
    }

    public function getRecentByEmployer($employerId, $limit = 5) {
        $limit = max(1, (int)$limit);
        return $this->db->fetchAll(
            "SELECT jv.id, jt.name AS job_title_name, jv.status, jv.created_at
             FROM job_vacancies jv
             INNER JOIN job_titles jt ON jt.id = jv.job_title_id
             WHERE jv.employer_id = ? AND jv.status <> ?
             ORDER BY jv.created_at DESC
             LIMIT {$limit}",
            [(int)$employerId, 'removed']
        );
    }

    public function getAllByEmployer($employerId) {
        return $this->db->fetchAll(
            'SELECT jv.id, jt.name AS job_title_name, jc.name AS job_category_name,
                    et.name AS employment_type_name, c.name AS city_name, d.name AS district_name,
                    co.name AS country_name, wa.name AS work_arrangement_name,
                    sr.label AS salary_range_label, st.name AS salary_type_name,
                    jv.status, jv.created_at
             FROM job_vacancies jv
             INNER JOIN job_titles jt ON jt.id = jv.job_title_id
             INNER JOIN job_categories jc ON jc.id = jv.job_category_id
             INNER JOIN employment_types et ON et.id = jv.employment_type_id
             INNER JOIN countries co ON co.id = jv.country_id
             INNER JOIN cities c ON c.id = jv.city_id
             LEFT JOIN districts d ON d.id = jv.district_id
             INNER JOIN work_arrangements wa ON wa.id = jv.work_arrangement_id
             INNER JOIN salary_ranges sr ON sr.id = jv.salary_range_id
             INNER JOIN salary_types st ON st.id = jv.salary_type_id
             WHERE jv.employer_id = ? AND jv.status <> ?
             ORDER BY jv.created_at DESC, jv.id DESC',
            [(int)$employerId, 'removed']
        );
    }

    public function findByIdAndEmployer($jobId, $employerId) {
        return $this->db->fetch(
            'SELECT *
             FROM job_vacancies
             WHERE id = ? AND employer_id = ? AND status <> ?',
            [(int)$jobId, (int)$employerId, 'removed']
        );
    }

    public function getDetailByEmployer($jobId, $employerId) {
        return $this->db->fetch(
            'SELECT jv.*,
                    jt.name AS job_title_name,
                    jc.name AS job_category_name,
                    et.name AS employment_type_name,
                    i.name AS industry_name,
                    jl.name AS job_level_name,
                    co.name AS country_name,
                    c.name AS city_name,
                    d.name AS district_name,
                    wa.name AS work_arrangement_name,
                    sr.label AS salary_range_label,
                    st.name AS salary_type_name,
                    dl.name AS degree_level_name,
                    el.name AS experience_level_name
             FROM job_vacancies jv
             INNER JOIN job_titles jt ON jt.id = jv.job_title_id
             INNER JOIN job_categories jc ON jc.id = jv.job_category_id
             INNER JOIN employment_types et ON et.id = jv.employment_type_id
             INNER JOIN industries i ON i.id = jv.industry_id
             INNER JOIN job_levels jl ON jl.id = jv.job_level_id
             INNER JOIN countries co ON co.id = jv.country_id
             INNER JOIN cities c ON c.id = jv.city_id
             LEFT JOIN districts d ON d.id = jv.district_id
             INNER JOIN work_arrangements wa ON wa.id = jv.work_arrangement_id
             INNER JOIN salary_ranges sr ON sr.id = jv.salary_range_id
             INNER JOIN salary_types st ON st.id = jv.salary_type_id
             INNER JOIN degree_levels dl ON dl.id = jv.degree_level_id
             INNER JOIN experience_levels el ON el.id = jv.experience_level_id
             WHERE jv.id = ? AND jv.employer_id = ? AND jv.status <> ?',
            [(int)$jobId, (int)$employerId, 'removed']
        );
    }

    public function create($data) {
        return $this->db->insert('job_vacancies', $data);
    }

    public function update($jobId, $employerId, $data) {
        $assignments = [];
        foreach (array_keys($data) as $column) {
            $assignments[] = "{$column} = :{$column}";
        }

        $data['id'] = (int)$jobId;
        $data['employer_id'] = (int)$employerId;

        return $this->db->execute(
            'UPDATE job_vacancies
             SET ' . implode(', ', $assignments) . '
             WHERE id = :id AND employer_id = :employer_id AND status <> \'removed\'',
            $data
        );
    }

    public function delete($jobId, $employerId) {
        return $this->db->execute(
            'DELETE FROM job_vacancies WHERE id = ? AND employer_id = ? AND status <> ?',
            [(int)$jobId, (int)$employerId, 'removed']
        );
    }

    public function getLatestActiveJobs($limit = 6) {
        $limit = max(1, (int)$limit);
        return $this->db->fetchAll(
            "SELECT jv.id, jv.created_at,
                    jt.name AS job_title_name,
                    jc.name AS job_category_name,
                    ep.company_name,
                    co.name AS country_name,
                    c.name AS city_name,
                    d.name AS district_name,
                    et.name AS employment_type_name,
                    wa.name AS work_arrangement_name,
                    sr.label AS salary_range_label
             FROM job_vacancies jv
             INNER JOIN job_titles jt ON jt.id = jv.job_title_id
             INNER JOIN job_categories jc ON jc.id = jv.job_category_id
             INNER JOIN employer_profiles ep ON ep.id = jv.employer_id
             INNER JOIN countries co ON co.id = jv.country_id
             INNER JOIN cities c ON c.id = jv.city_id
             LEFT JOIN districts d ON d.id = jv.district_id
             INNER JOIN employment_types et ON et.id = jv.employment_type_id
             INNER JOIN work_arrangements wa ON wa.id = jv.work_arrangement_id
             INNER JOIN salary_ranges sr ON sr.id = jv.salary_range_id
             WHERE jv.status = 'active'
             ORDER BY jv.created_at DESC, jv.id DESC
             LIMIT {$limit}"
        );
    }

    /**
     * Build the WHERE clause + parameter array for an active-job search.
     * Returns [string $where, array $params].
     */
    private function buildSearchWhere($filters = []) {
        $where  = ['jv.status = ?'];
        $params = ['active'];

        if (!empty($filters['keyword'])) {
            $kw = '%' . $filters['keyword'] . '%';
            $where[] = '(jt.name LIKE ? OR jv.responsibilities LIKE ? OR jv.required_qualifications LIKE ? OR jv.preferred_skills LIKE ? OR jv.additional_notes LIKE ?)';
            array_push($params, $kw, $kw, $kw, $kw, $kw);
        }
        if (!empty($filters['category_id']))         { $where[] = 'jv.job_category_id = ?';      $params[] = (int)$filters['category_id']; }
        if (!empty($filters['country_id']))          { $where[] = 'jv.country_id = ?';           $params[] = (int)$filters['country_id']; }
        if (!empty($filters['city_id']))             { $where[] = 'jv.city_id = ?';              $params[] = (int)$filters['city_id']; }
        if (!empty($filters['district_id']))         { $where[] = 'jv.district_id = ?';          $params[] = (int)$filters['district_id']; }
        if (!empty($filters['employment_type_id']))  { $where[] = 'jv.employment_type_id = ?';   $params[] = (int)$filters['employment_type_id']; }
        if (!empty($filters['job_level_id']))        { $where[] = 'jv.job_level_id = ?';         $params[] = (int)$filters['job_level_id']; }
        if (!empty($filters['salary_range_id']))     { $where[] = 'jv.salary_range_id = ?';      $params[] = (int)$filters['salary_range_id']; }
        if (!empty($filters['work_arrangement_id'])) { $where[] = 'jv.work_arrangement_id = ?';  $params[] = (int)$filters['work_arrangement_id']; }
        if (!empty($filters['skill_id'])) {
            $where[]  = 'EXISTS (SELECT 1 FROM job_vacancy_skills jvs_filter WHERE jvs_filter.job_vacancy_id = jv.id AND jvs_filter.skill_id = ?)';
            $params[] = (int)$filters['skill_id'];
        }

        return [implode(' AND ', $where), $params];
    }

    public function countActiveJobs($filters = []) {
        list($where, $params) = $this->buildSearchWhere($filters);
        return $this->db->count(
            'SELECT COUNT(*)
               FROM job_vacancies jv
               INNER JOIN job_titles jt ON jt.id = jv.job_title_id
              WHERE ' . $where,
            $params
        );
    }

    public function searchActiveJobs($filters = [], $sort = 'newest', $limit = null, $offset = 0) {
        list($where, $params) = $this->buildSearchWhere($filters);

        $sql = 'SELECT jv.id, jv.created_at,
                       jt.name AS job_title_name,
                       jc.name AS job_category_name,
                       ep.company_name,
                       co.name AS country_name,
                       c.name AS city_name,
                       d.name AS district_name,
                       et.name AS employment_type_name,
                       wa.name AS work_arrangement_name,
                       sr.label AS salary_range_label,
                       sr.min_salary
                FROM job_vacancies jv
                INNER JOIN job_titles jt ON jt.id = jv.job_title_id
                INNER JOIN job_categories jc ON jc.id = jv.job_category_id
                INNER JOIN employer_profiles ep ON ep.id = jv.employer_id
                INNER JOIN countries co ON co.id = jv.country_id
                INNER JOIN cities c ON c.id = jv.city_id
                LEFT JOIN districts d ON d.id = jv.district_id
                INNER JOIN employment_types et ON et.id = jv.employment_type_id
                INNER JOIN work_arrangements wa ON wa.id = jv.work_arrangement_id
                INNER JOIN salary_ranges sr ON sr.id = jv.salary_range_id
                WHERE ' . $where;

        switch ($sort) {
            case 'salary_asc':
                $sql .= ' ORDER BY sr.min_salary ASC, jv.created_at DESC';
                break;
            case 'salary_desc':
                $sql .= ' ORDER BY sr.min_salary DESC, jv.created_at DESC';
                break;
            case 'title_asc':
                $sql .= ' ORDER BY jt.name ASC, jv.created_at DESC';
                break;
            default:
                $sql .= ' ORDER BY jv.created_at DESC, jv.id DESC';
        }

        if ($limit !== null) {
            $limit  = max(1, (int)$limit);
            $offset = max(0, (int)$offset);
            // LIMIT / OFFSET inlined as integers (already cast) so PDO doesn't
            // try to bind them as strings, which MySQL refuses.
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function getActiveJobDetail($jobId) {
        return $this->db->fetch(
            'SELECT jv.*,
                    jt.name AS job_title_name,
                    jc.name AS job_category_name,
                    et.name AS employment_type_name,
                    i.name AS industry_name,
                    jl.name AS job_level_name,
                    ep.company_name,
                    ep.company_website,
                    ep.company_description,
                    co.name AS country_name,
                    c.name AS city_name,
                    d.name AS district_name,
                    wa.name AS work_arrangement_name,
                    sr.label AS salary_range_label,
                    st.name AS salary_type_name,
                    dl.name AS degree_level_name,
                    el.name AS experience_level_name
             FROM job_vacancies jv
             INNER JOIN job_titles jt ON jt.id = jv.job_title_id
             INNER JOIN job_categories jc ON jc.id = jv.job_category_id
             INNER JOIN employment_types et ON et.id = jv.employment_type_id
             INNER JOIN industries i ON i.id = jv.industry_id
             INNER JOIN job_levels jl ON jl.id = jv.job_level_id
             INNER JOIN employer_profiles ep ON ep.id = jv.employer_id
             INNER JOIN countries co ON co.id = jv.country_id
             INNER JOIN cities c ON c.id = jv.city_id
             LEFT JOIN districts d ON d.id = jv.district_id
             INNER JOIN work_arrangements wa ON wa.id = jv.work_arrangement_id
             INNER JOIN salary_ranges sr ON sr.id = jv.salary_range_id
             INNER JOIN salary_types st ON st.id = jv.salary_type_id
             INNER JOIN degree_levels dl ON dl.id = jv.degree_level_id
             INNER JOIN experience_levels el ON el.id = jv.experience_level_id
             WHERE jv.id = ? AND jv.status = ?',
            [(int)$jobId, 'active']
        );
    }

    public function getSkillsByJobIds($jobIds) {
        if (empty($jobIds)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($jobIds), '?'));
        $rows = $this->db->fetchAll(
            "SELECT jvs.job_vacancy_id, s.name AS skill_name, pl.name AS proficiency_name
             FROM job_vacancy_skills jvs
             INNER JOIN skills s ON s.id = jvs.skill_id
             INNER JOIN proficiency_levels pl ON pl.id = jvs.proficiency_level_id
             WHERE jvs.job_vacancy_id IN ({$placeholders})
             ORDER BY s.name ASC",
            array_map('intval', $jobIds)
        );
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['job_vacancy_id']][] = $row;
        }
        return $grouped;
    }

    public function toggleStatus($jobId, $employerId) {
        $job = $this->findByIdAndEmployer($jobId, $employerId);
        if (!$job || $job['status'] === 'removed') {
            return false;
        }

        $nextStatus = $job['status'] === 'active' ? 'inactive' : 'active';
        $updated = $this->db->execute(
            'UPDATE job_vacancies
             SET status = ?
             WHERE id = ? AND employer_id = ? AND status <> ?',
            [$nextStatus, (int)$jobId, (int)$employerId, 'removed']
        );

        return $updated ? $nextStatus : false;
    }
}
