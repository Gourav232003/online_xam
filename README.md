# Online Examination System (PHP + MySQL)

A starter Online Examination System built with PHP, MySQL, Bootstrap, and vanilla JavaScript.

## Features

- Secure login for candidates
- Candidate profile and exam dashboard
- Time-bound exam with auto-submit
- Save and submit candidate answers
- Basic result view
- Admin panel for managing questions

## Tech Stack

- Backend: PHP 8+
- Database: MySQL 5.7+ / MariaDB
- Frontend: Bootstrap 5 + custom CSS
- Server: Apache via XAMPP

## Project Structure

- public/: Candidate-facing pages
- admin/: Admin pages
- config/: App configuration
- includes/: Shared helpers (auth/session)
- database/: SQL schema and seed data
- assets/: CSS/JS assets

## Setup (XAMPP)

1. Copy this folder into your XAMPP htdocs directory.
2. Start Apache and MySQL from XAMPP.
3. Create a database named online_exam.
4. Import database/schema.sql into online_exam.
5. Update DB credentials in config/database.php if needed.

Candidate Login URL:
- http://localhost/onlin%20xam/public/index.php

Admin Login URL:
- http://localhost/onlin%20xam/admin/login.php

## Default Accounts

Candidate:
- User ID: CAND001
- Password: pass123

Admin:
- Username: admin
- Password: admin123

## Notes

- This is a starter project for learning and extension.
- For production, hash all passwords with password_hash and enforce HTTPS.
# online_xam
