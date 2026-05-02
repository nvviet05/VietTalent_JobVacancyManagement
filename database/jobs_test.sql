USE viettalent_job_vacancy_db;

INSERT INTO job_vacancies 
(employer_id, job_title_id, job_category_id, employment_type_id, industry_id, job_level_id, number_of_openings, country_id, city_id, work_arrangement_id, salary_range_id, salary_type_id, responsibilities, required_qualifications, degree_level_id, experience_level_id, status) 
VALUES
-- Job 1: Software Engineer
(1, 1, 1, 1, 1, 1, 2, 1, 1, 1, 3, 1, 
 'Develop and maintain web applications using PHP and MySQL. Collaborate with cross-functional teams to define requirements.', 
 'Bachelor degree in CS. 1-2 years of experience with modern PHP frameworks.', 
 3, 3, 'active'),

-- Job 2: Frontend Developer
(1, 2, 1, 1, 1, 1, 1, 1, 1, 3, 2, 1, 
 'Create responsive user interfaces using HTML, CSS, and JavaScript. Ensure high-quality graphic standards and brand consistency.', 
 'Strong portfolio of web projects. Knowledge of React or Vue is a plus.', 
 3, 2, 'active'),

-- Job 3: Business Analyst
(1, 4, 2, 1, 2, 2, 1, 1, 1, 2, 4, 1, 
 'Evaluate business processes, anticipating requirements, and uncovering areas for improvement. Leading ongoing reviews of business processes.', 
 'Excellent documentation and communication skills. 3-5 years of industry experience.', 
 3, 4, 'active');