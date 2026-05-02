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

    public function getLatestActive($limit = 6) {
        $sql = "SELECT jv.*, jt.name AS job_title_name, ep.company_name, 
                       c.name AS city_name, et.name AS employment_type_name, 
                       wa.name AS work_arrangement_name, sr.label AS salary_range_label
                FROM job_vacancies jv
                JOIN job_titles jt ON jv.job_title_id = jt.id
                JOIN employer_profiles ep ON jv.employer_id = ep.id
                LEFT JOIN cities c ON jv.city_id = c.id
                LEFT JOIN employment_types et ON jv.employment_type_id = et.id
                LEFT JOIN work_arrangements wa ON jv.work_arrangement_id = wa.id
                LEFT JOIN salary_ranges sr ON jv.salary_range_id = sr.id
                WHERE jv.status = 'active' ORDER BY jv.created_at DESC LIMIT " . (int)$limit;
        return $this->db->fetchAll($sql);
    }

    public function getActiveJobById($id) {
        $sql = "SELECT jv.*, jt.name AS job_title_name, ep.company_name, ep.company_description, ep.company_website,
                       c.name AS city_name, et.name AS employment_type_name, wa.name AS work_arrangement_name, 
                       jl.name AS job_level_name, sr.label AS salary_range_label
                FROM job_vacancies jv
                JOIN job_titles jt ON jv.job_title_id = jt.id
                JOIN employer_profiles ep ON jv.employer_id = ep.id
                LEFT JOIN cities c ON jv.city_id = c.id
                LEFT JOIN employment_types et ON jv.employment_type_id = et.id
                LEFT JOIN work_arrangements wa ON jv.work_arrangement_id = wa.id
                LEFT JOIN job_levels jl ON jv.job_level_id = jl.id
                LEFT JOIN salary_ranges sr ON jv.salary_range_id = sr.id
                WHERE jv.id = ? AND jv.status = 'active'";
        return $this->db->fetch($sql, [(int)$id]);
    }

    public function searchActiveJobs($filters = []) {
    // 1. Khởi tạo câu lệnh SQL cơ bản với điều kiện bắt buộc là status = 'active'
    $sql = "SELECT jv.*, jt.name as job_title_name, ep.company_name, c.name as city_name, 
                   et.name as employment_type_name, wa.name as work_arrangement_name, sr.label as salary_range_label
            FROM job_vacancies jv
            JOIN job_titles jt ON jv.job_title_id = jt.id
            JOIN employer_profiles ep ON jv.employer_id = ep.id
            JOIN cities c ON jv.city_id = c.id
            JOIN employment_types et ON jv.employment_type_id = et.id
            JOIN work_arrangements wa ON jv.work_arrangement_id = wa.id
            JOIN salary_ranges sr ON jv.salary_range_id = sr.id
            WHERE jv.status = 'active'";

    $params = [];

    // 2. AND Keyword condition (Tìm trong tiêu đề, công ty, mô tả)
    if (!empty($filters['keyword'])) {
        $sql .= " AND (jt.name LIKE :k1 OR ep.company_name LIKE :k2 OR jv.responsibilities LIKE :k3 OR jv.required_qualifications LIKE :k4)";
        $keyword = '%' . $filters['keyword'] . '%';
        $params['k1'] = $keyword;
        $params['k2'] = $keyword;
        $params['k3'] = $keyword;
        $params['k4'] = $keyword;
    }

    // 3. AND Category condition
    if (!empty($filters['category_id'])) {
        $sql .= " AND jv.job_category_id = :category_id";
        $params['category_id'] = $filters['category_id'];
    }

    // 4. AND Country condition
    if (!empty($filters['country_id'])) {
        $sql .= " AND jv.country_id = :country_id";
        $params['country_id'] = $filters['country_id'];
    }

    // 5. AND City condition
    if (!empty($filters['city_id'])) {
        $sql .= " AND jv.city_id = :city_id";
        $params['city_id'] = $filters['city_id'];
    }

    // 6. AND Employment Type condition
    if (!empty($filters['employment_type_id'])) {
        $sql .= " AND jv.employment_type_id = :employment_type_id";
        $params['employment_type_id'] = $filters['employment_type_id'];
    }

    // 7. AND Job Level condition
    if (!empty($filters['job_level_id'])) {
        $sql .= " AND jv.job_level_id = :job_level_id";
        $params['job_level_id'] = $filters['job_level_id'];
    }

    // 8. AND Salary Range condition
    if (!empty($filters['salary_range_id'])) {
        $sql .= " AND jv.salary_range_id = :salary_range_id";
        $params['salary_range_id'] = $filters['salary_range_id'];
    }

    // 9. AND Work Arrangement condition
    if (!empty($filters['work_arrangement_id'])) {
        $sql .= " AND jv.work_arrangement_id = :work_arrangement_id";
        $params['work_arrangement_id'] = $filters['work_arrangement_id'];
    }

    // 10. AND Skill condition (Sử dụng EXISTS để kiểm tra bảng quan hệ n-n)
    if (!empty($filters['skill_id'])) {
        $sql .= " AND EXISTS (
            SELECT 1 FROM job_vacancy_skills jvs 
            WHERE jvs.job_vacancy_id = jv.id AND jvs.skill_id = :skill_id
        )";
        $params['skill_id'] = $filters['skill_id'];
    }

    // 11. Xử lý sắp xếp (Sorting)
    $sort = $filters['sort'] ?? 'newest';
    
    switch ($sort) {
        case 'salary_desc':
            // Lương cao đến thấp (Giả sử salary_range_id lớn hơn là lương cao hơn)
            $sql .= " ORDER BY jv.salary_range_id DESC, jv.created_at DESC";
            break;
        case 'salary_asc':
            // Lương thấp đến cao
            $sql .= " ORDER BY jv.salary_range_id ASC, jv.created_at DESC";
            break;
        case 'title_asc':
            // Tên công việc từ A-Z (Dùng bảng jt - job_titles)
            $sql .= " ORDER BY jt.name ASC, jv.created_at DESC";
            break;
        case 'newest':
        default:
            // Mặc định: Mới nhất xếp trước
            $sql .= " ORDER BY jv.created_at DESC";
            break;
    }

    // 11. Thực thi câu lệnh SQL và trả về kết quả
    return $this->db->fetchAll($sql, $params);

}
}
