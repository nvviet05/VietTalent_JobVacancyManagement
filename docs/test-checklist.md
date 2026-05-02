# Test Checklist

## A. Phase 1 - Auth/Foundation

- Home loads
- Login loads
- Register loads
- Register employer
- Register job seeker
- Login admin
- Login employer
- Login job seeker
- Logout
- Role-based redirect
- 403 page
- 404 page
- EN/VI switch
- Logo/header/footer

## B. Phase 2 - Employer

- Employer dashboard loads
- My Job Postings loads
- Empty state works
- Create job with all fields
- Required skills min 1
- Required skills max 5
- Duplicate skill rejected
- `job_vacancies` record created
- `job_vacancy_skills` records created
- Edit own job
- Skill sync on edit
- View own job
- Activate/deactivate own job
- Delete own job
- Employer A cannot view Employer B job
- Employer A cannot edit Employer B job
- Employer A cannot delete Employer B job
- Employer A cannot toggle Employer B job

## C. Phase 3 - Public/Job Seeker

- Home latest active jobs
- Public jobs page loads
- Only active jobs shown
- Inactive jobs hidden
- Removed jobs hidden
- Keyword search
- Category filter
- Country filter
- City filter
- Skill filter
- Employment type filter
- Job level filter
- Salary range filter
- Work arrangement filter
- Combined filters use AND logic
- Sorting newest
- Sorting salary low-high
- Sorting salary high-low
- Sorting title A-Z
- Job detail active job
- Inactive job detail blocked
- Removed job detail blocked
- No apply workflow

## D. Phase 4 - Admin

- Admin dashboard loads
- Visitor cannot access admin pages
- Employer cannot access admin pages
- Job seeker cannot access admin pages
- Admin stats correct
- Admin jobs listing loads
- Admin sees all jobs
- Admin job detail loads
- Admin set active
- Admin set inactive
- Admin set removed
- Removed job hidden from public pages
- Admin lookup list
- Admin create lookup
- Admin edit lookup
- Admin activate/deactivate lookup
- Invalid lookup type blocked
- Admin manage countries
- Admin manage cities
- Admin manage districts
- Non-admin cannot access location pages

## E. Security

- Prepared statements used
- Output escaped
- Role guards work
- Employer ownership protection works
- Admin lookup type whitelist works
- No raw table name from GET
- Invalid IDs handled safely

## F. Responsive/UI

- Home responsive
- Public jobs responsive
- Job detail responsive
- Employer dashboard responsive enough
- Admin dashboard responsive enough
- Tables usable on mobile

## G. Syntax

- PHP syntax check for changed PHP files
- JS syntax check for changed JS files if applicable

## Final Manual URLs

- Home: `http://localhost/Job-Vacancy-Management/public/`
- Login: `http://localhost/Job-Vacancy-Management/public/index.php?page=login`
- Register: `http://localhost/Job-Vacancy-Management/public/index.php?page=register`
- Employer dashboard: `http://localhost/Job-Vacancy-Management/public/index.php?page=employer_dashboard`
- Employer create job: `http://localhost/Job-Vacancy-Management/public/index.php?page=employer_job_create`
- Public jobs: `http://localhost/Job-Vacancy-Management/public/index.php?page=jobs`
- Admin dashboard: `http://localhost/Job-Vacancy-Management/public/index.php?page=admin_dashboard`
- Admin jobs: `http://localhost/Job-Vacancy-Management/public/index.php?page=admin_jobs`
- Admin lookup: `http://localhost/Job-Vacancy-Management/public/index.php?page=admin_lookup&type=skills`
- Admin countries: `http://localhost/Job-Vacancy-Management/public/index.php?page=admin_countries`

## Final Regression Expectations

- No PHP fatal error
- No SQL error
- No obvious broken layout
- No apply workflow appears
- Role guards work
