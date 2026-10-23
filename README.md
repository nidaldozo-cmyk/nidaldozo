---FitPulse: is a web application where the users can track their daily workouts, search for specific excersizes in the excercize database, and set physical workouts.---

--- Entities: users, workouts, exercises, workout_logs, goals. ---

--- Relationships in the ERD database:
- users 1 -> N workouts
- users 1 -> N goals
- workouts 1 -> N workout_logs
- exercises 1 -> N workout_logs
(1 -> N - One To Many) ---