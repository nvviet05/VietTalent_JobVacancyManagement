# Job Vacancy Management & Job Search System / VietTalent

## Project Overview

VietTalent is a PHP/MySQL web application for structured job vacancy management and public job search.

The system supports three main use cases:
- Employers manage their own job vacancies through a protected dashboard.
- Job seekers and visitors browse active jobs, search, filter, sort, and view job details.
- Administrators monitor the platform, manage job visibility, and maintain reference and location data.

## Technologies Used

- XAMPP
- PHP
- MySQL
- HTML
- CSS
- JavaScript

## System Roles

- Employer
- Job Seeker / Visitor
- Administrator

## Main Features

### Employer

- Register and login
- Employer dashboard
- Create job vacancy
- Edit own job vacancy
- View own job vacancy
- Delete own job vacancy
- Activate/deactivate own job vacancy
- Dynamic required skills form
- Employer ownership protection

### Job Seeker / Visitor

- View homepage
- Browse active jobs
- Keyword search
- Multi-criteria filters
- AND logic across filters
- Sorting
- View job detail
- Read-only browsing, no apply workflow

### Administrator

- Admin dashboard
- View all jobs
- View job detail
- Set job status to active, inactive, or removed
- Manage lookup/reference data
- Manage countries, cities, and districts

## Folder Structure

```text
VietTalent_JobVacancyManagement/
├── app/
│   ├── Core/          # Database, router, controller base classes, auth, validation
│   ├── Controllers/   # Home, auth, employer, admin, dashboard, language, public jobs
│   ├── Helpers/       # URL, session, translation helpers
│   └── Models/        # Job, lookup, profile, and relationship data access
├── config/            # App and database configuration
├── database/          # schema.sql and seed.sql
├── docs/              # Database notes, test checklist, demo script
├── public/
│   ├── css/           # Public and dashboard styles
│   ├── js/            # Public and employer form JavaScript
│   └── index.php      # Front controller / entry point
├── resources/
│   └── views/         # Public, auth, employer, admin, partial, layout, and error views
└── storage/           # Reserved local storage area
```

## Database Setup

- Schema file: `database/schema.sql`
- Seed file: `database/seed.sql`
- Configured database name: `viettalent_job_vacancy_db`

### Import with phpMyAdmin

1. Start Apache and MySQL in XAMPP.
2. Open phpMyAdmin.
3. Create a database named `viettalent_job_vacancy_db` if needed.
4. Import `database/schema.sql`.
5. Import `database/seed.sql`.
6. Confirm `config/database.php` matches your local MySQL settings.

## How to Run with XAMPP

1. Put the project in `C:\xampp\htdocs\VietTalent_JobVacancyManagement`
2. Start Apache and MySQL in XAMPP.
3. Import `database/schema.sql` and `database/seed.sql`.
4. Open:

```text
http://localhost/VietTalent_JobVacancyManagement/public/
```

## Default Accounts

The following demo accounts are defined in `database/seed.sql`.

- Admin: `admin@viettalent.local` / `password`
- Employer: `employer@viettalent.local` / `password`
- Job Seeker: `jobseeker@viettalent.local` / `password`

## Main Routes

- Home: `/public/`
- Login: `?page=login`
- Register: `?page=register`
- Employer dashboard: `?page=employer_dashboard`
- Employer jobs: `?page=employer_jobs`
- Employer create job: `?page=employer_job_create`
- Public jobs: `?page=jobs`
- Public job detail: `?page=job_detail&id={jobId}`
- Admin dashboard: `?page=admin_dashboard`
- Admin jobs: `?page=admin_jobs`
- Admin lookup management: `?page=admin_lookup&type=skills`
- Admin countries: `?page=admin_countries`
- Admin cities: `?page=admin_cities`
- Admin districts: `?page=admin_districts`

## Demo Flow

1. Open `http://localhost/VietTalent_JobVacancyManagement/public/`
2. Login as employer: `employer@viettalent.local` / `password`
3. Go to Employer Dashboard → Create New Job → fill all fields → Save (set status to Active)
4. Logout → Browse Jobs from the public page
5. Use keyword search, category filter, location filter, and sort options to find the job
6. Click View Detail to see the full job posting
7. Login as admin: `admin@viettalent.local` / `password`
8. Go to Admin Dashboard → Manage Job Postings → find the job → Set Removed
9. Logout → Browse Jobs again → confirm the removed job no longer appears

## Team Contribution / Phase Contribution

- Phase 1: Foundation, authentication, role guards, base UI/UX, schema, seed data
- Phase 2: Employer vacancy management and required skills workflow
- Phase 3: Public job search, filtering, sorting, and job detail
- Phase 4: Admin dashboard, admin job management, lookup/location management, and final documentation

## Limitations / Out of Scope

The following features are intentionally not implemented:

- No CV creation
- No CV search
- No job application workflow
- No recommendation or matching algorithm
- No email notification workflow
- No chat
- No payment workflow

## Git Workflow

The project is organized phase by phase. A practical workflow for submission is to keep commits grouped by feature area, for example:

- Phase 1 foundation and auth
- Phase 2 employer features
- Phase 3 public search and detail
- Phase 4 admin features and documentation

## Notes

- Public job pages show only jobs with status `active`.
- Admin "remove" is implemented as status `removed`, not a hard delete.
- Structured location is stored through `countries`, `cities`, and `districts`.
- Required skills are stored in a many-to-many relationship through `job_vacancy_skills`.
