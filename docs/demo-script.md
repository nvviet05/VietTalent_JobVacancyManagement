# Demo Script

## 0:00-0:30 Introduction

- Introduce the project as "Job Vacancy Management & Job Search System / VietTalent"
- Mention the tech stack: PHP, MySQL, HTML, CSS, JavaScript on XAMPP
- Explain the three roles: employer, visitor/job seeker, and administrator

## 0:30-1:30 Employer Demo

- Login as the employer demo account
- Open the employer dashboard
- Show employer statistics and recent jobs
- Open Create Job Vacancy
- Highlight the structured form: job information, structured location, salary, education, and skills
- Show the dynamic required skills section and mention the max of 5 skills
- Submit or review a job posting
- Open My Job Postings
- Toggle job status active/inactive

## 1:30-2:40 Public Job Seeker Demo

- Open the public homepage
- Show the latest active jobs section
- Open the public jobs page
- Search by keyword
- Apply filters such as country, city, category, or salary range
- Explain that combined filters use AND logic
- Show sorting options
- Open a job detail page
- Clearly mention that the system is read-only for visitors and there is no apply workflow

## 2:40-3:50 Admin Demo

- Login as the admin demo account
- Open the admin dashboard
- Show platform statistics and quick links
- Open Admin Jobs
- View one job detail
- Set a job to removed
- Return to the public jobs page and show that removed jobs no longer appear
- Reactivate or restore status if needed for the rest of the demo

## 3:50-4:30 Lookup/Location Demo

- Open lookup management, for example Skills or Job Categories
- Create or edit a simple lookup value
- Open Countries, Cities, and Districts management
- Explain that structured location data is managed separately because of parent-child relationships

## 4:30-5:00 Database/Conclusion

- Explain that the database is normalized
- Mention structured location through countries, cities, and districts
- Mention the many-to-many required skills relationship with proficiency level
- Close by stating the intentional scope limits:
  - no CV creation
  - no CV search
  - no job application workflow
  - no recommendation or matching system
