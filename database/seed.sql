USE viettalent_job_vacancy_db;

INSERT INTO users (id, full_name, email, password_hash, role, status) VALUES
(1, 'System Administrator', 'admin@viettalent.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi', 'admin', 'active'),
(2, 'Demo Employer', 'employer@viettalent.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi', 'employer', 'active'),
(3, 'Demo Job Seeker', 'jobseeker@viettalent.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi', 'job_seeker', 'active');

INSERT INTO employer_profiles (id, user_id, company_name, company_website, company_description) VALUES
(1, 2, 'VietTalent Demo Company', 'https://example.com', 'Demo employer profile for Phase 1 authentication testing.');

INSERT INTO job_seeker_profiles (id, user_id) VALUES
(1, 3);

INSERT INTO job_titles (name) VALUES
('Software Engineer'), ('Frontend Developer'), ('Backend Developer'), ('Business Analyst'), ('Data Analyst');

INSERT INTO job_categories (name) VALUES
('Information Technology'), ('Business'), ('Marketing'), ('Finance'), ('Human Resources');

INSERT INTO industries (name) VALUES
('Software'), ('E-commerce'), ('Banking'), ('Education'), ('Manufacturing');

INSERT INTO employment_types (name) VALUES
('Full-time'), ('Part-time'), ('Contract'), ('Internship');

INSERT INTO job_levels (name) VALUES
('Junior'), ('Mid'), ('Senior');

INSERT INTO salary_ranges (label, min_salary, max_salary, currency) VALUES
('Under 500 USD', 0, 499, 'USD'),
('500-1000 USD', 500, 1000, 'USD'),
('1000-1500 USD', 1000, 1500, 'USD'),
('1500-2500 USD', 1500, 2500, 'USD'),
('2500+ USD', 2500, NULL, 'USD');

INSERT INTO salary_types (name) VALUES
('Gross'), ('Net');

INSERT INTO skills (name) VALUES
('PHP'), ('MySQL'), ('JavaScript'), ('HTML'), ('CSS'), ('Communication'), ('Problem Solving');

INSERT INTO proficiency_levels (name) VALUES
('Beginner'), ('Intermediate'), ('Advanced');

INSERT INTO degree_levels (name) VALUES
('High School'), ('Associate Degree'), ('Bachelor Degree'), ('Master Degree');

INSERT INTO experience_levels (name) VALUES
('No experience'), ('Less than 1 year'), ('1-2 years'), ('3-5 years'), ('5+ years');

INSERT INTO work_arrangements (name) VALUES
('Onsite'), ('Remote'), ('Hybrid');

INSERT INTO countries (id, name) VALUES
(1, 'Vietnam');

INSERT INTO cities (id, country_id, name) VALUES
(1, 1, 'Ho Chi Minh City'),
(2, 1, 'Ha Noi'),
(3, 1, 'Da Nang');

INSERT INTO districts (city_id, name) VALUES
(1, 'District 1'),
(1, 'District 3'),
(1, 'Binh Thanh'),
(2, 'Ba Dinh'),
(2, 'Cau Giay'),
(3, 'Hai Chau');
