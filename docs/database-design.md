# Database Design

## 1. Database Overview

The project uses a relational MySQL database designed for normalized job vacancy management. Most selectable job fields are stored in lookup/reference tables instead of free-text columns, and job location is stored as structured country/city/district relationships.

## 2. Main Entity Groups

- Users and profiles
- Job vacancies
- Required skills relationship
- Lookup/reference tables
- Location tables

## 3. Users and Roles

### `users`

Stores authentication and role data:
- `full_name`
- `email`
- `password_hash`
- `role`
- `status`
- `created_at`
- `updated_at`

Supported role values:
- `admin`
- `employer`
- `job_seeker`

### `employer_profiles`

Extends employer users with company information:
- `user_id`
- `company_name`
- `company_website`
- `company_description`

### `job_seeker_profiles`

Provides a separate profile table for job seeker users:
- `user_id`
- `created_at`

## 4. Job Vacancy Design

### `job_vacancies`

This is the main business table. Each row belongs to one employer profile and stores structured references to lookup and location data.

Conceptual field groups:

- Employer owner:
  - `employer_id`
- Basic job information:
  - `job_title_id`
  - `job_category_id`
  - `employment_type_id`
  - `industry_id`
  - `job_level_id`
  - `number_of_openings`
- Structured location:
  - `country_id`
  - `city_id`
  - `district_id`
  - `work_arrangement_id`
- Salary:
  - `salary_range_id`
  - `salary_type_id`
- Free text content:
  - `benefits`
  - `responsibilities`
  - `required_qualifications`
  - `preferred_skills`
  - `additional_notes`
- Education and experience:
  - `degree_level_id`
  - `experience_level_id`
- Workflow:
  - `status`
  - `created_at`
  - `updated_at`

## 5. Lookup Tables

Lookup/reference tables are used so the application can:
- keep select options consistent
- validate foreign key relationships
- support active/inactive management
- avoid repeated free-text values

Main lookup tables:
- `job_titles`
- `job_categories`
- `industries`
- `employment_types`
- `job_levels`
- `salary_ranges`
- `salary_types`
- `skills`
- `proficiency_levels`
- `degree_levels`
- `experience_levels`
- `work_arrangements`

Most of these tables use:
- `id`
- `name` or `label`
- `status`

## 6. Structured Location

Location is not stored as one plain text field.

The schema uses:
- `countries`
- `cities`
- `districts`
- `work_arrangements`

Relationship structure:
- `countries.id -> cities.country_id`
- `cities.id -> districts.city_id`

This allows:
- structured employer job forms
- structured public filters
- safer validation of parent-child location data

## 7. Many-to-Many Required Skills

Required skills are stored through a many-to-many bridge table:

- `job_vacancies`
- `skills`
- `job_vacancy_skills`

### `job_vacancy_skills`

Stores:
- `job_vacancy_id`
- `skill_id`
- `proficiency_level_id`

The application enforces a maximum of 5 required skills and at least 1 required skill through validation logic.

## 8. Job Status

`job_vacancies.status` supports:
- `active`
- `inactive`
- `removed`

Behavior:
- Public pages show only `active` jobs.
- Employer pages hide `removed` jobs from normal employer management.
- Admin "remove" is implemented by setting `status = 'removed'`.

## 9. Salary Sorting

### `salary_ranges`

Stores:
- `label`
- `min_salary`
- `max_salary`
- `currency`
- `status`

The application uses salary range data for display and filtering. For salary sorting, the intended structured basis is the numeric salary range values, especially `min_salary`.

## 10. Free Text Fields

Only the following job vacancy fields are free text:
- `benefits`
- `responsibilities`
- `required_qualifications`
- `preferred_skills`
- `additional_notes`

Everything else important to filtering and validation is modeled through foreign keys or structured numeric fields.

## 11. Normalization Decisions

Key normalization choices in this project:

- Selectable business fields are stored as foreign keys
- Skills are modeled as a many-to-many relationship
- Location is structured through country/city/district tables
- Salary ranges are stored in a dedicated lookup table
- No JSON blob is used for required skills
- No plain text location field is used for job vacancies

This design supports more reliable validation, filtering, admin management, and future extension than a plain text or denormalized approach.
