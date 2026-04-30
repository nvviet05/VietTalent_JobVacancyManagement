# VietTalent Job Vacancy Management

Phase 1 implementation for the Job Vacancy Management & Job Search System assignment.

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

## Phase 1 Scope

Implemented:

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

Not implemented in Phase 1:

- Employer job vacancy CRUD
- Public job search/filter/sort
- Job detail view
- Admin job moderation
- Admin lookup/location management
- CV creation/search
- Apply job workflow
- Recommendation or matching workflow
