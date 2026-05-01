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
