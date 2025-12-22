-- Database Schema for School Management System
-- Version 1.0

-- Drop tables if they exist to start from a clean state.
DROP TABLE IF EXISTS `assignment_submissions`;
DROP TABLE IF EXISTS `assignments`;
DROP TABLE IF EXISTS `materials`;
DROP TABLE IF EXISTS `attendances`;
DROP TABLE IF EXISTS `lesson_sessions`;
DROP TABLE IF EXISTS `schedules`;
DROP TABLE IF EXISTS `subjects`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `classes`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;

-- Table for user roles
CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Role name: Developer, Siswa, Guru Mapel, Guru Kelas, Akademik, Kepala Sekolah'
) ENGINE=InnoDB;

-- Table for all users in the system
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_id` INT NOT NULL,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL COMMENT 'Stored as a hash',
    `full_name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table for classes
CREATE TABLE `classes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL COMMENT 'e.g., X-A, XII-IPA-1',
    `homeroom_teacher_id` INT NULL COMMENT 'FK to users table for homeroom teacher (Guru Kelas)',
    FOREIGN KEY (`homeroom_teacher_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Table for student-specific data
CREATE TABLE `students` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL UNIQUE,
    `class_id` INT NULL,
    `student_id_number` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Nomor Induk Siswa (NIS)',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Table for subjects
CREATE TABLE `subjects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT
) ENGINE=InnoDB;

-- Table for weekly class schedules (managed by Akademik)
CREATE TABLE `schedules` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `class_id` INT NOT NULL,
    `subject_id` INT NOT NULL,
    `teacher_id` INT NOT NULL COMMENT 'FK to users table for subject teacher (Guru Mapel)',
    `day_of_week` INT NOT NULL COMMENT '1=Senin, 2=Selasa, ..., 7=Minggu',
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`teacher_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table to track a live lesson session started by a teacher
CREATE TABLE `lesson_sessions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `schedule_id` INT NOT NULL,
    `session_date` DATE NOT NULL,
    `actual_start_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `actual_end_time` DATETIME NULL,
    `attendance_code` VARCHAR(20) NULL UNIQUE,
    `status` ENUM('active', 'ended', 'cancelled') NOT NULL,
    FOREIGN KEY (`schedule_id`) REFERENCES `schedules`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table for teaching materials submitted by teachers
CREATE TABLE `materials` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `lesson_session_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NULL,
    `file_path` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`lesson_session_id`) REFERENCES `lesson_sessions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table for student attendance records
CREATE TABLE `attendances` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `lesson_session_id` INT NOT NULL,
    `student_id` INT NOT NULL,
    `submitted_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('present', 'late', 'absent') NOT NULL,
    FOREIGN KEY (`lesson_session_id`) REFERENCES `lesson_sessions`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table for assignments, including daily tasks, UTS, and UAS
CREATE TABLE `assignments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `class_id` INT NOT NULL,
    `subject_id` INT NOT NULL,
    `teacher_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `type` ENUM('harian', 'uts', 'uas') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `due_date` DATETIME NOT NULL,
    FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`teacher_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table for student submissions for assignments
CREATE TABLE `assignment_submissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `assignment_id` INT NOT NULL,
    `student_id` INT NOT NULL,
    `submitted_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `file_path` VARCHAR(255) NULL,
    `text_answer` TEXT NULL,
    `grade` FLOAT NULL,
    `graded_by` INT NULL COMMENT 'FK to users table for the grader',
    `graded_at` DATETIME NULL,
    FOREIGN KEY (`assignment_id`) REFERENCES `assignments`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`graded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Insert initial data for roles
INSERT INTO `roles` (`name`) VALUES
('Developer'),
('Siswa'),
('Guru Mapel'),
('Guru Kelas'),
('Akademik'),
('Kepala Sekolah');

COMMIT;
