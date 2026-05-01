# Test Checklist

## Phase 1 Regression

- Open `http://localhost/VietTalent_JobVacancyManagement/public/`.
- Import `database/schema.sql`.
- Import `database/seed.sql`.
- Home page returns HTTP 200.
- Login page returns HTTP 200.
- Register page returns HTTP 200.
- Login with `admin@viettalent.local` / `password`.
- Login with `employer@viettalent.local` / `password`.
- Login with `jobseeker@viettalent.local` / `password`.
- Register a new employer and confirm company name is required.
- Register a new job seeker and confirm company name is not required.
- Confirm EN/VI switching still works on public, auth, and dashboard pages.
- Confirm role-based redirect still works after login.
- Try visiting an admin dashboard as an employer and confirm 403.
- Try an invalid route and confirm 404.

## Phase 2 Employer Features

- Employer login succeeds and `?page=employer_dashboard` loads.
- Visitor cannot access employer pages and is redirected to login.
- Job seeker cannot access employer pages.
- Admin cannot access employer pages.
- Employer dashboard shows total jobs, active jobs, inactive jobs, and recent jobs scoped only to the current employer.
- `?page=employer_jobs` lists only the current employer's job postings.
- Empty state appears when an employer has no jobs.
- `?page=employer_job_create` loads all lookup selects.
- Create a job with valid data and confirm a record is inserted into `job_vacancies`.
- Confirm required skills insert into `job_vacancy_skills`.
- Submit create form with missing required fields and confirm validation errors display.
- Submit create form with 0 required skills and confirm rejection.
- Submit create form with more than 5 required skills and confirm rejection.
- Submit create form with duplicate required skills and confirm rejection.
- Open `?page=employer_job_view&id={jobId}` and confirm only the employer owner can view it.
- Open `?page=employer_job_edit&id={jobId}` and confirm existing values and skills pre-fill correctly.
- Update a job and confirm `job_vacancies` changes persist.
- Update required skills and confirm `job_vacancy_skills` syncs correctly.
- Activate an inactive job and confirm status becomes `active`.
- Deactivate an active job and confirm status becomes `inactive`.
- Delete a job and confirm the employer can no longer see it.
- Confirm related skill rows are removed safely when a job is deleted.
- Confirm Employer A cannot view, edit, delete, or toggle Employer B's job posting.
- Confirm there are no obvious console errors on employer pages.

## Syntax Checks

- Run PHP syntax checks for changed PHP files.
- Run JS syntax check for `public/js/job-form.js`.
