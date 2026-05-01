# VietTalent Job Vacancy Management

Phase 1 foundation plus Phase 2 employer job vacancy management for the Job Vacancy Management & Job Search System assignment.

## Tech Stack

- XAMPP
- PHP
- MySQL
- HTML
- CSS
- JavaScript
- No frontend framework

## Local URL

```txt
http://localhost/VietTalent_JobVacancyManagement/public/
```

## Setup

1. Start Apache and MySQL in XAMPP.
2. Create/import the database by running `database/schema.sql`.
3. Load seed data by running `database/seed.sql`.
4. Check database credentials in `config/database.php`.
5. Open the local URL above.

## Seed Accounts

All seed accounts use password:

```txt
password
```

- Admin: `admin@viettalent.local`
- Employer: `employer@viettalent.local`
- Job Seeker: `jobseeker@viettalent.local`

## Implemented Scope

- Required project folder structure
- Public entry point at `public/index.php`
- MVC-like core classes
- Database configuration
- Normalized schema and seed data
- Login, register, logout
- Password hashing and verification
- Session authentication
- Role-based dashboard redirects
- Role guard with 403 handling
- Base responsive UI/UX for public, auth, dashboard, 403, and 404 pages
- Employer dashboard with company-scoped job statistics
- Employer-only job vacancy create, view, edit, update, delete, and activate/deactivate actions
- Dynamic required skills form with server-side validation for 1 to 5 skills and duplicate prevention

## Employer Phase 2 Routes

- `?page=employer_dashboard`
- `?page=employer_jobs`
- `?page=employer_job_create`
- `?page=employer_job_store`
- `?page=employer_job_view&id={jobId}`
- `?page=employer_job_edit&id={jobId}`
- `?page=employer_job_update&id={jobId}`
- `?page=employer_job_delete&id={jobId}`
- `?page=employer_job_toggle_status&id={jobId}`

## Employer Workflow

1. Login with an employer account.
2. Open `?page=employer_dashboard` to review job stats and recent postings.
3. Open `?page=employer_job_create` to create a vacancy with structured location, salary, and skill data.
4. Add 1 to 5 required skills and submit the form.
5. Review the posting from `?page=employer_jobs` or `?page=employer_job_view&id={jobId}`.
6. Edit, activate/deactivate, or delete only your own postings.

## Out Of Scope

- Public job search/filter/sort
- Public job detail browsing workflow
- Admin job moderation
- Admin lookup/location management
- CV creation/search
- Apply job workflow
- Recommendation or matching workflow
