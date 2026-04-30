# Final Master Plan — Job Vacancy Management & Job Search System

> Project path: `C:\BKU\Web_Programming\xampp\htdocs\JobVaccancyManagement_JobSearchSystem`  
> Local URL dự kiến: `http://localhost/JobVaccancyManagement_JobSearchSystem/public/`  
> Tech stack: **XAMPP + PHP + MySQL + HTML + CSS + JavaScript**  
> UI direction: lấy cảm hứng từ **CareerViet** nhưng chỉ làm vừa đủ theo requirement.

---

## 0. Double-check kết luận sau khi rà soát 2 file yêu cầu

Sau khi kiểm tra lại 2 file:

1. `Assignment 2.Job Vacancy Management Job Search System.pdf`
2. `Website Project Folder Structure.pdf`

Kế hoạch 4 phase đã bám đúng các yêu cầu chính:

| Nhóm yêu cầu | Đã cover trong phase nào | Ghi chú |
|---|---:|---|
| System Roles: Employer, Job Seeker, Administrator | Phase 1–4 | Có auth, role-based access control, dashboard riêng |
| Employer register/login securely | Phase 1 | Dùng `password_hash()`, session, role guard |
| Employer create structured job vacancy | Phase 2 | Form chia đúng A–F theo đề |
| Employer edit/delete/activate/deactivate/view own jobs | Phase 2 | Có ownership protection |
| Job Seeker search/filter/view only | Phase 3 | Không làm apply job |
| Combined filters dùng AND logic | Phase 3 | Search query build bằng nhiều điều kiện `AND` |
| Sorting: newest, salary asc/desc, title A-Z | Phase 3 | Cần `salary_ranges.min_salary` để sort |
| Admin manage job vacancies | Phase 4 | Admin xem tất cả job, remove invalid/inappropriate job |
| Admin manage reference data | Phase 4 | Quản lý job categories, titles, skills, industries, locations, employment types, job levels, salary ranges |
| Relational database | Phase 1 | MySQL |
| Job vacancy normalized | Phase 1–2 | Selectable fields lưu bằng foreign key |
| Lookup/reference tables cho selectable fields | Phase 1–4 | Có bảng lookup riêng |
| Many-to-many job vacancies ↔ required skills | Phase 1–2 | `job_vacancy_skills` |
| Không lưu requirements/location bằng text/JSON | Phase 1–2 | Location tách country/city/district/work arrangement |
| MVC hoặc equivalent | Phase 1 | MVC-like PHP thuần |
| Dynamic form cho required skills | Phase 2 | Add/remove skill row, tối đa 5 |
| Server-side validation | Phase 1–4 | Auth, job form, lookup form |
| Responsive UI | Phase 3–4 | Public pages + dashboards responsive |
| Git commit history | Phase 1–4 | Chia phase/branch/commit rõ theo từng member |
| Deliverables: source, ERD, SQL schema, report, demo video, git repo | Phase 4 | Đóng gói cuối |

Điểm cần chỉnh nhẹ so với plan ban đầu:

1. **Không nên dùng Job Title dạng text input**. Theo đề, `Job Title` là **Select**, nên phải có bảng `job_titles`.
2. **Salary range nên có `min_salary` và `max_salary`** để làm sorting salary asc/desc.
3. **Admin nên remove job bằng status `removed`**, không nên hard delete, để dễ demo và không phá dữ liệu.
4. **Employer delete có thể hard delete hoặc soft delete**, nhưng nếu muốn nhất quán, nên dùng `status = deleted` hoặc `inactive`. Tuy nhiên đề ghi delete, nên cách đơn giản là hard delete job của chính employer, còn admin dùng remove.
5. **Job seeker không cần login vẫn có thể search/view jobs**, vì requirement chỉ nói job seeker search/view read-only, không bắt bắt buộc login.
6. **Không làm CV creation/search, apply workflow, recommendation/matching** vì đây là phần bị loại khỏi scope.

---

## 1. Project Scope

### 1.1 Mục tiêu project

Xây dựng một website quản lý tin tuyển dụng và tìm kiếm việc làm, tập trung vào phía **Employer**.

Hệ thống cho phép:

- Employer đăng ký, đăng nhập, tạo và quản lý job vacancies.
- Job Seeker/visitor tìm kiếm, lọc, sắp xếp và xem chi tiết job vacancies.
- Administrator quản lý job postings và reference data.

### 1.2 Những gì KHÔNG làm

Không làm các chức năng sau vì ngoài scope assignment:

- Không tạo CV.
- Không tìm kiếm CV.
- Không apply job.
- Không upload CV.
- Không recommendation/matching algorithm.
- Không chat recruiter.
- Không email notification.
- Không payment.

---

## 2. System Roles

### 2.1 Employer

Employer có thể:

- Register/login.
- Xem Employer Dashboard.
- Create job posting.
- Edit job posting của chính mình.
- Delete job posting của chính mình.
- Activate/deactivate job posting.
- View list of own job postings.
- Không được edit/delete/toggle job của employer khác.

### 2.2 Job Seeker

Job Seeker hoặc visitor có thể:

- Xem homepage.
- Search job vacancies.
- Filter job vacancies.
- Sort job vacancies.
- View job detail.
- Không được apply job.
- Không được chỉnh sửa job.

### 2.3 Administrator

Admin có thể:

- Login bằng account seed sẵn.
- Xem toàn bộ job postings.
- Remove inappropriate/invalid jobs.
- Manage reference data:
  - Job categories
  - Job titles
  - Skills
  - Industries
  - Locations
  - Employment types
  - Job levels
  - Salary ranges

---

## 3. Folder Structure

Theo guideline, project nên để web entry point trong `public/index.php`, application logic trong `app/`, config database trong `config/`, CSS/JS/images trong `public/`.

```txt
JobVaccancyManagement_JobSearchSystem/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── EmployerController.php
│   │   ├── PublicJobController.php
│   │   └── AdminController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── EmployerProfile.php
│   │   ├── JobVacancy.php
│   │   ├── JobVacancySkill.php
│   │   └── Lookup.php
│   ├── Core/
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── Auth.php
│   │   ├── Controller.php
│   │   └── Validator.php
│   └── Helpers/
│       ├── url.php
│       └── session.php
│
├── config/
│   ├── app.php
│   └── database.php
│
├── database/
│   ├── schema.sql
│   └── seed.sql
│
├── docs/
│   ├── erd.png
│   ├── database-design.md
│   └── test-checklist.md
│
├── public/
│   ├── index.php
│   ├── css/
│   │   ├── main.css
│   │   ├── auth.css
│   │   ├── jobs.css
│   │   └── dashboard.css
│   ├── js/
│   │   ├── main.js
│   │   ├── job-form.js
│   │   └── filters.js
│   ├── images/
│   └── assets/
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── main.php
│       │   └── dashboard.php
│       ├── partials/
│       │   ├── header.php
│       │   ├── footer.php
│       │   ├── job-card.php
│       │   ├── filter-sidebar.php
│       │   └── flash-message.php
│       ├── auth/
│       ├── employer/
│       ├── jobs/
│       ├── admin/
│       └── errors/
│
├── storage/
│   └── logs/
│
├── README.md
└── .gitignore
```

---

## 4. Database Plan

### 4.1 Database name

```sql
job_vacancy_system_db
```

### 4.2 Auth/Profile tables

#### `users`

```txt
id
full_name
email
password_hash
role              -- admin, employer, job_seeker
status            -- active, inactive
created_at
updated_at
```

#### `employer_profiles`

```txt
id
user_id
company_name
company_size
company_website
company_description
created_at
updated_at
```

#### `job_seeker_profiles`

```txt
id
user_id
phone
created_at
updated_at
```

> Không tạo bảng CV vì đề không yêu cầu CV trong Assignment 2.

---

### 4.3 Lookup/reference tables

Các field dạng select phải lấy từ lookup/reference tables.

#### Required lookup tables

```txt
job_titles
job_categories
industries
employment_types
job_levels
salary_ranges
skills
countries
cities
districts
```

#### Additional lookup tables nên có

```txt
salary_types
degree_levels
experience_levels
work_arrangements
proficiency_levels
```

Mỗi bảng lookup đơn giản nên có:

```txt
id
name
status
created_at
updated_at
```

Riêng `salary_ranges` nên có thêm:

```txt
label
min_salary
max_salary
currency
status
created_at
updated_at
```

Ví dụ:

| label | min_salary | max_salary | currency |
|---|---:|---:|---|
| Below 500 USD | 0 | 500 | USD |
| 500–1000 USD | 500 | 1000 | USD |
| 1000–1500 USD | 1000 | 1500 | USD |
| 1500–2000 USD | 1500 | 2000 | USD |
| Above 2000 USD | 2000 | NULL | USD |

Lý do: cần `min_salary` để sort salary ascending/descending.

---

### 4.4 Job vacancy table

#### `job_vacancies`

```txt
id
employer_id

job_title_id
job_category_id
employment_type_id
industry_id
job_level_id
number_of_openings

country_id
city_id
district_id
work_arrangement_id

salary_range_id
salary_type_id
benefits

responsibilities
required_qualifications
preferred_skills
additional_notes

degree_level_id
experience_level_id

status            -- active, inactive, removed
created_at
updated_at
```

Notes:

- `employer_id` nên trỏ tới `employer_profiles.id`.
- Public job search chỉ lấy `status = 'active'`.
- Admin remove job bằng `status = 'removed'`.
- Employer activate/deactivate đổi giữa `active` và `inactive`.

---

### 4.5 Many-to-many required skills

#### `job_vacancy_skills`

```txt
id
job_vacancy_id
skill_id
proficiency_level_id
created_at
```

Rules:

- Mỗi job phải có ít nhất 1 required skill.
- Mỗi job tối đa 5 required skills.
- Không cho chọn trùng skill trong cùng một job.
- Skill phải lấy từ predefined skill list.
- Minimum proficiency lấy từ `proficiency_levels`.

---

## 5. Route/Page Plan

Nếu dùng PHP thuần, nên dùng query-based routing để dễ chạy trên XAMPP:

### Public/Auth

```txt
/public/index.php?page=home
/public/index.php?page=login
/public/index.php?page=register
/public/index.php?page=logout
```

### Employer

```txt
/public/index.php?page=employer_dashboard
/public/index.php?page=employer_jobs
/public/index.php?page=employer_job_create
/public/index.php?page=employer_job_store
/public/index.php?page=employer_job_view&id=1
/public/index.php?page=employer_job_edit&id=1
/public/index.php?page=employer_job_update&id=1
/public/index.php?page=employer_job_delete&id=1
/public/index.php?page=employer_job_toggle_status&id=1
```

### Job Seeker/Public Jobs

```txt
/public/index.php?page=jobs
/public/index.php?page=job_detail&id=1
```

### Admin

```txt
/public/index.php?page=admin_dashboard
/public/index.php?page=admin_jobs
/public/index.php?page=admin_job_view&id=1
/public/index.php?page=admin_job_set_status&id=1
/public/index.php?page=admin_lookup&type=skills
/public/index.php?page=admin_lookup_create&type=skills
/public/index.php?page=admin_lookup_edit&type=skills&id=1
/public/index.php?page=admin_countries
/public/index.php?page=admin_cities
/public/index.php?page=admin_districts
```

---

## 6. UI Direction — CareerViet-inspired

Không clone toàn bộ CareerViet. Chỉ lấy cảm hứng:

- Header chuyên nghiệp.
- Màu xanh dương/trắng.
- Hero search box lớn.
- Job cards rõ ràng.
- Filter sidebar.
- Badge cho salary/location/skills.
- Dashboard có sidebar + cards + tables.
- Responsive layout.

### Public header

```txt
Logo | Việc làm | Công ty | Cẩm nang nghề nghiệp | Đăng nhập | Đăng ký | Nhà tuyển dụng
```

### Employer/Admin dashboard layout

```txt
Sidebar bên trái
Main content bên phải
Cards thống kê
Tables quản lý
Form nhập liệu rõ ràng
```

---

# 7. PHASE 1 — Foundation, Database, Auth

## 7.1 Người phụ trách

**Member 1**

## 7.2 Mục tiêu

Dựng nền móng project:

- Folder structure đúng guideline.
- Config XAMPP/PHP/MySQL.
- Database schema.
- Seed data.
- Auth register/login/logout.
- Role-based redirect.
- Base layout.
- README setup cơ bản.

## 7.3 Tasks chi tiết

### Task 1 — Initialize project

Tạo folder:

```txt
C:\BKU\Web_Programming\xampp\htdocs\JobVaccancyManagement_JobSearchSystem
```

Tạo cấu trúc:

```txt
app/
config/
database/
docs/
public/
resources/
storage/
README.md
.gitignore
```

### Task 2 — Config

Tạo:

```txt
config/app.php
config/database.php
app/Core/Database.php
```

`BASE_URL`:

```php
define('BASE_URL', 'http://localhost/JobVaccancyManagement_JobSearchSystem/public');
```

### Task 3 — Database schema

Tạo `database/schema.sql` gồm:

- `users`
- `employer_profiles`
- `job_seeker_profiles`
- lookup tables
- `job_vacancies`
- `job_vacancy_skills`

### Task 4 — Seed data

Tạo `database/seed.sql` gồm:

- Admin demo account.
- Employer demo account.
- Job seeker demo account.
- Job titles.
- Job categories.
- Industries.
- Employment types.
- Job levels.
- Salary ranges.
- Skills.
- Locations.
- Work arrangements.
- Proficiency levels.
- Degree levels.
- Experience levels.

### Task 5 — MVC-like foundation

Tạo:

```txt
Router.php
Controller.php
Auth.php
Validator.php
```

Không cần framework. Chỉ cần MVC-like để đáp ứng technical requirement.

### Task 6 — Auth

Implement:

- Register.
- Login.
- Logout.
- Password hashing.
- Session.
- Role-based redirect.

Register cho phép:

```txt
Employer
Job Seeker
```

Admin không register qua form, chỉ seed sẵn.

### Task 7 — Base UI

Tạo:

- Main layout.
- Header.
- Footer.
- Home page placeholder.
- Login/register pages.
- 403/404 pages.

## 7.4 Validation Phase 1

Auth validation:

```txt
Full name required
Email required
Email format
Email unique
Password required
Password min length
Confirm password match
Role allowed: employer/job_seeker
Company name required nếu role = employer
```

## 7.5 Commit plan Member 1

```txt
commit 1: Initialize project structure
commit 2: Add app and database configuration
commit 3: Add normalized database schema
commit 4: Add seed data for users and lookup tables
commit 5: Add MVC-like core classes
commit 6: Implement authentication pages
commit 7: Implement register, login, logout logic
commit 8: Add role-based access helpers
commit 9: Add base public layout and auth styling
commit 10: Update README with setup instructions
```

## 7.6 Phase 1 Done Criteria

```txt
[OK] Project chạy được bằng XAMPP
[OK] Có database schema và seed
[OK] Có login/register/logout
[OK] Có role admin/employer/job_seeker
[OK] Có base layout
[OK] Có role-based redirect
[OK] Có README setup cơ bản
```

---

# 8. PHASE 2 — Employer Features

## 8.1 Người phụ trách

**Member 2**

## 8.2 Mục tiêu

Hoàn thiện employer workflow:

- Employer Dashboard.
- My Job Postings.
- Create Job Vacancy.
- Edit Job Vacancy.
- View Job Detail.
- Delete Job.
- Activate/deactivate Job.
- Required skills dynamic form.
- Ownership protection.

## 8.3 Employer pages

```txt
Employer Dashboard
My Job Postings
Create New Job
Edit Job
View Job
Company Profile placeholder
```

## 8.4 Create Job Form

Form chia đúng theo đề:

### A. Basic Job Information

```txt
Job Title             Select
Job Category          Select
Employment Type       Select
Industry              Select
Job Level             Select
Number of Openings    Number
```

### B. Job Location

```txt
Country              Select
City / Province      Select
District             Select optional
Work Arrangement     Select: Onsite / Remote / Hybrid
```

Important:

- Không lưu location thành 1 text field.
- Lưu bằng foreign keys.

### C. Salary & Benefits

```txt
Salary Range         Select
Salary Type          Select: Gross / Net
Benefits             Textarea
```

### D. Job Description

```txt
Responsibilities
Required Qualifications
Preferred Skills
Additional Notes
```

Đây là free-text section hợp lệ.

### E. Required Skills

Dynamic form:

```txt
[Skill select] [Minimum Proficiency select] [Remove]
+ Add Skill
```

Rules:

```txt
At least 1 skill
Maximum 5 skills
No duplicate skill
Each skill requires proficiency
```

### F. Education & Experience

```txt
Minimum Degree Level             Select
Minimum Years of Experience      Select
```

## 8.5 Employer management actions

Employer có thể:

```txt
Create job
Edit own job
Delete own job
Activate/deactivate own job
View own jobs
```

Ownership check bắt buộc:

```sql
WHERE id = ? AND employer_id = ?
```

Nếu Employer B cố sửa job của Employer A:

```txt
403 Forbidden hoặc redirect về My Jobs kèm error message
```

## 8.6 Server-side validation Phase 2

Validate:

```txt
job_title_id required
job_category_id required
employment_type_id required
industry_id required
job_level_id required
number_of_openings required, integer, >= 1

country_id required
city_id required
district_id optional
work_arrangement_id required

salary_range_id required
salary_type_id required
benefits optional

responsibilities required
required_qualifications required
preferred_skills optional
additional_notes optional

degree_level_id required
experience_level_id required

skills required
skills count >= 1
skills count <= 5
skill_id required
proficiency_level_id required
no duplicate skill
```

## 8.7 Commit plan Member 2

```txt
commit 1: Add employer dashboard layout and sidebar
commit 2: Implement employer job listing with ownership filtering
commit 3: Add structured create job vacancy form
commit 4: Add dynamic required skills form
commit 5: Implement server-side validation for job creation
commit 6: Save job vacancy and required skills
commit 7: Implement employer job detail page
commit 8: Implement edit job vacancy and skill sync
commit 9: Implement activate and deactivate job actions
commit 10: Implement delete job with ownership protection
commit 11: Improve employer UI and validation messages
commit 12: Update README with employer workflow
```

## 8.8 Phase 2 Done Criteria

```txt
[OK] Employer tạo được job đầy đủ fields
[OK] Required skills lưu many-to-many
[OK] Dynamic form giới hạn 5 skills
[OK] Employer xem được job của mình
[OK] Employer edit/delete/toggle được job của mình
[OK] Employer không sửa được job người khác
[OK] Job data lưu normalized
```

---

# 9. PHASE 3 — Job Seeker Features + Public UI

## 9.1 Người phụ trách

**Member 3**

## 9.2 Mục tiêu

Làm phần public/job seeker:

- CareerViet-inspired homepage.
- Job listing page.
- Multi-criteria search/filter.
- AND logic.
- Sorting.
- Job detail page.
- Responsive UI.
- Không apply job.

## 9.3 Public pages

```txt
Home Page
Job Listing/Search Page
Job Detail Page
```

## 9.4 Home page

Nên có:

```txt
Header
Hero search section
Popular keywords
Featured categories
Latest active jobs
Footer
```

Latest jobs query:

```sql
WHERE status = 'active'
ORDER BY created_at DESC
LIMIT 6
```

## 9.5 Job listing page

Layout:

```txt
Top search bar
Left filter sidebar
Right job cards
```

Job card hiển thị:

```txt
Job title
Company name
Location
Salary range
Employment type
Work arrangement
Required skills
Posted date
View Detail button
```

## 9.6 Search/filter criteria

Job seeker search theo:

```txt
Keyword: job title, description
Job category
Country
City
Required skill
Employment type
Job level
Salary range
Work arrangement
```

## 9.7 AND logic

Query phải build kiểu:

```txt
WHERE jv.status = 'active'
AND keyword condition nếu có keyword
AND category condition nếu có category
AND city condition nếu có city
AND skill condition nếu có skill
AND employment type condition nếu có employment type
AND job level condition nếu có job level
AND salary range condition nếu có salary range
AND work arrangement condition nếu có work arrangement
```

Không dùng OR giữa các filter chính.

## 9.8 Skill filter

Nếu chọn 1 skill:

```sql
AND EXISTS (
    SELECT 1
    FROM job_vacancy_skills jvs
    WHERE jvs.job_vacancy_id = jv.id
    AND jvs.skill_id = :skill_id
)
```

Chỉ cần one-skill filter là đủ. Multi-select skill không bắt buộc.

## 9.9 Sorting

Dropdown:

```txt
Newest
Salary: Low to High
Salary: High to Low
Job Title: A-Z
```

Mapping:

```txt
newest       => ORDER BY jv.created_at DESC
salary_asc   => ORDER BY sr.min_salary ASC
salary_desc  => ORDER BY sr.min_salary DESC
title_asc    => ORDER BY jt.name ASC
```

## 9.10 Job detail page

Hiển thị:

```txt
Job title
Employer information
Location
Salary range
Required skills
Job description
Posting date
Education and experience
Benefits
```

Không có apply button/workflow.

## 9.11 Responsive UI

Desktop:

```txt
Filter sidebar left
Job list right
```

Mobile:

```txt
Search full width
Filters stacked/collapsible
Job cards one column
Job detail single column
```

## 9.12 Commit plan Member 3

```txt
commit 1: Add CareerViet-inspired homepage
commit 2: Add public job listing page and job card partial
commit 3: Implement active job query for public pages
commit 4: Implement keyword search
commit 5: Add category, location, employment type, job level, salary, and work arrangement filters
commit 6: Add required skill filter
commit 7: Implement AND logic for combined filters
commit 8: Add sorting options
commit 9: Implement job detail page
commit 10: Add responsive styles for public pages
commit 11: Add empty state and clear filter behavior
commit 12: Update README with job seeker features
```

## 9.13 Phase 3 Done Criteria

```txt
[OK] Home page chuyên nghiệp
[OK] Chỉ active jobs hiển thị public
[OK] Search keyword hoạt động
[OK] Filters hoạt động
[OK] Combined filters dùng AND logic
[OK] Sorting hoạt động
[OK] Job detail đầy đủ thông tin
[OK] Không có apply workflow
[OK] Responsive public UI
```

---

# 10. PHASE 4 — Admin + Final Integration + Documentation

## 10.1 Người phụ trách

**Member 4**

## 10.2 Mục tiêu

Đóng gói toàn hệ thống:

- Admin Dashboard.
- Admin manage all job vacancies.
- Admin remove invalid/inappropriate jobs.
- Admin manage lookup/reference data.
- Final integration.
- Testing.
- README.
- Technical report.
- Demo video script.
- Git history check.

## 10.3 Admin Dashboard

Admin dashboard hiển thị:

```txt
Total jobs
Active jobs
Inactive jobs
Removed jobs
Total employers
Total lookup records
Recent jobs
```

## 10.4 Admin manage job vacancies

Admin xem tất cả jobs:

```txt
Job Title
Company
Category
Location
Salary Range
Status
Created Date
Actions
```

Actions:

```txt
View
Set Active
Set Inactive
Remove
```

Admin remove job:

```txt
status = 'removed'
```

Public job search chỉ lấy:

```sql
WHERE status = 'active'
```

## 10.5 Admin manage lookup/reference data

Lookup bắt buộc:

```txt
Job categories
Job titles
Skills
Industries
Locations
Employment types
Job levels
Salary ranges
```

Nên quản lý thêm:

```txt
Salary types
Degree levels
Experience levels
Work arrangements
Proficiency levels
```

Generic CRUD vừa đủ:

```txt
List
Create
Edit
Activate/deactivate
```

Không hard delete lookup để tránh phá dữ liệu job cũ.

## 10.6 Location management

Quản lý riêng:

```txt
Countries
Cities
Districts
```

City form:

```txt
City name
Country select
Status
```

District form:

```txt
District name
City select
Status
```

## 10.7 Final integration flow

Luồng demo chính:

```txt
Employer login
→ Create active job
→ Job appears in public search
→ Job seeker filters/searches
→ Job seeker views detail
→ Admin removes job
→ Removed job disappears from public search
```

## 10.8 Final testing checklist

### Auth/RBAC

```txt
[ ] Register employer
[ ] Register job seeker
[ ] Login admin seeded account
[ ] Employer cannot access admin page
[ ] Job seeker cannot access employer dashboard
[ ] Visitor cannot access dashboards
```

### Employer

```txt
[ ] Create job with all required fields
[ ] Required skills min 1
[ ] Required skills max 5
[ ] Duplicate skill rejected
[ ] Edit own job
[ ] Delete own job
[ ] Activate/deactivate own job
[ ] Employer A cannot edit Employer B job
```

### Job Seeker/Public

```txt
[ ] Home loads
[ ] Latest active jobs show
[ ] Inactive/removed jobs hidden
[ ] Keyword search works
[ ] Category filter works
[ ] Location filter works
[ ] Skill filter works
[ ] Employment type filter works
[ ] Job level filter works
[ ] Salary range filter works
[ ] Work arrangement filter works
[ ] Combined filters use AND logic
[ ] Sort newest works
[ ] Sort salary asc/desc works
[ ] Sort title A-Z works
[ ] Job detail shows required information
[ ] No apply workflow exists
```

### Admin

```txt
[ ] Admin views all jobs
[ ] Admin views job detail
[ ] Admin removes job
[ ] Removed job hidden from public
[ ] Admin reactivates job
[ ] Admin creates lookup record
[ ] Admin edits lookup record
[ ] Admin activates/deactivates lookup record
[ ] Admin manages countries/cities/districts
```

### Responsive

```txt
[ ] Home responsive
[ ] Job list responsive
[ ] Job detail responsive
[ ] Employer dashboard responsive enough
[ ] Admin dashboard responsive enough
```

## 10.9 README structure

```txt
# Job Vacancy Management & Job Search System

## 1. Project Overview
## 2. Technologies Used
## 3. System Roles
## 4. Main Features
## 5. Folder Structure
## 6. Database Setup
## 7. How to Run with XAMPP
## 8. Default Accounts
## 9. Team Members and Contributions
## 10. Git Workflow
## 11. Limitations
```

Default accounts:

```txt
Admin:
email: admin@jobfinder.local
password: Admin@123

Employer:
email: employer@jobfinder.local
password: Employer@123

Job Seeker:
email: seeker@jobfinder.local
password: Seeker@123
```

## 10.10 Technical report structure

```txt
1. Introduction
2. System Roles
3. Functional Requirements Mapping
4. Database Design
5. ER Diagram
6. Normalization Decisions
7. Architecture Design
8. Main Workflows
9. Search and Filtering Logic
10. Role-based Access Control
11. Validation
12. UI Design
13. Testing
14. Team Contribution
15. Limitations
16. Conclusion
```

## 10.11 Demo video script

Target: khoảng 5 phút.

```txt
0:00–0:30 Introduction
0:30–1:10 Database/Architecture
1:10–2:20 Employer demo
2:20–3:30 Job seeker search/filter/detail demo
3:30–4:30 Admin demo
4:30–5:00 Conclusion
```

## 10.12 Commit plan Member 4

```txt
commit 1: Add admin dashboard layout
commit 2: Implement admin job vacancy management
commit 3: Add admin job detail and remove action
commit 4: Implement generic lookup listing
commit 5: Add create and edit lookup records
commit 6: Add activate and deactivate lookup records
commit 7: Add country, city, and district management
commit 8: Strengthen admin role-based access control
commit 9: Run final integration fixes
commit 10: Improve responsive dashboard UI
commit 11: Add ERD and database documentation
commit 12: Write README setup and demo accounts
commit 13: Add technical report draft
commit 14: Add final testing checklist and demo video script
```

## 10.13 Phase 4 Done Criteria

```txt
[OK] Admin quản lý jobs
[OK] Admin remove job
[OK] Admin quản lý lookup/reference data
[OK] Full flow chạy từ employer → public → admin
[OK] README đầy đủ
[OK] Technical report có ERD và design decisions
[OK] Demo script sẵn sàng
[OK] Git history hợp lý
```

---

# 11. Branch and Git Workflow

## 11.1 Branch strategy

```txt
main
phase-1-foundation
phase-2-employer
phase-3-jobseeker-ui
phase-4-admin-final
```

Merge order:

```txt
phase-1-foundation → main
phase-2-employer → main
phase-3-jobseeker-ui → main
phase-4-admin-final → main
```

## 11.2 Team contribution summary

| Member | Phase | Main Responsibility |
|---|---|---|
| Member 1 | Phase 1 | Foundation, DB, Auth, base structure |
| Member 2 | Phase 2 | Employer job creation and management |
| Member 3 | Phase 3 | Public UI, search/filter/sort, job detail |
| Member 4 | Phase 4 | Admin, final testing, docs, report, demo |

---

# 12. Requirement-to-Implementation Mapping

| Requirement | Implementation |
|---|---|
| Employer register/login securely | AuthController, User model, password_hash, session |
| Role-based access control | Auth helper, `requireRole()` |
| Employer create structured job vacancy | Employer Create Job form |
| Basic job info select fields | `job_titles`, `job_categories`, `employment_types`, `industries`, `job_levels` |
| Structured location | `countries`, `cities`, `districts`, `work_arrangements` |
| Salary & benefits | `salary_ranges`, `salary_types`, benefits textarea |
| Job description free text | responsibilities, required qualifications, preferred skills, additional notes |
| Required skills mandatory | Dynamic skill rows, `job_vacancy_skills` |
| Up to 5 skills | JS + PHP validation |
| Education & experience | `degree_levels`, `experience_levels` |
| Employer manage own jobs | Ownership filtering by `employer_id` |
| Job seeker search by keyword | LIKE on title and description fields |
| Job seeker filters | SQL conditions from lookup IDs |
| Combined filters AND logic | Dynamic query builder appends `AND` |
| Sorting | `ORDER BY created_at`, `sr.min_salary`, `jt.name` |
| Job detail | Public job detail page |
| Admin manage job vacancies | Admin jobs page |
| Admin manage reference tables | Generic lookup CRUD |
| Remove invalid job | `status = 'removed'` |
| Relational DB | MySQL |
| Normalization | Foreign keys to lookup tables |
| Many-to-many skills | `job_vacancy_skills` |
| MVC/equivalent | MVC-like PHP structure |
| Server-side validation | Validator class + controller validation |
| Responsive UI | CSS media queries |
| Git commit history | Phase branches + detailed commits |

---

# 13. Priority Strategy for Scoring

Rubric priority:

| Category | Weight | Priority |
|---|---:|---|
| Job vacancy creation & management | 30% | Highest |
| Job search & filtering | 25% | Highest |
| Database design & normalization | 20% | Highest |
| Code structure & architecture | 15% | Medium |
| UI & usability | 5% | Medium-low |
| Documentation & demo | 5% | Medium-low |

Do đó nhóm nên ưu tiên:

1. Structured job form.
2. Normalized database.
3. Required skills many-to-many.
4. Employer ownership protection.
5. Search/filter AND logic.
6. Sorting.
7. Admin lookup management.
8. Clean folder structure.
9. Responsive enough UI.
10. README/report/demo.

Không nên mất quá nhiều thời gian cho:

- Animation.
- Advanced UI effects.
- Recommendation.
- Apply job.
- CV upload.
- Chat/email.

---

# 14. Final Implementation Order

Thứ tự triển khai cuối cùng:

```txt
1. Member 1 creates project structure and database.
2. Member 1 implements auth and roles.
3. Member 2 implements employer dashboard.
4. Member 2 implements create/edit/delete/activate/deactivate job.
5. Member 3 implements public homepage and job list.
6. Member 3 implements search/filter/sort/detail.
7. Member 4 implements admin dashboard.
8. Member 4 implements job and lookup management.
9. All members test integration.
10. Member 4 finalizes README, report, demo script.
```

---

# 15. Final Notes

Kế hoạch này giữ scope vừa đủ:

- Đủ đáp ứng assignment.
- Không over-engineer.
- Dễ chia việc cho 4 người.
- Dễ tạo commit history rõ.
- Dễ demo.
- Dễ giải thích trong report.
- Bám sát yêu cầu XAMPP + PHP + MySQL + HTML/CSS/JS.

Điểm cốt lõi cần luôn nhớ khi implement:

```txt
Location không lưu text đơn.
Selectable fields phải dùng lookup tables.
Required skills phải many-to-many.
Employer chỉ quản lý job của mình.
Job seeker chỉ search/view.
Admin quản lý job và reference data.
Không làm CV/apply/recommendation.
```
