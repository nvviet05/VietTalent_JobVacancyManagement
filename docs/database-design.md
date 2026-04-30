# Database Design Notes

This Phase 1 schema follows the assignment requirements from the PDFs:

- The system uses a relational MySQL database.
- Selectable fields are stored in reference tables.
- Job location is structured as `countries`, `cities`, and optional `districts`.
- Job vacancies reference lookup records through foreign keys.
- Required skills use a many-to-many table: `job_vacancy_skills`.
- CV creation, CV search, apply-job, and recommendation workflows are not included.

Phase 1 creates the schema only. Job creation, search, filtering, sorting, and admin lookup management are reserved for later phases.
