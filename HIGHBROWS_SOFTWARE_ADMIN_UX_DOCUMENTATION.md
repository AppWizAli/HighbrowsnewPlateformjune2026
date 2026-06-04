# `highbrows_software` Main Admin Panel UX Documentation

## Scope

This document is only for the main `admin` panel inside `highbrows_software`.

Do not include:

- subadmin panel
- coordinator panel
- student panel
- public website
- `backend` CMS
- `pafonlinetest`

This brief is intended for Figma AI to redesign the full admin frontend with better UX, clearer navigation, faster daily workflows, and stronger information hierarchy.

---

## 1. Product Goal

Design a modern institutional admin panel for a school/academy operation where the admin can quickly manage:

- students
- admissions
- teachers and staff
- classes and subjects
- fees and monthly fees
- expenses and salaries
- exams, schedules, datesheets, and results
- blogs
- rules and policy content

The design must feel:

- fast
- clean
- trustworthy
- task-oriented
- easy to learn
- easy to navigate
- optimized for repeated daily actions

---

## 2. Current Admin Modules Found In Code

Based on the current admin navigation and views, the admin side includes these modules:

### Dashboard

- overview dashboard
- income, expenses, revenue, student count, employee count
- date filtering by month and year

### Student Operations

- registered students
- student user registration
- admission records
- admission form
- student detail
- student attendance
- fee records for students/cadet colleges
- monthly fees
- challan and receipt views

### Staff Operations

- teachers
- subadmins
- coordinators
- employee attendance
- salaries

### Academic Setup

- colleges
- classes
- subjects

### Exam Operations

- exams
- exam schedules
- datesheets
- result entry
- result list
- result view
- result print
- question bank

### Content And Admin Utilities

- blogs
- rules and regulations
- expenses

---

## 3. Admin Information Architecture For New UX

Do not copy the current navigation literally.

The new admin IA should be:

### Primary Navigation

1. Dashboard
2. Students
3. Staff
4. Academics
5. Finance
6. Exams
7. Content
8. Policies
9. Integrations

### Recommended Secondary Navigation

#### Dashboard

- Overview
- Activity
- Alerts

#### Students

- Student Accounts
- Admissions
- Student Profiles
- Attendance
- Fee Plans
- Monthly Fees

#### Staff

- Teachers
- Subadmins
- Coordinators
- Attendance
- Salaries

#### Academics

- Colleges
- Classes
- Subjects

#### Finance

- Student Fees
- Monthly Fees
- Expenses
- Salary Payments
- Receipts & Challans

#### Exams

- Exams
- Schedules
- Datesheets
- Results
- Question Bank

#### Content

- Blogs

#### Policies

- Student Rules
- Teacher Rules

#### Integrations

- External PAF panel links

Important IA note:

- Teachers, subadmins, and coordinators must be shown as separate entities
- Do not mix "register teacher" with subadmin creation
- Monthly fee and fee plan flows should be grouped under Finance
- External links should not compete with core admin actions

---

## 4. Global UX Requirements

### Layout

Design a desktop-first admin shell with:

- collapsible left sidebar
- sticky topbar
- clear page title and breadcrumb area
- page-level quick actions
- responsive behavior for tablet and mobile

### Topbar

The topbar should include:

- global search
- quick create button
- notifications/alerts
- current user menu
- optional current month/session filter

### Quick Actions

Design a global quick action system for frequent tasks:

- add student
- start admission
- add teacher
- create fee plan
- generate monthly fees
- create exam
- add exam schedule
- enter result
- add expense
- create blog

This can be shown as:

- topbar create button
- command palette
- dashboard quick action cards

### Table UX Pattern

All list pages should use one unified table pattern with:

- search
- filters
- sorting
- pagination
- sticky header
- bulk selection
- bulk actions
- export option
- row quick actions
- empty state
- no-results state

### Form UX Pattern

All forms should follow a consistent pattern:

- clear section grouping
- inline help text
- required field indicators
- validation near fields
- sticky submit area
- save draft where useful
- cancel/back behavior
- success toast after save

### Detail Page Pattern

Detail screens should use:

- summary header
- status chips
- key metadata
- tabs for related data
- right-side quick actions or sticky action bar

### Feedback And States

Use modern feedback patterns instead of browser alerts:

- success toast
- warning confirmation modal
- danger confirmation modal
- inline validation
- loading skeletons
- upload progress

---

## 5. Core Design Principles

Figma AI should optimize for:

- minimum clicks for common tasks
- clarity over decorative complexity
- strong visual grouping
- readable tables and forms
- safe destructive actions
- quick scanning of operational status
- high contrast and accessibility
- consistency across modules

The admin should be able to do the most common tasks in under 3 steps.

Examples of common tasks:

- register a student
- complete admission
- view student profile
- assign fee/installments
- upload or verify receipt
- add teacher
- mark attendance
- create exam
- schedule exam
- view result
- add expense

---

## 6. Screen-By-Screen UX Documentation

## 6.1 Dashboard

### Current purpose

Main summary screen with month/year filters and KPI cards.

### New UX goals

- show admin priorities immediately
- reduce navigation time
- surface pending work
- show financial and academic snapshots

### Dashboard content

- KPI cards: income, expenses, revenue, total students, total staff
- pending admissions
- unpaid fees / overdue installments
- pending monthly fee receipts
- exams this week
- recent student registrations
- recent staff changes
- expense alerts
- shortcuts to create most-used records

### Recommended widgets

- KPI row
- quick actions block
- pending approvals block
- upcoming schedule block
- recent activity feed
- finance trend chart
- attendance risk summary

### UX notes

- do not rely only on colorful cards
- show trends, changes, and actionability
- every dashboard block should link to a filtered list

---

## 6.2 Students List

### Real screen intent

List all registered students and let admin:

- view admission if it exists
- add admission if missing
- delete user

### New UX goals

- make this the central student management table
- show status at a glance
- reduce need to jump across pages

### Required columns

- student name
- contact
- email
- class
- admission status
- fee status
- monthly fee status
- attendance risk
- last activity

### Required filters

- class
- admission complete / incomplete
- hostel / day scholar / online
- fee paid / partial / overdue
- active / inactive

### Row actions

- open profile
- continue admission
- view documents
- fee details
- attendance
- result
- more actions menu

### UX improvements

- replace tiny icon-only actions with clear action menu
- add status chips
- add profile preview drawer
- allow bulk export and bulk messaging later if needed

---

## 6.3 Register Student Account

### Real screen intent

Simple account creation with:

- name
- contact
- email
- password

### New UX goals

- make this a lightweight first step in student onboarding
- avoid confusion between account creation and full admission

### UX requirements

- step label: `Step 1 of 2`
- clear note that admission details will be filled next
- inline validation
- duplicate email/contact warning
- success state with next action buttons

### Next-step actions after save

- continue to admission form
- return to student list
- create another account

---

## 6.4 Admission Form

### Real screen intent

A long single-page form covering:

- personal info
- guardian info
- grade applied for
- admission date
- residence type
- contact info
- address
- father income
- document uploads
- cadet college application

### Biggest current UX problem

This should not stay a single long form.

### New UX structure

Turn it into a multi-step wizard:

1. Personal Details
2. Academic & Admission Details
3. Contact & Family Details
4. Documents
5. Cadet College Application
6. Review & Submit

### Wizard features

- progress indicator
- save draft
- previous/next navigation
- validation per step
- sticky action footer
- document upload previews
- draft autosave if possible

### Special UX logic

- if cadet college checkbox is enabled, show selected colleges step clearly
- show missing required documents before final submit
- show review summary before confirmation

---

## 6.5 Student Profile / Student Detail

### Real screen intent

View student details and related records.

### New UX goals

Create one strong profile page as the central student command center.

### Recommended layout

- profile header with photo, name, class, status, residence type
- summary cards for fee status, attendance, latest result, admission status
- tabbed content

### Tabs

- Overview
- Admission
- Documents
- Fees
- Monthly Fees
- Attendance
- Results
- Activity Log

### Quick actions

- edit profile
- print admission
- add/update fee
- upload or verify receipt
- view result card

---

## 6.6 Student Attendance

### Real screen intent

Select class, mark attendance, view attendance, filter records.

### New UX goals

- make attendance fast for daily use
- reduce repetitive selection
- improve visibility of absent/late trends

### Required screens

- attendance overview
- take attendance
- attendance history
- student attendance detail

### UX requirements

- class selector with recent classes
- present/absent/leave toggles
- bulk mark actions
- save confirmation
- attendance summary for selected class
- filter by date/class/student

---

## 6.7 Teachers List

### Real screen intent

List teachers, view, edit, delete, and access teacher onboarding.

### Current UX issue

Teacher creation and subadmin flows are mixed in current navigation.

### New UX goals

- separate teachers from subadmins clearly
- make staff records easier to scan

### Required columns

- teacher photo
- name
- email
- joining date
- assigned class
- salary status
- attendance status
- active/inactive

### Row actions

- view profile
- edit
- salary history
- attendance history
- deactivate

---

## 6.8 Staff Profiles, Salaries, And Attendance

### New UX goals

Create a consistent staff management experience across teachers, subadmins, and coordinators.

### Staff profile structure

- profile header
- contact info
- role info
- joining info
- class assignment
- salary summary
- attendance summary
- documents if applicable

### Salary screens

- salary list
- generate current month salary
- pay salary
- salary receipt

### Salary UX requirements

- month filter
- paid/unpaid status chips
- total payroll summary
- pay action with confirmation modal
- printable receipt preview

### Employee attendance UX requirements

- daily attendance entry
- employee filter
- role filter
- attendance summary cards
- detailed attendance history

---

## 6.9 Academics: Colleges, Classes, Subjects

### Real screen intent

Manage academic setup master data.

### New UX goals

- keep these screens simple and consistent
- allow quick setup and easy edits

### Each module should have

- clean list page
- add modal or side sheet for simple creation
- edit action
- status if needed
- relation preview

### Suggested improvements

- show class-teacher-subject relations visually
- allow subject assignment visibility per class
- make colleges more informative if linked to admissions

---

## 6.10 Fee Plans And Cadet College Fees

### Real screen intent

Admin selects student, enters total fee, advance, number of installments, then generates dynamic installment fields.

### New UX goals

- make fee setup understandable and less error-prone
- improve installment planning

### Recommended UX structure

- student selector with search
- fee summary panel
- installment builder
- due date planner
- live total calculation
- remaining balance summary
- challan preview

### Important UX features

- auto-calculate remaining amount
- warning if installment total does not match total fee
- copy fee template from existing student/class if useful
- show paid vs unpaid installments clearly

---

## 6.11 Monthly Fees

### Real screen intent

Generate fees for current month, view details, mark paid, review student-side receipts.

### New UX goals

- make recurring fee operations very fast
- show approval bottlenecks

### Required views

- monthly fee dashboard
- monthly fee list
- student monthly fee detail
- payment review
- mark-as-paid flow

### UX requirements

- current month summary
- generated / pending / paid / overdue counts
- receipt review drawer or modal
- approval workflow
- batch actions for generation and reminders

---

## 6.12 Expenses

### Real screen intent

Create, edit, delete expenses and upload receipts.

### New UX goals

- make expense tracking feel reliable and auditable

### Required fields and views

- expense title
- category
- amount
- date
- receipt
- status

### UX requirements

- expense summary cards
- category filter
- receipt preview
- add expense flow
- edit expense flow
- delete confirmation modal

---

## 6.13 Exams

### Modules inside Exams

- exams
- schedules
- datesheets
- results
- question bank

### New UX goals

- group all exam work into one cohesive system
- reduce confusion between schedule, datesheet, and result flows

---

## 6.14 Exam List

### Required UX

- list of exams
- status chip
- class coverage
- start/end timeline
- create/edit/delete
- quick access to schedule and results

---

## 6.15 Exam Schedule List

### Real screen intent

List schedules with more-actions dropdown:

- add datesheet
- view datesheet
- print result
- edit
- delete

### New UX goals

- make this more discoverable
- reduce hidden actions

### Recommended UX

- table plus calendar toggle
- clear schedule status
- action menu with labels
- timeline visualization
- upcoming exams section

---

## 6.16 Datesheets

### Required UX

- create datesheet from schedule
- view datesheet entries
- edit datesheet entries
- printable clean format

### UX improvements

- subject rows with date and time
- conflict warnings
- print-friendly layout
- publish/unpublish state if needed

---

## 6.17 Results

### Real screen intent

Filter by class, list students, open result view, add result, print result card.

### New UX goals

- make result entry and review simpler
- show class/exam context clearly

### Required result views

- result dashboard
- add result form
- result list
- student result detail
- printable result card

### UX requirements

- filter by exam, class, subject, student
- marks summary
- grade summary
- pass/fail visuals
- printable clean card layout
- clear empty state when no result exists

---

## 6.18 Question Bank

### Required UX

- question list
- add question
- edit question
- delete question

### UX notes

- use structured filters
- show class/subject relation if available
- allow future bulk import design room

---

## 6.19 Blogs

### Real screen intent

Simple admin blog management.

### New UX goals

- make content work feel lighter than operational modules

### UX requirements

- blog list
- create/edit blog
- status draft/published if possible
- cover image preview
- rich text friendly editor layout

---

## 6.20 Student Rules And Teacher Rules

### Real screen intent

Manage institutional rules content.

### New UX goals

- keep policy pages simple and easy to maintain

### UX requirements

- list page
- add/edit page
- preview mode
- version/date visibility if possible

---

## 7. Global Component Requirements

Figma AI should define reusable components for:

- sidebar
- topbar
- page header
- KPI cards
- summary cards
- filter bar
- data table
- status chip
- action menu
- confirmation modal
- success toast
- multi-step form wizard
- file upload card
- profile header
- detail tabs
- printable card layout
- empty states
- no-results states

---

## 8. Visual Direction

The visual design should feel:

- premium but practical
- academic and institutional
- clean white surfaces with strong structure
- restrained brand color use
- confident typography
- not overly generic SaaS blue-purple style

### Suggested visual style

- strong section headers
- soft card elevation
- clear dividers
- compact but readable density
- generous spacing around forms
- high-contrast interactive states

### Accessibility

- high color contrast
- keyboard-friendly navigation
- large click targets
- readable tables
- accessible modals and form states

---

## 9. Figma AI Prompt

Copy this prompt into Figma AI:

> Design a complete modern frontend UI/UX for the main admin panel of a school/institute management system called HighBrows. This is only for the main admin role, not subadmin, coordinator, teacher, or student dashboards.
>
> Create a desktop-first but fully responsive admin panel with excellent UX, easy navigation, fast daily workflows, clear hierarchy, and strong quick actions. The admin must be able to manage students, admissions, teachers and staff, classes, subjects, colleges, fees, monthly fees, expenses, salaries, exams, schedules, datesheets, results, blogs, and institutional rules.
>
> Do not copy a generic template. Design a premium, practical, highly usable panel focused on operational speed and clarity.
>
> Use this improved information architecture:
> 1. Dashboard
> 2. Students
> 3. Staff
> 4. Academics
> 5. Finance
> 6. Exams
> 7. Content
> 8. Policies
> 9. Integrations
>
> Inside Students, include:
> Student Accounts, Admissions, Student Profiles, Attendance, Fee Plans, Monthly Fees.
>
> Inside Staff, include:
> Teachers, Subadmins, Coordinators, Attendance, Salaries.
>
> Inside Academics, include:
> Colleges, Classes, Subjects.
>
> Inside Finance, include:
> Student Fees, Monthly Fees, Expenses, Salary Payments, Receipts and Challans.
>
> Inside Exams, include:
> Exams, Schedules, Datesheets, Results, Question Bank.
>
> Inside Content, include:
> Blogs.
>
> Inside Policies, include:
> Student Rules and Teacher Rules.
>
> Design these key screens in detail:
> dashboard overview, students list, register student account, multi-step admission wizard, student profile, student attendance, teachers list, staff profile, salaries, employee attendance, colleges/classes/subjects management, fee plan builder, monthly fee dashboard, expenses list and create form, exam list, exam schedule list, datesheet management, result entry, result list, result detail, question bank, blogs, rules management.
>
> The dashboard should include KPI cards, trends, pending tasks, recent activity, finance summaries, and quick actions.
>
> The panel must include a sticky sidebar, sticky topbar, global search, quick-create button, breadcrumbs, status chips, reusable filter bars, unified data tables, confirmation modals, success toasts, empty states, and mobile/tablet responsive behavior.
>
> Optimize for the most common admin actions to be fast:
> register student, continue admission, open student profile, assign fee installments, generate monthly fees, verify receipts, add teacher, mark attendance, create exam, create schedule, enter result, add expense, create blog.
>
> For tables, design consistent search, filters, sorting, pagination, bulk selection, bulk actions, exports, row action menus, and sticky headers.
>
> For forms, design clear section grouping, inline validation, required indicators, sticky action footer, save draft where useful, file upload previews, and strong confirmation states.
>
> The admission form must be redesigned as a multi-step wizard with:
> Personal Details, Academic and Admission Details, Contact and Family Details, Documents, Cadet College Application, Review and Submit.
>
> The student profile should be a central command page with tabs:
> Overview, Admission, Documents, Fees, Monthly Fees, Attendance, Results, Activity Log.
>
> Teachers, subadmins, and coordinators must be treated as separate entities. Do not mix teacher registration with subadmin creation.
>
> Monthly fees and fee plans must be grouped under Finance and designed for fast recurring operations with status filters, receipt review, and mark-as-paid workflows.
>
> Exams must be presented as one connected system with exam list, schedules, datesheets, results, and question bank. Make schedules and datesheets easy to understand visually.
>
> Use a clean, premium, institutional visual language: strong structure, clean white surfaces, confident typography, compact readable density, restrained brand color usage, high contrast, and accessibility-first interaction design.
>
> Generate a complete admin panel frontend concept with reusable components, page templates, and polished high-fidelity screens.

---

## 10. Optional Extra Prompt For Even Better Output

Use this after the main prompt if needed:

> Focus heavily on workflow UX, not just visual polish. Prioritize fewer clicks, better table usability, clearer states, stronger hierarchy, safer destructive actions, and better admin productivity. Show quick actions, filters, dashboards, detail views, and form flows as complete realistic product screens rather than moodboard-style UI.
