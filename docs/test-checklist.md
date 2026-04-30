# Phase 1 Test Checklist

- Open `http://localhost/VietTalent_JobVacancyManagement/public/`.
- Import `database/schema.sql`.
- Import `database/seed.sql`.
- Login with `admin@viettalent.local` / `password`.
- Login with `employer@viettalent.local` / `password`.
- Login with `jobseeker@viettalent.local` / `password`.
- Register a new employer and confirm company name is required.
- Register a new job seeker and confirm company name is not required.
- Try visiting an admin dashboard as an employer and confirm 403.
- Try an invalid route and confirm 404.
