-- Active: 1741822176111@@127.0.0.1@3306@sms

create database sms;

use sms;

CREATE TABLE `user_roles` (
  `role_id` CHAR(10) PRIMARY KEY NOT NULL,
  `role_name` VARCHAR(64) NOT NULL,
  `role_description` TEXT
);

CREATE TABLE `genders` (
  `gender_id` CHAR(10) PRIMARY KEY NOT NULL,
  `gender_name` VARCHAR(64) NOT NULL,
  `gender_description` TEXT
);

CREATE TABLE `user_accounts` (
  `user_uuid` VARCHAR(64) PRIMARY KEY NOT NULL,
  `user_fname` VARCHAR(64) NOT NULL,
  `user_lname` VARCHAR(64) NOT NULL,
  `user_pwd` VARCHAR(255) NOT NULL,
  `user_pwd_last_changed` DATETIME,
  `user_email` VARCHAR(64) NOT NULL,
  `user_gender` CHAR(10) NOT NULL,
  `user_role` VARCHAR(10) NOT NULL,
  `user_created_on` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `notif_types` (
  `notif_type_id` CHAR(15) PRIMARY KEY NOT NULL,
  `notif_type_name` VARCHAR(100) NOT NULL,
  `notif_type_description` TEXT
);

CREATE TABLE `notifications` (
  `notif_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `notif_title` VARCHAR(64) NOT NULL,
  `notif_sent_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `notif_message` TEXT NOT NULL,
  `notif_type` CHAR(15) NOT NULL,
  `notif_sent_by` VARCHAR(64) NOT NULL,
  `notif_sent_to` VARCHAR(64) NOT NULL
);

CREATE TABLE `log_type` (
  `log_type_id` VARCHAR(10) PRIMARY KEY NOT NULL,
  `log_type_name` VARCHAR(20) NOT NULL,
  `log_type_description` TEXT
);

CREATE TABLE `log_status` (
  `log_status_id` VARCHAR(10) PRIMARY KEY NOT NULL,
  `log_status_name` VARCHAR(20) NOT NULL,
  `log_status_description` TEXT
);

CREATE TABLE `log_records` (
  `log_rec_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `log_rec_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `log_rec_type` VARCHAR(10) NOT NULL,
  `log_rec_status` VARCHAR(10) NOT NULL,
  `log_rec_uuid` VARCHAR(64) NOT NULL
);

CREATE TABLE `file_types` (
  `file_type_id` VARCHAR(64) PRIMARY KEY,
  `file_type_name` VARCHAR(64) NOT NULL,
  `file_type_allowed` BOOLEAN NOT NULL,
  `file_ext_name` CHAR(10) NOT NULL,
  `file_mime_type` VARCHAR(255) NOT NULL,
  `file_type_description` TEXT
);

CREATE TABLE `document_types` (
  `document_id` VARCHAR(64) PRIMARY KEY,
  `document_name` VARCHAR(64) NOT NULL,
  `document_allowed` BOOLEAN NOT NULL,
  `document_description` TEXT
);

CREATE TABLE `uploaded_files` (
  `file_id` VARCHAR(64) UNIQUE PRIMARY KEY,
  `file_name` VARCHAR(255) NOT NULL,
  `file_stored_name` VARCHAR(255) NOT NULL,
  `file_path` TEXT NOT NULL,
  `file_type` VARCHAR(50) NOT NULL,
  `file_doc_type` VARCHAR(64),
  `file_size` INT NOT NULL,
  `file_uploaded_by` VARCHAR(64) NOT NULL,
  `file_upload_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `file_password` VARCHAR(255)
);

CREATE TABLE `registration_status` (
  `registration_status_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `registration_status_name` VARCHAR(64) UNIQUE NOT NULL,
  `registration_status_description` VARCHAR(64)
);

CREATE TABLE `registered_students` (
  `student_learning_ref_number` VARCHAR(12) UNIQUE NOT NULL,
  `student_account_id` VARCHAR(64) UNIQUE NOT NULL,
  `student_registration_status` VARCHAR(64) NOT NULL,
  `student_fname` VARCHAR(64) NOT NULL,
  `student_lname` VARCHAR(64) NOT NULL,
  `student_mname` VARCHAR(64),
  `student_requested_strand` VARCHAR(64) NOT NULL,
  `student_age` INT(2) NOT NULL,
  `student_gender` CHAR(10) NOT NULL,
  `student_bday` DATE NOT NULL,
  `student_registered_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `student_rejected_reg_on` DATETIME,
  `student_registration_approved_on` DATETIME,
  `if_rejected_reason` TEXT,
  `student_birth_certificate` VARCHAR (64) NOT NULL,
  `student_enrollment_form` VARCHAR (64) NOT NULL,
  `student_form_127` VARCHAR (64) NOT NULL,
  `student_good_moral_cerf` VARCHAR (64) NOT NULL,
  `student_report_card` VARCHAR (64) NOT NULL,
  `student_photo` VARCHAR (64) NOT NULL,
  PRIMARY KEY (`student_learning_ref_number`, `student_account_id`)
);

CREATE TABLE `registered_teachers` (
  `teacher_professional_id` VARCHAR(20) UNIQUE NOT NULL,
  `teacher_account_id` VARCHAR(64) UNIQUE NOT NULL,
  `teacher_registration_status` VARCHAR(64) NOT NULL,
  `teacher_fname` VARCHAR(64) NOT NULL,
  `teacher_lname` VARCHAR(64) NOT NULL,
  `teacher_mname` VARCHAR(64),
  `teacher_age` INT(2) NOT NULL,
  `teacher_gender` CHAR(10),
  `teacher_bday` DATE NOT NULL,
  `teacher_registered_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`teacher_professional_id`, `teacher_account_id`)
);

CREATE TABLE `building` (
  `building_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `building_name` VARCHAR(64) NOT NULL,
  `building_description` TEXT
);

CREATE TABLE `floor` (
  `floor_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `floor_name` VARCHAR(64) NOT NULL,
  `floor_building` VARCHAR(64) NOT NULL,
  `floor_description` TEXT
);

CREATE TABLE `room` (
  `room_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `room_floor` VARCHAR(64) NOT NULL,
  `room_description` TEXT
);

CREATE TABLE `track` (
  `track_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `track_name` VARCHAR(64) UNIQUE NOT NULL,
  `track_description` TEXT
);

CREATE TABLE `strand` (
  `strand_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `strand_name` VARCHAR(64) UNIQUE NOT NULL,
  `strand_track` VARCHAR(64) NOT NULL,
  `strand_description` TEXT
);

CREATE TABLE `class` (
  `class_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `class_adviser` VARCHAR(64) UNIQUE NOT NULL,
  `class_section_name` VARCHAR(64) UNIQUE NOT NULL,
  `class_student_limit` INT(2) NOT NULL,
  `class_strand` VARCHAR(64) NOT NULL,
  `class_room` VARCHAR(64) UNIQUE NOT NULL,
  `class_grade_level` ENUM("11", "12") NOT NULL
);

CREATE TABLE `class_students` (
  `class_student_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `class_student_lrn` VARCHAR(12) NOT NULL,
  `class_id` VARCHAR(64) NOT NULL
);

CREATE TABLE `subject_types` (
  `subject_type_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `subject_type_name` VARCHAR(64) NOT NULL UNIQUE,
  `subject_type_desc` TEXT NOT NULL
);

CREATE TABLE `subjects` (
  `subject_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `subject_type` VARCHAR(64) NOT NULL,
  `subject_name` VARCHAR(255) NOT NULL,
  `subject_description` TEXT NOT NULL,
  `subject_strand` VARCHAR(10) NOT NULL,
  `subject_time_added` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`subject_type`) REFERENCES `subject_types`(`subject_type_id`) ON DELETE CASCADE
);

CREATE TABLE `subject_subtopics` (
  `subtopic_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `subtopic_name` VARCHAR(64) UNIQUE NOT NULL,
  `subtopic_subject` VARCHAR(64) NOT NULL,
  `subtopic_description` TEXT
);

CREATE TABLE `subject_teacher` (
  `subject_teacher_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `teacher_id` VARCHAR(20) NOT NULL,
  `class_id` VARCHAR(64) UNIQUE NOT NULL,
  `subject_id` VARCHAR(64) UNIQUE NOT NULL
);

CREATE TABLE `days` (
  `day_id` CHAR(10) PRIMARY KEY NOT NULL,
  `day_name` VARCHAR(30) UNIQUE NOT NULL,
  `day_description` TEXT 
);

CREATE TABLE `class_schedules` (
  `schedule_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `class_id` VARCHAR(64) NOT NULL,
  `subject_id` VARCHAR(64) NOT NULL,
  `teacher_id` VARCHAR(20) NOT NULL,
  `day_of_week` CHAR(10) NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL
);

CREATE TABLE `assignments` (
  `assignment_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `assignment_title` VARCHAR(255) NOT NULL,
  `assignment_description` TEXT,
  `assignment_due_date` DATETIME NOT NULL,
  `assignment_created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `assignment_created_by` VARCHAR(64) NOT NULL,
  `class_id` VARCHAR(64) NOT NULL,
  `subject_id` VARCHAR(64) NOT NULL
);

CREATE TABLE `assignment_attachements` (
  `attachement_id` VARCHAR (64) PRIMARY KEY,
  `assignment_id` VARCHAR (64) NOT NULL,
  `assignment_uploaded_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `assignment_file_id` VARCHAR (64) NOT NULL
);

ALTER TABLE `assignment_attachements` ADD FOREIGN KEY (`assignment_file_id`) REFERENCES `uploaded_files`(`file_id`) ON DELETE CASCADE;
ALTER TABLE `assignment_attachements` ADD FOREIGN KEY (`assignment_id`) REFERENCES `assignments`(`assignment_id`) ON DELETE CASCADE;


CREATE TABLE `student_submissions` (
  `submission_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `assignment_id` VARCHAR(64) NOT NULL,
  `student_lrn` VARCHAR(12) NOT NULL,
  `submission_file_id` VARCHAR(64),
  `submission_text` TEXT,
  `submission_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `submission_status` ENUM("pending", "submitted", "failed", "completed") NOT NULL
);

CREATE TABLE `grade_status` (
  `grade_status_id` CHAR(15) PRIMARY KEY NOT NULL,
  `grade_status_name` VARCHAR(64) NOT NULL,
  `grade_status_description` TEXT
);

CREATE TABLE `semester_level` (
  `semester_level_id` CHAR(15) PRIMARY KEY NOT NULL,
  `semester_name` VARCHAR(64) NOT NULL,
  `semester_description` TEXT
);

CREATE TABLE `quarter_level` (
  `quarter_level_id` CHAR (15) PRIMARY KEY NOT NULL,
  `quarter_name` VARCHAR (64) NOT NULL,
  `quarter_description` TEXT
);

CREATE TABLE `student_grade_records` (
  `grade_record_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `subject` VARCHAR(64) UNIQUE NOT NULL,
  `student_lrn` VARCHAR(12) UNIQUE NOT NULL,
  `grade_record_stat` CHAR(15) NOT NULL,
  `class_id` VARCHAR(64) NOT NULL,
  `semester` CHAR(15) NOT NULL,
  `quarter` CHAR (15) NOT NULL
);

CREATE TABLE `student_score_records` (
  `score_record_id` VARCHAR(64) NOT NULL,
  `score_value` INT(2) NOT NULL,
  `submitted_assignment` VARCHAR(64) NOT NULL,
  `subject_teacher` VARCHAR(64) NOT NULL,
  `added_on` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `student_lrn` VARCHAR(12) NOT NULL
);

CREATE TABLE `attendance_sheet` (
  `attendance_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `class_id` VARCHAR(64) NOT NULL,
  `attendance_date` DATE NOT NULL,
  `first_period_start` TIME NOT NULL,
  `first_period_end` TIME NOT NULL,
  `second_period_start` TIME,
  `second_period_end` TIME
);

CREATE TABLE `student_attendance` (
  `record_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `attendance_id` VARCHAR(64) NOT NULL,
  `student_id` VARCHAR(64) NOT NULL,
  `period` ENUM("First","Second") NOT NULL,
  `action` ENUM("IN","OUT") NOT NULL,
  `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `attendance_summary` (
  `summary_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `attendance_id` VARCHAR(64) NOT NULL,
  `student_id` VARCHAR(64) NOT NULL,
  `first_in_time` TIME,
  `first_out_time` TIME,
  `second_in_time` TIME,
  `second_out_time` TIME,
  `status` ENUM("On Time","Late","Absent","Cut Class") NOT NULL,
  `processed_on` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `private_chats` (
  `chat_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `sender_id` VARCHAR(64) NOT NULL,
  `receiver_id` VARCHAR(64) NOT NULL,
  `message` TEXT NOT NULL,
  `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` BOOLEAN DEFAULT false
);

CREATE TABLE `chat_reactions` (
  `reaction_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `chat_id` VARCHAR(64) NOT NULL,
  `user_id` VARCHAR(64) NOT NULL,
  `reaction_type` VARCHAR(64) NOT NULL,
  `reacted_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `group_chats` (
  `group_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `group_name` VARCHAR(255) NOT NULL,
  `created_by` VARCHAR(64) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `group_members` (
  `group_member_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `group_id` VARCHAR(64) NOT NULL,
  `user_id` VARCHAR(64) NOT NULL,
  `is_admin` BOOLEAN DEFAULT false,
  `joined_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `group_chat_messages` (
  `message_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `group_id` VARCHAR(64) NOT NULL,
  `sender_id` VARCHAR(64) NOT NULL,
  `message` TEXT NOT NULL,
  `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` BOOLEAN DEFAULT false
);

CREATE TABLE `group_chat_reactions` (
  `reaction_id` VARCHAR(64) PRIMARY KEY NOT NULL,
  `message_id` VARCHAR(64) NOT NULL,
  `user_id` VARCHAR(64) NOT NULL,
  `reaction_type` VARCHAR(64) NOT NULL,
  `reacted_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `registered_students` ADD FOREIGN KEY (`student_requested_strand`) REFERENCES `strand`(`strand_id`);
ALTER TABLE `user_accounts` ADD FOREIGN KEY (`user_role`) REFERENCES `user_roles` (`role_id`);
ALTER TABLE `user_accounts` ADD FOREIGN KEY (`user_gender`) REFERENCES `genders` (`gender_id`);
ALTER TABLE `notifications` ADD FOREIGN KEY (`notif_sent_by`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `notifications` ADD FOREIGN KEY (`notif_sent_to`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `notifications` ADD FOREIGN KEY (`notif_type`) REFERENCES `notif_types` (`notif_type_id`);
ALTER TABLE `log_records` ADD FOREIGN KEY (`log_rec_status`) REFERENCES `log_status` (`log_status_id`);
ALTER TABLE `log_records` ADD FOREIGN KEY (`log_rec_type`) REFERENCES `log_type` (`log_type_id`);
ALTER TABLE `log_records` ADD FOREIGN KEY (`log_rec_uuid`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `uploaded_files` ADD FOREIGN KEY (`file_uploaded_by`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `uploaded_files` ADD FOREIGN KEY (`file_type`) REFERENCES `file_types` (`file_type_id`);
ALTER TABLE `uploaded_files` ADD FOREIGN KEY (`file_doc_type`) REFERENCES `document_types` (`document_id`);
ALTER TABLE `registered_students` ADD FOREIGN KEY (`student_registration_status`) REFERENCES `registration_status` (`registration_status_id`);
ALTER TABLE `registered_students` ADD FOREIGN KEY (`student_account_id`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `registered_students` ADD FOREIGN KEY (`student_enrollment_form`) REFERENCES `uploaded_files`(`file_id`);
ALTER TABLE `registered_teachers` ADD FOREIGN KEY (`teacher_registration_status`) REFERENCES `registration_status` (`registration_status_id`);
ALTER TABLE `registered_teachers` ADD FOREIGN KEY (`teacher_account_id`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `floor` ADD FOREIGN KEY (`floor_building`) REFERENCES `building` (`building_id`);
ALTER TABLE `room` ADD FOREIGN KEY (`room_floor`) REFERENCES `floor` (`floor_id`);
ALTER TABLE `strand` ADD FOREIGN KEY (`strand_track`) REFERENCES `track` (`track_id`);
ALTER TABLE `class` ADD FOREIGN KEY (`class_strand`) REFERENCES `strand` (`strand_id`);
ALTER TABLE `class` ADD FOREIGN KEY (`class_room`) REFERENCES `room` (`room_id`);
ALTER TABLE `class` ADD FOREIGN KEY (`class_adviser`) REFERENCES `registered_teachers` (`teacher_professional_id`);
ALTER TABLE `class_students` ADD FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);
ALTER TABLE `class_students` ADD FOREIGN KEY (`class_student_lrn`) REFERENCES `registered_students` (`student_learning_ref_number`);
ALTER TABLE `subject_subtopics` ADD FOREIGN KEY (`subtopic_subject`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;
ALTER TABLE `subjects` ADD FOREIGN KEY (`subject_strand`) REFERENCES `strand` (`strand_id`) ON DELETE CASCADE;
ALTER TABLE `subject_teacher` ADD FOREIGN KEY (`teacher_id`) REFERENCES `registered_teachers` (`teacher_professional_id`);
ALTER TABLE `subject_teacher` ADD FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);
ALTER TABLE `class_schedules` ADD FOREIGN KEY (`day_of_week`) REFERENCES `days` (`day_id`);
ALTER TABLE `class_schedules` ADD FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);
ALTER TABLE `class_schedules` ADD FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;
ALTER TABLE `class_schedules` ADD FOREIGN KEY (`teacher_id`) REFERENCES `registered_teachers` (`teacher_professional_id`);
ALTER TABLE `assignments` ADD FOREIGN KEY (`assignment_created_by`) REFERENCES `registered_teachers` (`teacher_professional_id`);
ALTER TABLE `assignments` ADD FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);
ALTER TABLE `assignments` ADD FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;
ALTER TABLE `student_submissions` ADD FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`);
ALTER TABLE `student_submissions` ADD FOREIGN KEY (`student_lrn`) REFERENCES `registered_students` (`student_learning_ref_number`);
ALTER TABLE `student_submissions` ADD FOREIGN KEY (`submission_file_id`) REFERENCES `uploaded_files` (`file_id`);
ALTER TABLE `student_grade_records` ADD FOREIGN KEY (`grade_record_stat`) REFERENCES `grade_status` (`grade_status_id`);
ALTER TABLE `student_grade_records` ADD FOREIGN KEY (`student_lrn`) REFERENCES `registered_students` (`student_learning_ref_number`);
ALTER TABLE `student_grade_records` ADD FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);
ALTER TABLE `student_grade_records` ADD FOREIGN KEY (`subject`) REFERENCES `subjects` (`subject_id`);
ALTER TABLE `student_grade_records` ADD FOREIGN KEY (`semester`) REFERENCES `semester_level` (`semester_level_id`);
ALTER TABLE `student_grade_records` ADD FOREIGN KEY (`quarter`) REFERENCES `quarter_level`(`quarter_level_id`);
ALTER TABLE `student_score_records` ADD FOREIGN KEY (`submitted_assignment`) REFERENCES `student_submissions` (`submission_id`);
ALTER TABLE `student_score_records` ADD FOREIGN KEY (`student_lrn`) REFERENCES `registered_students` (`student_learning_ref_number`);
ALTER TABLE `student_score_records` ADD FOREIGN KEY (`subject_teacher`) REFERENCES `registered_teachers` (`teacher_professional_id`);
ALTER TABLE `attendance_summary` ADD FOREIGN KEY (`attendance_id`) REFERENCES `attendance_sheet` (`attendance_id`);
ALTER TABLE `attendance_summary` ADD FOREIGN KEY (`student_id`) REFERENCES `registered_students` (`student_learning_ref_number`);
ALTER TABLE `student_attendance` ADD FOREIGN KEY (`attendance_id`) REFERENCES `attendance_sheet` (`attendance_id`);
ALTER TABLE `student_attendance` ADD FOREIGN KEY (`student_id`) REFERENCES `registered_students` (`student_learning_ref_number`);
ALTER TABLE `attendance_sheet` ADD FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);
ALTER TABLE `private_chats` ADD FOREIGN KEY (`sender_id`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `private_chats` ADD FOREIGN KEY (`receiver_id`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `chat_reactions` ADD FOREIGN KEY (`chat_id`) REFERENCES `private_chats` (`chat_id`);
ALTER TABLE `chat_reactions` ADD FOREIGN KEY (`user_id`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `group_chats` ADD FOREIGN KEY (`created_by`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `group_members` ADD FOREIGN KEY (`group_id`) REFERENCES `group_chats` (`group_id`);
ALTER TABLE `group_members` ADD FOREIGN KEY (`user_id`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `group_chat_messages` ADD FOREIGN KEY (`group_id`) REFERENCES `group_chats` (`group_id`);
ALTER TABLE `group_chat_messages` ADD FOREIGN KEY (`sender_id`) REFERENCES `user_accounts` (`user_uuid`);
ALTER TABLE `group_chat_reactions` ADD FOREIGN KEY (`message_id`) REFERENCES `group_chat_messages` (`message_id`);
ALTER TABLE `group_chat_reactions` ADD FOREIGN KEY (`user_id`) REFERENCES `user_accounts` (`user_uuid`);




-- Log Type --
INSERT INTO `log_type` (`log_type_id`, `log_type_name`, `log_type_description`) VALUES
('LT001', 'Login', 'User logged into the system'),
('LT002', 'Logout', 'User logged out of the system'),
('LT003', 'Failed Login', 'User attempted to log in but failed'),
('LT004', 'Password Change', 'User changed their password'),
('LT005', 'Account Update', 'User updated account details'),
('LT006', 'Account Deletion', 'User requested account deletion'),
('LT007', 'Session Timeout', 'User session expired due to inactivity'),
("LT008", "Signup", "Account creation into the system.");

-- Insert data into log_status (Log processing status)
INSERT INTO `log_status` (`log_status_id`, `log_status_name`, `log_status_description`) VALUES
('LS001', 'Success', 'The action was completed successfully'),
('LS002', 'Failed', 'The action failed to complete'),
('LS003', 'Pending', 'The action is awaiting processing'),
('LS004', 'Unauthorized', 'The action was attempted without proper authorization'),
('LS005', 'Invalid Data', 'The action failed due to invalid input or missing data');

-- Insert data into genders
INSERT INTO `genders` (`gender_id`, `gender_name`, `gender_description`) VALUES
('G001', 'Male', 'Biological male'),
('G002', 'Female', 'Biological female'),
('G004', 'Prefer Not to Say', 'User chose not to disclose gender');

INSERT INTO `user_roles` (`role_id`, `role_name`, `role_description`) VALUES
("STU", "Student", "Just a regular motherfucker."),
("SUBJTEA", "Subject Teacher", "A subject teacher assigned to a classroom with a subject."),
("ADVTEA", "Adviser Teacher", "An adviser teacher of a class."),
("ADM", "Administrator", "A school personnel who manages daily operations."),
("SUADM", "Super Administrator", "A school personel who has access to all (principal).");

INSERT INTO `document_types` (`document_id`, `document_name`, `document_allowed`, `document_description`) VALUES
("form137", "Form 137", TRUE, "Student permanent record."),
('assignment', 'Student Assignment', TRUE, 'Documents submitted by students as part of their coursework.'),
('lrn', 'Learner Reference Number (LRN)', TRUE, 'A unique 12-digit number assigned to each student in the education system.'),
('report_card', 'Report Card', TRUE, 'Official record of a student’s academic performance.'),
('transcript', 'Transcript of Records', TRUE, 'Detailed record of all subjects and grades obtained.'),
('diploma_cert', 'Diploma Certificate', TRUE, 'Official certification that a student has graduated.'),
('enrollment_form', 'Enrollment Form', TRUE, 'Document required for student registration.'),
('id_card', 'Student ID Card', TRUE, 'Official identification card for students.'),
('good_moral', 'Good Moral Certificate', TRUE, 'Certification of a student’s good conduct.'),
('birth_cert', 'Birth Certificate', TRUE, 'Proof of birth and identity, usually required for enrollment.'),
('medical_cert', 'Medical Certificate', TRUE, 'Proof of a student’s health condition.'),
('guardian_id', 'Parent/Guardian ID', TRUE, 'Identification card of the parent or legal guardian.'),
('proof_residency', 'Proof of Residency', TRUE, 'Document verifying the student’s residence.'),
('student_photo', 'Passport-Sized Photo', TRUE, 'Required for student ID and official records.'),
('scholarship_doc', 'Scholarship Documents', TRUE, 'Required documents for applying for scholarships.'),
('clearance_form', 'School Clearance Form', TRUE, 'Required to ensure a student has settled all obligations before transfer or graduation.'),
('recommendation', 'Recommendation Letter', TRUE, 'A letter from a teacher or administrator recommending a student for something.'),
('internship_cert', 'Internship Certificate', TRUE, 'Certification of completion of an internship program.'),
('disciplinary_report', 'Disciplinary Report', TRUE, 'Record of any disciplinary actions taken against a student.'),
('attendance_report', 'Attendance Report', TRUE, 'Record of a student’s attendance throughout a term.'),
('research_paper', 'Research Paper', TRUE, 'Academic research submitted by students.'),
('thesis_doc', 'Thesis Document', TRUE, 'Final academic document required for graduation in some programs.'),
('parent_consent', 'Parental Consent Form', TRUE, 'Permission slip required for certain student activities.'),
('financial_statement', 'Financial Statement', TRUE, 'Document related to tuition fees and financial aid.'),
('extracurricular_cert', 'Extracurricular Certificate', TRUE, 'Certification for participation in clubs, sports, or other activities.'),
('other', 'Other', TRUE, 'Other set of files.');


INSERT INTO `file_types` 
(`file_type_id`, `file_type_name`, `file_type_allowed`, `file_ext_name`, `file_mime_type`, `file_type_description`) 
VALUES
('pdf_doc', 'PDF Document', TRUE, 'pdf', 'application/pdf', 'Portable Document Format'),
('word_doc', 'Word Document', TRUE, 'doc', 'application/msword', 'Microsoft Word 97-2003 document'),
('word_docx', 'Word Document (New)', TRUE, 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Microsoft Word document (modern format)'),
('excel_xls', 'Excel Spreadsheet', TRUE, 'xls', 'application/vnd.ms-excel', 'Microsoft Excel 97-2003 spreadsheet'),
('excel_xlsx', 'Excel Spreadsheet (New)', TRUE, 'xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Microsoft Excel spreadsheet (modern format)'),
('ppt_ppt', 'PowerPoint Presentation', TRUE, 'ppt', 'application/vnd.ms-powerpoint', 'Microsoft PowerPoint 97-2003 presentation'),
('ppt_pptx', 'PowerPoint Presentation (New)', TRUE, 'pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'Microsoft PowerPoint presentation (modern format)'),
('txt_plain', 'Plain Text File', TRUE, 'txt', 'text/plain', 'Simple text file'),
('csv_data', 'CSV File', TRUE, 'csv', 'text/csv', 'Comma-separated values file'),
('json_data', 'JSON File', TRUE, 'json', 'application/json', 'JavaScript Object Notation file'),
('xml_data', 'XML File', TRUE, 'xml', 'application/xml', 'Extensible Markup Language file'),
('html_web', 'HTML File', TRUE, 'html', 'text/html', 'HyperText Markup Language file'),
('css_style', 'CSS File', TRUE, 'css', 'text/css', 'Cascading Style Sheets file'),
('js_script', 'JavaScript File', TRUE, 'js', 'application/javascript', 'JavaScript file'),
('php_script', 'PHP Script', TRUE, 'php', 'application/x-httpd-php', 'PHP Hypertext Preprocessor file'),
('py_script', 'Python Script', TRUE, 'py', 'text/x-python', 'Python script file'),
('c_source', 'C Source Code', TRUE, 'c', 'text/x-csrc', 'C programming language source file'),
('cpp_source', 'C++ Source Code', TRUE, 'cpp', 'text/x-c++src', 'C++ programming language source file'),
('java_source', 'Java Source Code', TRUE, 'java', 'text/x-java-source', 'Java programming language source file'),
('sql_script', 'SQL Script', TRUE, 'sql', 'application/sql', 'Structured Query Language script file'),
('jpg_image', 'JPEG Image', TRUE, 'jpg', 'image/jpeg', 'JPEG compressed image file'),
('jpeg_image', 'JPEG Image', TRUE, 'jpeg', 'image/jpeg', 'JPEG compressed image file'),
('png_image', 'PNG Image', TRUE, 'png', 'image/png', 'Portable Network Graphics image file'),
('gif_image', 'GIF Image', TRUE, 'gif', 'image/gif', 'Graphics Interchange Format file'),
('bmp_image', 'BMP Image', TRUE, 'bmp', 'image/bmp', 'Bitmap image file'),
('tiff_image', 'TIFF Image', TRUE, 'tiff', 'image/tiff', 'Tagged Image File Format file'),
('ico_icon', 'Icon File', TRUE, 'ico', 'image/vnd.microsoft.icon', 'Windows icon file'),
('svg_vector', 'SVG File', TRUE, 'svg', 'image/svg+xml', 'Scalable Vector Graphics file'),
('mp3_audio', 'MP3 Audio', TRUE, 'mp3', 'audio/mpeg', 'MP3 compressed audio file'),
('wav_audio', 'WAV Audio', TRUE, 'wav', 'audio/wav', 'Waveform Audio file'),
('ogg_audio', 'OGG Audio', TRUE, 'ogg', 'audio/ogg', 'OGG Vorbis compressed audio file'),
('flac_audio', 'FLAC Audio', TRUE, 'flac', 'audio/flac', 'Free Lossless Audio Codec file'),
('mp4_video', 'MP4 Video', TRUE, 'mp4', 'video/mp4', 'MPEG-4 video file'),
('avi_video', 'AVI Video', TRUE, 'avi', 'video/x-msvideo', 'Audio Video Interleave file'),
('mov_video', 'MOV Video', TRUE, 'mov', 'video/quicktime', 'Apple QuickTime movie file'),
('wmv_video', 'WMV Video', TRUE, 'wmv', 'video/x-ms-wmv', 'Windows Media Video file'),
('mkv_video', 'MKV Video', TRUE, 'mkv', 'video/x-matroska', 'Matroska multimedia container file'),
('zip_archive', 'ZIP Archive', TRUE, 'zip', 'application/zip', 'Compressed archive file'),
('rar_archive', 'RAR Archive', TRUE, 'rar', 'application/vnd.rar', 'WinRAR compressed archive file'),
('7z_archive', '7z Archive', TRUE, '7z', 'application/x-7z-compressed', '7-Zip compressed archive file'),
('tar_archive', 'TAR Archive', TRUE, 'tar', 'application/x-tar', 'Tape Archive file'),
('gz_archive', 'GZIP Archive', TRUE, 'gz', 'application/gzip', 'Gzip compressed archive file'),
('iso_disc', 'ISO Image', TRUE, 'iso', 'application/x-iso9660-image', 'Disk image file');

-- Notification Types --
INSERT INTO `notif_types` (`notif_type_id`, `notif_type_name`, `notif_type_description`) VALUES
('general', 'General Notification', 'General announcements or updates for students and staff.'),
('assignment', 'Assignment Notification', 'Alerts regarding new assignments, deadlines, or submissions.'),
('grade_update', 'Grade Update', 'Notifications about new grades or performance reports.'),
('enrollment', 'Enrollment Notification', 'Updates about enrollment status, approvals, or rejections.'),
('event', 'Event Notification', 'Reminders and announcements for upcoming school events.'),
('schedule', 'Schedule Update', 'Changes or updates to class schedules.'),
('payment', 'Payment Reminder', 'Reminders about pending tuition or other school fees.'),
('disciplinary', 'Disciplinary Action', 'Notifications related to disciplinary actions or warnings.'),
('message', 'Message Notification', 'Alerts for new private or group chat messages.'),
('announcement', 'School Announcement', 'Important school-wide announcements.'),
('security', 'Security Alert', 'Urgent security or emergency notifications.'),
('scholarship', 'Scholarship Notification', 'Updates on scholarship applications and approvals.'),
('clearance', 'Clearance Notification', 'Reminders for school clearance completion.'),
('system_update', 'System Update', 'Notifications about system maintenance or new features.'),
('other', 'Other', 'Miscellaneous notifications not covered by other categories.');

-- Registration Status --
INSERT INTO `registration_status` (`registration_status_id`, `registration_status_name`, `registration_status_description`) VALUES 
("PND", "Pending", "Not yet verified."),
("ENR", "Enrolled", "Enrolled as a student."),
("REG", "Registered", "Registered as a teacher."),
("APPL", "Applicant", "Applied as an applicant"),
("ONENR", "On Enrollment", "Approved applicant student. Is on enrollment process."),
("REJ", "Rejected", "Rejected registration or enrollment.");

-- Track --
INSERT INTO `track` (`track_id`, `track_name`, `track_description`) VALUES
('acad', 'Academic Track', 'Designed for students who plan to pursue higher education in universities and colleges.'),
('techvoc', 'Technical-Vocational-Livelihood (TVL) Track', 'Focuses on hands-on skills for employment and entrepreneurship after senior high school.'),
('arts', 'Arts and Design Track', 'For students with a passion for creative and performing arts.'),
('sports', 'Sports Track', 'Prepares students for careers in sports, fitness, and coaching.'),
('core', "core", "core shit");

-- Strand --
INSERT INTO `strand` (`strand_id`, `strand_name`, `strand_track`, `strand_description`) VALUES
('stem', 'Science, Technology, Engineering, and Mathematics (STEM)', 'acad', 'For students interested in pursuing careers in science, engineering, mathematics, and medical fields.'),
('abm', 'Accountancy, Business, and Management (ABM)', 'acad', 'For students who want to enter business, entrepreneurship, finance, or management fields.'),
('humss', 'Humanities and Social Sciences (HUMSS)', 'acad', 'For students aiming for careers in social sciences, education, politics, or communication.'),
('gas', 'General Academic Strand (GAS)', 'acad', 'For students who are undecided on a specific academic path but want a flexible curriculum.'),
('ict', 'Information and Communications Technology (ICT)', 'techvoc', 'Focuses on computer programming, web development, and other IT-related skills.'),
('he', 'Home Economics (HE)', 'techvoc', 'Includes courses in culinary arts, tourism, fashion design, and hospitality management.'),
('ia', 'Industrial Arts (IA)', 'techvoc', 'Covers carpentry, welding, electrical installation, and mechanical skills.'),
('agri', 'Agri-Fishery Arts', 'techvoc', 'Teaches farming, fishery, and agribusiness skills.'),
('ad', 'Arts and Design', 'arts', 'For students interested in painting, music, theater, dance, and multimedia arts.'),
('sp', 'Sports', 'sports', 'For students pursuing careers in professional sports, coaching, physical therapy, and fitness training.'),
("Core", "Core Strand and Subject", "core", "Core strand and subjects.");



-- DEFAULT SUPER ADMINISTRATOR ACCOUNT ---
INSERT INTO user_accounts (user_uuid, user_email, user_fname, user_lname, user_pwd, user_gender, user_role) VALUES 
("d8b78a6b0be56966d2c4", "zsnhsAdmin@gmail.com", "Main", "Administrator", "$2y$10$CwDg8UICO4oBUM2y6LADsOqLXvqYhRXGeEqdMdT6Ulds4mBARe3jq", "G004", "SUADM");

-- PASSWORD --
-- admin@ZSNHSshsHelloWorld --


-- PASSWORDS --
-- @dmin123 --
INSERT INTO user_accounts (user_uuid, user_email, user_fname, user_lname, user_pwd, user_gender, user_role) VALUES 
("lad;fjl;akjdsfsdfkj30srlkj2lkjsdf", "adviserTeacher@gmail.com", "Adviser", "Teacher", "$2y$10$kNqtBOpLYUljOIJNgV/dOlxdMvqPNCN73You/hJGM9xyBoC.VFxK", "G001", "ADVTEA"),
("lsdfkj30srlkja;dfj0u0292lkjsdf", "advTea@gmail.com", "Adv", "Tea", "$2y$10$kNqtBOGpLYUljOIJNgV/dOxdMvqPNCN73You/hJGM9xyBoC.VFxK", "G001", "ADVTEA");

INSERT INTO user_accounts (user_uuid, user_email, user_fname, user_lname, user_pwd, user_gender, user_role) VALUES 
("39dkja;sldj30grlkdllkjsdf", "admin@gmail.com", "Main", "Admin", "$2y$10$kNqtBOGpLYUljOIJNgV/dOlxdMvqPNCN73You/hJGM9xyBoC.VFxK", "G001", "ADM"),
("l3dfkj30lrl;asdjlkj2lkjdf", "adm@gmail.com", "Min", "Adm", "$2y$10$kNqtBOGpLYUljOIJNgV/dOlxdMvqPNCN73You/hJGM9xyBoC.VFxK", "G001", "ADM");


INSERT INTO user_accounts (user_uuid, user_email, user_fname, user_lname, user_pwd, user_gender, user_role) VALUES 
("isdfkj30salkj2lkjsdf", "subjectTeacher@gmail.com", "Subject", "Teacher", "$2y$10$kNqtBOGpLYUljOIJNgV/dOlxdMvqPNCN73You/hJGM9xyBoC.VFxK", "G001", "SUBJTEA");

-- REGISTERED TEACHERS --
INSERT INTO registered_teachers (teacher_professional_id, teacher_account_id, teacher_registration_status, teacher_fname, teacher_lname, teacher_mname, teacher_age, teacher_gender, teacher_bday) VALUES 
("1EDKSL1930", "isdfkj30salkj2lkjsdf", "REG", "Subject", "Teacher", "M", 30, "G001", "2025-03-31 01:54:26"),
("1UDKSL1930", "lad;fjl;akjdsfsdfkj30srlkj2lkjsdf", "REG", "Adviser", "Teacher", "M", 33, "G001", "2025-03-31 01:54:26"),
("1UDKSL1931", "lsdfkj30srlkja;dfj0u0292lkjsdf", "REG", "Adv", "Tea", "M", 33, "G001", "2025-03-31 01:54:26"),
("1UDKSL1911", "39dkja;sldj30grlkdllkjsdf", "REG", "Main", "Admin", "M", 33, "G001", "2025-03-31 01:54:26"),
("1UDKS11911", "l3dfkj30lrl;asdjlkj2lkjdf", "REG", "Min", "Admin", "M", 33, "G001", "2025-03-31 01:54:26");







-- BUILDINGS, FLOOR, AND ROOM --
INSERT INTO building (building_id, building_name) VALUES ("BLDG.C", "BUILDING C");
INSERT INTO floor (floor_id, floor_name, floor_building) VALUES ("4THFLOOR", "FOURTH FLOOR", "BLDG.C");
INSERT INTO room (room_id, room_floor) VALUES ("ICT_PROG_101", "4THFLOOR");

-- DAYS --
INSERT INTO days (day_id, day_name) VALUES 
("a", "Sunday"),
("b", "Monday"),
("c", "Tuesday"),
("d", "Wednesday"),
("e", "Thursday"),
("f", "Friday"),
("g", "Saturday");


INSERT INTO `subject_types` (`subject_type_id`, `subject_type_name`, `subject_type_desc`) VALUES
('CORE', 'Core Subjects', 'Required subjects for all students.'),
('MAJOR', 'Major Subjects', 'Subjects deeply related to a student’s specialization.'),
('MINOR', 'Minor Subjects', 'Supplementary subjects that support major subjects.'),
('ELECTIVE', 'Elective Subjects', 'Optional subjects chosen by students based on interests.'),
('PE', 'Physical Education', 'Subjects focused on physical fitness and sports.'),
('NSTP', 'NSTP Subjects', 'National Service Training Program subjects for community involvement.');

-- Inserting Subjects (🔥 Now Fully Constrained by Subject Type)
INSERT INTO `subjects` (`subject_type`, `subject_id`, `subject_name`, `subject_description`, `subject_strand`) VALUES
('CORE', 'SUBJ-CORE-001', 'Oral Communication', 'Fundamentals of speech, types of speech, and public speaking.', 'Core'),
('CORE', 'SUBJ-CORE-002', 'Reading and Writing', 'Covers text types, academic writing, and critical reading.', 'Core'),
('CORE', 'SUBJ-CORE-003', 'Earth and Life Science', 'Covers natural sciences, ecosystems, and human anatomy.', 'Core'),
('CORE', 'SUBJ-CORE-004', 'General Mathematics', 'Functions, graphs, business math, and probability.', 'Core'),
('CORE', 'SUBJ-CORE-005', 'Statistics and Probability', 'Descriptive and inferential statistics, hypothesis testing.', 'Core'),
('CORE', 'SUBJ-CORE-006', '21st Century Literature', 'Explores literary genres, critical analysis, and world literature.', 'Core'),
('CORE', 'SUBJ-CORE-007', 'Understanding Culture, Society, and Politics', 'Cultural and political systems, globalization.', 'Core'),
('MAJOR', 'SUBJ-STEM-001', 'Pre-Calculus', 'Trigonometry, analytic geometry, and conic sections.', 'STEM'),
('MAJOR', 'SUBJ-STEM-002', 'Basic Calculus', 'Derivatives, integration, and applications.', 'STEM'),
('MAJOR', 'SUBJ-STEM-003', 'Physics', 'Laws of motion, electricity, and waves.', 'STEM'),
('MAJOR', 'SUBJ-STEM-004', 'Chemistry', 'Chemical bonding, reactions, and organic chemistry.', 'STEM'),
('MAJOR', 'SUBJ-STEM-005', 'Biology', 'Cell biology, evolution, and human anatomy.', 'STEM'),
('MAJOR', 'SUBJ-ABM-001', 'Principles of Marketing', 'Market research, branding, and strategies.', 'ABM'),
('MAJOR', 'SUBJ-ABM-002', 'Business Mathematics', 'Accounting, financial ratios, and profit analysis.', 'ABM');

-- Inserting Subject Types
-- Insert subtopics (Core Subjects)
INSERT INTO `subject_subtopics` (`subtopic_id`, `subtopic_name`, `subtopic_subject`, `subtopic_description`) VALUES
('SUBTOPIC-CORE-001', 'Fundamentals of Speech Communication', 'SUBJ-CORE-001', 'Basics of verbal and non-verbal communication.'),
('SUBTOPIC-CORE-002', 'Text Types and Structures', 'SUBJ-CORE-002', 'Different structures of written texts.'),
('SUBTOPIC-CORE-003', 'Origin of the Universe and Earth', 'SUBJ-CORE-003', 'Theories about the origin of the universe and Earth.'),
('SUBTOPIC-CORE-004', 'Functions and Graphs', 'SUBJ-CORE-004', 'Understanding mathematical functions and their visual representations.'),
('SUBTOPIC-CORE-005', 'Descriptive Statistics', 'SUBJ-CORE-005', 'Data collection and basic statistical measures.'),
('SUBTOPIC-CORE-006', 'Philippine Literature', 'SUBJ-CORE-006', 'Exploring literature from different regions of the Philippines.'),
('SUBTOPIC-CORE-007', 'Globalization and Social Change', 'SUBJ-CORE-007', 'Effects of globalization on societies.'),
('SUBTOPIC-STEM-001', 'Trigonometry', 'SUBJ-STEM-001', 'Study of angles, triangles, and trigonometric functions.'),
('SUBTOPIC-STEM-002', 'Derivatives', 'SUBJ-STEM-002', 'Understanding the concept of differentiation.'),
('SUBTOPIC-STEM-003', 'Laws of Motion', 'SUBJ-STEM-003', 'Newton’s laws and their applications.'),
('SUBTOPIC-STEM-004', 'Chemical Reactions', 'SUBJ-STEM-004', 'Types and balancing of chemical reactions.'),
('SUBTOPIC-STEM-005', 'Cell Biology', 'SUBJ-STEM-005', 'Structure and function of cells.'),
('SUBTOPIC-ABM-001', 'Market Research', 'SUBJ-ABM-001', 'Analyzing consumer behavior and market trends.'),
('SUBTOPIC-ABM-002', 'Financial Ratios', 'SUBJ-ABM-002', 'Understanding key financial indicators.');

-- Insert grade status
INSERT INTO `grade_status` (`grade_status_id`, `grade_status_name`, `grade_status_description`) VALUES
('GRADE-STATUS-1', 'Passed', 'The student has met all the requirements and successfully passed the subject.'),
('GRADE-STATUS-2', 'Failed', 'The student did not meet the requirements and failed the subject.'),
('GRADE-STATUS-3', 'Incomplete', 'The student has not completed all the requirements for the subject.'),
('GRADE-STATUS-4', 'Withdrawn', 'The student has officially withdrawn from the subject before completion.'),
('GRADE-STATUS-5', 'Completed', 'The student has completed all necessary coursework but is yet to be graded.'),
('GRADE-STATUS-6', 'Dropped', 'The student has dropped the subject voluntarily after the add/drop period.');

-- Insert semester levels
INSERT INTO `semester_level` (`semester_level_id`, `semester_name`, `semester_description`) VALUES
('SE-LEVEL-2', 'First Semester', 'The first half of the academic year, typically from August to December.'),
('SE-LEVEL-3', 'Second Semester', 'The second half of the academic year, typically from January to May.'),
('SE-LEVEL-4', 'Summer Semester', 'An optional semester, usually between May and July, for catching up or advancing courses.');

-- Insert quarter levels
INSERT INTO `quarter_level` (`quarter_level_id`, `quarter_name`, `quarter_description`) VALUES
('QUARTER-LEVEL-1', 'First Quarter', 'The first quarter of the school year, typically covering the first 9 weeks of lessons.'),
('QUARTER-LEVEL-2', 'Second Quarter', 'The second quarter of the school year, covering weeks 10 to 18 of lessons.'),
('QUARTER-LEVEL-3', 'Third Quarter', 'The third quarter of the school year, typically covering weeks 19 to 27 of lessons.'),
('QUARTER-LEVEL-4', 'Fourth Quarter', 'The fourth and final quarter of the school year, typically covering the last 9 weeks of lessons.');

INSERT INTO class (class_id, class_adviser, class_section_name, class_student_limit, class_strand, class_room, class_grade_level) VALUES 
("lskjdf23011209usfd", "1UDKSL1930", "EPYC", 40, "ict", "ICT_PROG_101", "12");

