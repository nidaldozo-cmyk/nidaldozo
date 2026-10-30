# FitPulse

FitPulse is a workout-tracking web application for recording workouts, browsing
exercises, and setting fitness goals.

## Features

- Workout, exercise, and goal views
- Exercise database and workout log tracking
- User and administrator roles

## Technology

- Frontend: HTML, CSS, JavaScript, jQuery, and Bootstrap 5
- Backend: PHP and PDO, with a FlightPHP REST API planned
- Database: MySQL/MariaDB
- Local development: XAMPP

## Milestone 2

The database schema, sample data, and PHP data-access objects (DAOs) are in
place. The frontend is navigable, but it is not yet connected to backend routes
or services. Backend API integration and external exercise search are planned.

## Run locally

1. Start Apache and MySQL from the XAMPP Control Panel.
2. Import [`backend/database/fitpulse.sql`](backend/database/fitpulse.sql)
   through phpMyAdmin.
3. Copy `backend/config/config.example.php` to
   `backend/config/config.php` and enter your local database credentials.
4. Open `http://localhost/nidaldozo/frontend/`.

**Import warning:** The SQL dump drops and recreates the FitPulse tables before
inserting sample records. Back up any data you want to keep before importing.

## Database

The database contains users, workouts, exercises, workout logs, and goals.
Goals may optionally be linked to an exercise. The schema and sample data are
in [`backend/database/fitpulse.sql`](backend/database/fitpulse.sql); see the
[ERD](docs/ERD.png) for the relationships.
