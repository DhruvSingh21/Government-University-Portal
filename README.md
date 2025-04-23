# EduGov Connect

EduGov Connect is a comprehensive platform designed to bridge government agencies and academic institutions, streamlining education management and governance.

## Project Overview

EduGov Connect facilitates digital governance in education by providing a unified platform for:
- Government educational departments to monitor, analyze, and manage academic institutions
- Universities to manage courses, departments, and students
- Streamlined reporting and analytics for data-driven decision making

## Features

- **Real-time Analytics**: Comprehensive dashboards with education metrics and trends
- **Unified Data Management**: Centralized repository for all education-related data
- **Secure Platform**: Enterprise-grade security with role-based access controls
- **Multi-tenant Architecture**: Separate interfaces for government agencies and universities

## Project Structure
## 📁 Project Structure

```
├── api/
│   ├── config/
│   │   └── error_handler.php
│   ├── diagnostics.php
│   ├── government/
│   │   └── generate-report.php
│   ├── shared/
│   │   └── fetch-departments.php
│   └── university/
│       ├── add-course.php
│       ├── add-department.php
│       ├── add-student.php
│       └── dashboard-stats.php
├── assets/
│   └── images/
├── config.php
├── controllers/
│   └── auth/
├── database/
│   └── update_schema.php
├── index.php
├── schema.sql
└── views/
    ├── government/
    ├── government-login.php
    ├── home.php
    ├── login-options.php
    ├── register-government.php
    ├── register-options.php
    ├── register-university.php
    ├── university-login.php
    └── university/
```


## Technology Stack

- **Backend**: PHP
- **Database**: MySQL (defined in [schema.sql](schema.sql))
- **Frontend**: HTML, CSS (Tailwind CSS), JavaScript
- **UI Framework**: Tailwind CSS

## Installation

1. Clone the repository
2. Import the database schema from [schema.sql](schema.sql)
3. Configure your database connection in [config.php](config.php)
4. Deploy to a PHP-enabled web server (e.g., Apache with XAMPP)

## Usage

- Government officials can register and login through the government portal
- University administrators can register and login through the university portal
- Both portals provide specialized dashboards and management tools
