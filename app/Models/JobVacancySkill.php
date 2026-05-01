<?php
class JobVacancySkill {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getByJobVacancy($jobId) {
        return $this->db->fetchAll(
            'SELECT jvs.skill_id, jvs.proficiency_level_id, s.name AS skill_name, pl.name AS proficiency_name
             FROM job_vacancy_skills jvs
             INNER JOIN skills s ON s.id = jvs.skill_id
             INNER JOIN proficiency_levels pl ON pl.id = jvs.proficiency_level_id
             WHERE jvs.job_vacancy_id = ?
             ORDER BY s.name ASC',
            [(int)$jobId]
        );
    }

    public function replaceSkills($jobId, $skills) {
        $this->deleteByJobVacancy($jobId);

        foreach ($skills as $skill) {
            $this->db->execute(
                'INSERT INTO job_vacancy_skills (job_vacancy_id, skill_id, proficiency_level_id)
                 VALUES (?, ?, ?)',
                [
                    (int)$jobId,
                    (int)$skill['skill_id'],
                    (int)$skill['proficiency_level_id'],
                ]
            );
        }
    }

    public function deleteByJobVacancy($jobId) {
        return $this->db->execute(
            'DELETE FROM job_vacancy_skills WHERE job_vacancy_id = ?',
            [(int)$jobId]
        );
    }
}
