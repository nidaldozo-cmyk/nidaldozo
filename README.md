# FitPulse

FitPulse is a web application where users track their daily workouts, search an
exercise database and set physical goals.

## Roles and entities

Roles: user, admin.

Entities: users, workouts, exercises, workout_logs, goals.

## Database (draft)

Relationships (1→N means one-to-many):

- users 1→N workouts
- users 1→N goals
- workouts 1→N workout_logs
- exercises 1→N workout_logs

The diagram is in `docs/ERD.png`.

## Technology stack

- Frontend: HTML, CSS, JavaScript, jQuery, Bootstrap 5, single-page application (SPA)
- Backend: PHP with FlightPHP (REST API), PDO, MySQL (from Milestone 2)
- Local server: XAMPP
- External API: Wger or ExerciseDB for exercise search (planned)

## Folder structure

- `frontend/`: css, js, views, assets
- `backend/`: routes, services, dao, middleware, config, logs
- `docs/`: AI_LOG.md, ERD.png

## Status

Milestone 1: project structure, static SPA frontend and draft ERD.