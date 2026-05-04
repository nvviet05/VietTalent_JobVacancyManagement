CREATE DATABASE IF NOT EXISTS viettalent_job_vacancy_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE viettalent_job_vacancy_db;

DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS store_locations;
DROP TABLE IF EXISTS job_vacancy_skills;
DROP TABLE IF EXISTS job_vacancies;
DROP TABLE IF EXISTS job_seeker_profiles;
DROP TABLE IF EXISTS employer_profiles;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS districts;
DROP TABLE IF EXISTS cities;
DROP TABLE IF EXISTS countries;
DROP TABLE IF EXISTS job_titles;
DROP TABLE IF EXISTS job_categories;
DROP TABLE IF EXISTS industries;
DROP TABLE IF EXISTS employment_types;
DROP TABLE IF EXISTS job_levels;
DROP TABLE IF EXISTS salary_ranges;
DROP TABLE IF EXISTS salary_types;
DROP TABLE IF EXISTS skills;
DROP TABLE IF EXISTS proficiency_levels;
DROP TABLE IF EXISTS degree_levels;
DROP TABLE IF EXISTS experience_levels;
DROP TABLE IF EXISTS work_arrangements;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'employer', 'job_seeker') NOT NULL,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE employer_profiles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  company_name VARCHAR(160) NOT NULL,
  company_website VARCHAR(255) NULL,
  company_description TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_employer_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE job_seeker_profiles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_job_seeker_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE job_titles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE job_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE industries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE employment_types (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE job_levels (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE salary_ranges (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(120) NOT NULL UNIQUE,
  min_salary DECIMAL(12,2) NOT NULL,
  max_salary DECIMAL(12,2) NULL,
  currency VARCHAR(10) NOT NULL DEFAULT 'USD',
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE salary_types (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE skills (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE proficiency_levels (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE degree_levels (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE experience_levels (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE work_arrangements (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE countries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE cities (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  country_id INT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  UNIQUE KEY uq_city_country (country_id, name),
  CONSTRAINT fk_city_country FOREIGN KEY (country_id) REFERENCES countries(id)
) ENGINE=InnoDB;

CREATE TABLE districts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  city_id INT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  UNIQUE KEY uq_district_city (city_id, name),
  CONSTRAINT fk_district_city FOREIGN KEY (city_id) REFERENCES cities(id)
) ENGINE=InnoDB;

CREATE TABLE job_vacancies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employer_id INT UNSIGNED NOT NULL,
  job_title_id INT UNSIGNED NOT NULL,
  job_category_id INT UNSIGNED NOT NULL,
  employment_type_id INT UNSIGNED NOT NULL,
  industry_id INT UNSIGNED NOT NULL,
  job_level_id INT UNSIGNED NOT NULL,
  number_of_openings INT UNSIGNED NOT NULL DEFAULT 1,
  country_id INT UNSIGNED NOT NULL,
  city_id INT UNSIGNED NOT NULL,
  district_id INT UNSIGNED NULL,
  work_arrangement_id INT UNSIGNED NOT NULL,
  salary_range_id INT UNSIGNED NOT NULL,
  salary_type_id INT UNSIGNED NOT NULL,
  benefits TEXT NULL,
  responsibilities TEXT NOT NULL,
  required_qualifications TEXT NOT NULL,
  preferred_skills TEXT NULL,
  additional_notes TEXT NULL,
  degree_level_id INT UNSIGNED NOT NULL,
  experience_level_id INT UNSIGNED NOT NULL,
  status ENUM('active', 'inactive', 'removed') NOT NULL DEFAULT 'inactive',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_job_employer FOREIGN KEY (employer_id) REFERENCES employer_profiles(id),
  CONSTRAINT fk_job_title FOREIGN KEY (job_title_id) REFERENCES job_titles(id),
  CONSTRAINT fk_job_category FOREIGN KEY (job_category_id) REFERENCES job_categories(id),
  CONSTRAINT fk_job_employment_type FOREIGN KEY (employment_type_id) REFERENCES employment_types(id),
  CONSTRAINT fk_job_industry FOREIGN KEY (industry_id) REFERENCES industries(id),
  CONSTRAINT fk_job_level FOREIGN KEY (job_level_id) REFERENCES job_levels(id),
  CONSTRAINT fk_job_country FOREIGN KEY (country_id) REFERENCES countries(id),
  CONSTRAINT fk_job_city FOREIGN KEY (city_id) REFERENCES cities(id),
  CONSTRAINT fk_job_district FOREIGN KEY (district_id) REFERENCES districts(id),
  CONSTRAINT fk_job_work_arrangement FOREIGN KEY (work_arrangement_id) REFERENCES work_arrangements(id),
  CONSTRAINT fk_job_salary_range FOREIGN KEY (salary_range_id) REFERENCES salary_ranges(id),
  CONSTRAINT fk_job_salary_type FOREIGN KEY (salary_type_id) REFERENCES salary_types(id),
  CONSTRAINT fk_job_degree FOREIGN KEY (degree_level_id) REFERENCES degree_levels(id),
  CONSTRAINT fk_job_experience FOREIGN KEY (experience_level_id) REFERENCES experience_levels(id)
) ENGINE=InnoDB;

CREATE TABLE job_vacancy_skills (
  job_vacancy_id INT UNSIGNED NOT NULL,
  skill_id INT UNSIGNED NOT NULL,
  proficiency_level_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (job_vacancy_id, skill_id),
  CONSTRAINT fk_jvs_job FOREIGN KEY (job_vacancy_id) REFERENCES job_vacancies(id) ON DELETE CASCADE,
  CONSTRAINT fk_jvs_skill FOREIGN KEY (skill_id) REFERENCES skills(id),
  CONSTRAINT fk_jvs_proficiency FOREIGN KEY (proficiency_level_id) REFERENCES proficiency_levels(id)
) ENGINE=InnoDB;

-- Password reset tokens (Forgot Password feature, criterion #7).
CREATE TABLE password_resets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  token_hash CHAR(64) NOT NULL UNIQUE,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pwreset_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- VietTalent physical store / branch locations (Google Maps page, criterion #6).
CREATE TABLE store_locations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  address VARCHAR(255) NOT NULL,
  city_id INT UNSIGNED NULL,
  phone VARCHAR(40) NULL,
  email VARCHAR(160) NULL,
  hours VARCHAR(120) NULL,
  latitude DECIMAL(10,7) NULL,
  longitude DECIMAL(10,7) NULL,
  map_query VARCHAR(255) NOT NULL,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_store_city FOREIGN KEY (city_id) REFERENCES cities(id)
) ENGINE=InnoDB;
