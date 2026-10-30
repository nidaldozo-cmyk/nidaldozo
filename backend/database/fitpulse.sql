CREATE DATABASE IF NOT EXISTS fitpulse
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE fitpulse;

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(254) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS exercises (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  muscle_group VARCHAR(100) NOT NULL,
  equipment VARCHAR(100) DEFAULT NULL,
  external_api_id VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_exercises_name (name),
  KEY idx_exercises_muscle_group (muscle_group)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS workouts (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(150) NOT NULL,
  workout_date DATE NOT NULL,
  notes TEXT DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_workouts_user_date (user_id, workout_date),
  CONSTRAINT fk_workouts_user
    FOREIGN KEY (user_id) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS goals (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  exercise_id BIGINT UNSIGNED DEFAULT NULL,
  goal_type VARCHAR(100) NOT NULL,
  target_value DECIMAL(10, 2) NOT NULL,
  current_value DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  deadline DATE DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_goals_user (user_id),
  KEY idx_goals_exercise (exercise_id),
  CONSTRAINT fk_goals_user
    FOREIGN KEY (user_id) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_goals_exercise
    FOREIGN KEY (exercise_id) REFERENCES exercises (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS workout_logs (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  workout_id BIGINT UNSIGNED NOT NULL,
  exercise_id BIGINT UNSIGNED NOT NULL,
  sets SMALLINT UNSIGNED NOT NULL,
  reps SMALLINT UNSIGNED NOT NULL,
  weight DECIMAL(7, 2) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_workout_logs_workout (workout_id),
  KEY idx_workout_logs_exercise (exercise_id),
  CONSTRAINT fk_workout_logs_workout
    FOREIGN KEY (workout_id) REFERENCES workouts (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_workout_logs_exercise
    FOREIGN KEY (exercise_id) REFERENCES exercises (id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;
INSERT INTO users (name, email, password_hash, role) VALUES
 ('Admin User','admin@fitpulse.com','$2y$10$3ndkWhMtNwVoxAYlU2dInOytuoIYfObD1g2X6txJNgcTl0a7bVk3C','admin'),
 ('Amar Hodzic','amar@fitpulse.com','$2y$10$3ndkWhMtNwVoxAYlU2dInOytuoIYfObD1g2X6txJNgcTl0a7bVk3C','user'),
 ('Lejla Kovac','lejla@fitpulse.com','$2y$10$3ndkWhMtNwVoxAYlU2dInOytuoIYfObD1g2X6txJNgcTl0a7bVk3C','user'),
 ('Tarik Begic','tarik@fitpulse.com','$2y$10$3ndkWhMtNwVoxAYlU2dInOytuoIYfObD1g2X6txJNgcTl0a7bVk3C','user');

INSERT INTO exercises (name, muscle_group, equipment, external_api_id) VALUES
 ('Bench press','Chest','Barbell',NULL),
 ('Barbell squat','Legs','Barbell',NULL),
 ('Pull-up','Back','Bodyweight',NULL),
 ('Overhead press','Shoulders','Barbell',NULL),
 ('Deadlift','Back','Barbell',NULL),
 ('Biceps curl','Arms','Dumbbell',NULL),
 ('Plank','Core','Bodyweight',NULL);

INSERT INTO workouts (user_id, title, workout_date, notes) VALUES
 (2,'Push day','2026-10-26','Felt strong'),
 (2,'Leg day','2026-10-28',NULL),
 (3,'Full body','2026-10-27','First week back'),
 (4,'Pull day','2026-10-29',NULL);

INSERT INTO workout_logs (workout_id, exercise_id, sets, reps, weight) VALUES
 (1,1,4,8,70.00),(1,4,3,10,40.00),
 (2,2,4,6,90.00),(2,5,3,5,110.00),
 (3,2,3,10,40.00),(3,7,3,45,0),
 (4,3,4,8,0),(4,6,3,12,14.00);

INSERT INTO goals (user_id, exercise_id, goal_type, target_value, current_value, deadline) VALUES
 (2,1,'Strength',80,70,'2026-12-31'),
 (2,NULL,'Body weight',75,79,'2027-03-15'),
 (3,2,'Strength',60,40,'2027-01-31'),
 (4,NULL,'Cardio',25,31,'2027-02-01');