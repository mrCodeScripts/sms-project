<?php

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

class Config {
    protected static $config;

    public static function setConfig (array $config): void {
        if (empty($config)) {
            die("No configurations added!");
        } else {
            self::$config = $config;
        }
    }

    public static function loadConfig (): array {
        if (empty(self::$config)) {
            die("No configurations!");
        } else {
            return self::$config;
        }
    }
}

$config = [
    "GOOGLE_CLIENT_ID" => $_ENV["GOOGLE_CLIENT_ID"],
    "GOOGLE_CLIENT_SECRETE" => $_ENV["GOOGLE_CLIENT_SECRET"],
    "GOOGLE_REDIRECT_URI" => $_ENV["GOOGLE_REDIRECT_URI"],
    "APP_ENABLE" => boolval($_ENV["APP_ENABLE"]),
    "APP_DEBUG" => boolval($_ENV["APP_DEBUG"]),
    "APP_DOMAIN" => $_ENV["APP_DOMAIN"],
    "APP_UPLOAD_PATH" => __DIR__ . "/../uploads",
    "DB_USERNAME" => $_ENV["DB_USERNAME"],
    "DB_NAME" => $_ENV["DB_NAME"],
    "DB_PASSWORD" => $_ENV["DB_PASSWORD"],
    "DB_HOST" => $_ENV["DB_HOST"],
    "DB_OPTION" => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ],
    "APP_FILE_UPLOAD_PATHS" => [
        "other" => "userFiles/",
        "assignment" => "classFiles/assignments/",
        "enrollment_form" => "schoolFiles/enrollmentForms/",
        "birth_cert" => "schoolFiles/birthCertificates/",
        "form137" => "schoolFiles/form137s/",
        "good_moral" => "schoolFiles/goodMoralCerts/",
        "report_card" => "schoolFiles/reportCards/",
        "student_photo" => "schoolFiles/studentPhotos/",
    ],
    "ACC_DEF_SORT_LOG" => "ORDER BY log_rec_time DESC",
    "SYS_ERR_MSG" => [
        "PWD_NOMATCH" => [
            "key" => "PWD_NOMATCH",
            "status" => "failed",
            "refresh" => false,
            "message" => "Passwords does not match or is empty!",
        ],
        "INCOMPLETE_DATA" => [
            "key" => "INCOMPLETE_DATA",
            "status" => "failed",
            "refresh" => false,
            "message" => "Incomplete data!"
        ],
        "INVALID_PWD" => [
            "key" => "INVALID_PWD",
            "status" => "failed",
            "refresh" => false,
            "message" => "ACCESS DENIED: Invalid password!"
        ],
        "GENDER_NOEXIST" => [
            "key" => "GENDER_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Gender does not exist!"
        ],
        "USER_NOEXIST" => [
            "key" => "USER_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "User does not exist!"
        ],
        "EMAIL_INUSE" => [
            "key" => "EMAIL_INUSE",
            "status" => "failed",
            "refresh" => false,
            "message" => "Email already used!",
        ],
        "LOG_FAILED" => [
            "key" => "LOG_FAILED",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to add log record.",
        ],
        "LOG_SUCCESS" => [
            "key" => "LOG_SUCCESS",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly added log record.",
        ],
        "LOGIN_SUCCESS" => [
            "key" => "LOGIN_SUCCESS",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly logged in.",
        ],
        "ACC_NODATA" => [
            "key" => "ACC_NODATA",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to fetch account data!",
        ],
        "ALREADY_LOGGED" => [
            "key" => "ALREADY_LOGGED",
            "status" => "failed",
            "refresh" => false,
            "message" => "User already logged in!"
        ],
        "SUCC_ADDACC" => [
            "key" => "SUCC_ADDACC",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly registered account!",
        ],
        "FAIL_ADDACC" => [
            "key" => "FAIL_ADDACC",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed account registration!",
        ],
        "NO_LOG_REC" => [
            "key" => "NO_LOG_REC",
            "status" => "failed",
            "refresh" => false,
            "message" => "Account has no log record!",
        ],
        "FAILED_ACCSETUP" => [
            "key" => "FAILED_ACCSETUP",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to setup account data!"
        ],
        "SUCC_LOGOUT" => [
            "key" => "SUCC_LOGOUT",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly logged out!",
        ],
        "ALREADY_LOGGED_OUT" => [
            "key" => "ALREADY_LOGGED_OUT",
            "status" => "failed",
            "refresh" => false,
            "message" => "You are already logged out?!",
        ],
        "INCOR_EMAIL_FORMAT" => [
            "key" => "INCOR_EMAIL_FORMAT",
            "status" => "failed",
            "refresh" => false,
            "message" => "Incorrect email format!",
        ],
        "ACC_NOPWD" => [
            "key" => "ACC_NOPWD",
            "status" => "failed",
            "refresh" => false,
            "message" => "Account has no password set!",
        ],
        "SUCC_PWD_CHNG" => [
            "key" => "SUCC_PWD_CHNG",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly changed password!",
        ],
        "FAIL_PWD_CHNG" => [
            "key" => "FAIL_PWD_CHNG",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to change password!",
        ],
        "FAIL_PWD_CHNG_TIMEC" => [
            "key" => "FAIL_PWD_CHNG_TIMEC",
            "status" => "failed",
            "refresh" => false,
            "message" => "Password change max every 6 months."
        ],
        "SUADM_ADD_USER_SUCC" => [
            "key" => "SUADM_ADD_USER_SUCC",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly added a user!"
        ],
        "INV_SUADM" => [
            "key" => "INV_SUADM",
            "status" => "failed",
            "refresh" => false,
            "message" => "WARNING: Not a super admin!"
        ],
        "STU_LRN_USED" => [
            "key" => "STU_LRN_USED",
            "status" => "failed",
            "refresh" => false,
            "message" => "WARNING: LRN is already used!",
        ],
        "STU_REG_FAILED" => [
            "key" => "STU_REG_FAILED",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to enroll student!",
        ],
        "STU_REG_SUCC" => [
            "key" => "STU_REG_SUCC",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly enrolled student!"
        ],
        "FILETYPE_NOTALLOWED" => [
            "key" => "FILETYPE_NOTALLOWED",
            "status" => "failed",
            "refresh" => false,
            "message" => "File type not allowed!",
        ],
        "SUCC_ADD_CLASS" => [
            "key" => "SUCC_ADD_CLASS",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly added class!",
        ],
        "SECTION_NAME_ALRUSED" => [
            "key" => "SECTION_NAME_ALRUSED",
            "status" => "failed",
            "refresh" => false,
            "message" => "Section name already used!"
        ],
        "ROOM_NOEXIST" => [
            "key" => "ROOM_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Room does not exist!",
        ],
        "ROOMS_NOEXIST" => [
            "key" => "ROOMS_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Rooms does not exist!",
        ],
        "STRAND_NOEXIST" => [
            "key" => "STRAND_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Strand does not exist!",
        ],
        "STRAND_NOALLEXIST" => [
            "key" => "STRAND_NOALLEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "No strands."
        ],
        "TEACHER_NOEXIST" => [
            "key" => "TEACHER_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Teacher does not exist!",
        ],
        "USER_TEA_ALRREG" => [
            "key" => "USER_TEA_ALRREG",
            "status" => "failed",
            "refresh" => false,
            "message" => "User teacher is already registered!",
        ],
        "SUCC_ADD_TEA" => [
            "key" => "SUCC_ADD_TEA",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly added and registered new teacher!",
        ],
        "FAILED_ADD_TEA" => [
            "key" => "FAILED_ADD_TEA",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to add and register new teacher!",
        ],
        "SUCC_BLDG_CREATE" => [
            "key" => "SUCC_BLDG_CREATE",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly created building!"
        ],
        "FAILED_BLDG_CREATE" => [
            "key" => "FAILED_BLDG_CREATE",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to create new building!",
        ],
        "NOT_AN_ADMIN" => [
            "key" => "NOT_AN_ADMIN",
            "status" => "failed",
            "refresh" => false,
            "message" => "Not an administrator!",
        ],
        "BLDG_NAME_ALRUSED" => [
            "key" => "BLDG_NAME_ALRUSED",
            "status" => "failed",
            "refresh" => false,
            "message" => "Building name already used!",
        ],
        "BLDG_NOEXIST" => [
            "key" => "BLDG_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Building does not exist!",
        ],
        "NO_BLDG" => [
            "key" => "NO_BLDG",
            "status" => "failed",
            "refresh" => false,
            "message" => "No buildings.",
        ],
        "NO_FILE_FETCHED" => [
            "key" => "NO_FILE_FETCHED",
            "status" => "failed",
            "refresh" => false,
            "message" => "No files fetched.",
        ],
        "NO_FLOORS" => [
            "key" => "NO_FLOORS",
            "status" => "failed",
            "refresh" => false,
            "message" => "No floors.",
        ],
        "SUCC_FILE_UPL" => [
            "key" => "SUCC_FILE_UPL",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly uploaded file.",
        ],
        "FAIL_FILE_UPL" => [
            "key" => "FAIL_FILE_UPL",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to upload file.",
        ],
        "FLOOR_NM_ALREXIST" => [
            "key" => "FLOOR_NM_ALREXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Floor name already exist!",
        ],
        "SUCC_FLOOR_CREATION" => [
            "key" => "SUCC_FLOOR_CREATION",
            "status" => "success",
            "refresh" => true,
            "message" => "Succesfully created floor!"
        ],
        "FAILED_FLOOR_CREATION" => [
            "key" => "FAILED_FLOOR_CREATION",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to create floor!",
        ],
        "SUCC_ROOM_CREATION" => [
            "key" => "SUCC_FLOOR_CREATION",
            "status" => "success",
            "refresh" => true,
            "message" => "Succesfully created floor!"
        ],
        "FAILED_ROOM_CREATION" => [
            "key" => "FAILED_FLOOR_CREATION",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to create floor!",
        ],
        "STU_APPROVED_SUCC" => [
            "key" => "STU_APPROVED",
            "status" => "success",
            "refresh" => true,
            "message" => "Student is approved successfuly!",
        ],
        "STU_APPROVED_FAILED" => [
            "key" => "STU_APPROVED_FAILED",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to approve student enrollment!",
        ],
        "STU_REJECTED_SUCC" => [
            "key" => "STU_REJECTED_SUCC",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly rejected student!"
        ],
        "STU_REJECTED_FAILED" => [
            "key" => "STU_REJECTED_FAILED",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to reject student!"
        ],
        "FILE_PATH_ALREXIST" => [
            "key" => "FILE_PATH_ALREXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "File path already exist!"
        ],
        "STU_ALRINCLASS" => [
            "key" => "STU_ALRINCLASS",
            "status" => "failed",
            "refresh" => false,
            "message" => "Student already in class!"
        ],
        "FAILED_STU_ADCLASS" => [
            "key" => "FAILED_STU_ADCLASS",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to add student to the class!",
        ],
        "SUCC_STU_ADCLASS" => [
            "key" => "SUCC_STU_ADCLASS",
            "status" => "success",
            "refresh" => true,
            "message" => "Succesfully added student to the class!"
        ],
        "NO_STU_NOTINCLASS" => [
            "key" => "NO_STU_NOTINCLASS",
            "status" => "failed",
            "refresh" => false,
            "message" => "No students that are not assigned in class.",
        ],
        "NO_ENR_STUS" => [
            "key" => "NO_ENR_STUS",
            "status" => "failed",
            "refresh" => false,
            "message" => "No enrolled students!"
        ],
        "NO_UNASSG_ROOM_CLASS" => [
            "key" => "NO_UNASSG_ROOM_CLASS",
            "status" => "failed",
            "refresh" => false,
            "message" => "No vancant rooms for classes.",
        ],
        "NO_UNASSG_TEACHER_CLASS" => [
            "key" => "NO_UNASSG_STUDENT_CLASS",
            "status" => "failed",
            "refresh" => false,
            "message" => "No vancant teacher for classes.",
        ],
        "SUCC_SUBJECT_ADD" => [
            "key" => "SUCC_SUBJECT_ADD",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly added subject.",
        ],
        "NO_SUBJ_TYPES" => [
            "key" => "NO_SUBJ_TYPES",
            "status" => "failed",
            "refresh" => false,
            "message" => "No subject types available!",
        ],
        "SUBJ_NOEXISTS" => [
            "key" => "SUBJ_NOEXISTS",
            "status" => "failed",
            "refresh" => false,
            "message" => "No subjects available!",
        ],
        "SUBJ_NOEXIST" => [
            "key" => "SUBJ_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Subject does not exist!",
        ],
        "SUBJ_NAME_ALREADYEXIST" => [
            "key" => "SUBJ_NAME_ALREADYEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Strand name already used!",
        ],
        "SUBJ_ID_ALREADYEXIST" => [
            "key" => "SUBJ_ID_ALREADYEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Strand id already used!",           
        ],
        "FAILED_SUBJ_DEL" => [
            "key" => "FAILED_SUBJ_DEL",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to delete subject!",
            "removeElement" => false,
        ],
        "SUCC_SUBJ_DEL" => [
            "key" => "SUCC_SUBJ_DEL",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly deleted subject!",
            "removeElement" => true,
        ],
        "STU_ENROLLEE_NO_DATA" => [
            "key" => "STU_ENROLLEE_NO_DATA",
            "status" => "failed",
            "refresh" => false,
            "message" => "Student enrollee has no data!",
        ],
        "FILE_NOT_FOUND" => [
            "key" => "FILE_NOT_FOUND",
            "status" => "failed",
            "refresh" => false,
            "message" => "File not found!"
        ],
        "NO_AVAILABLE_TEACHER" => [
            "key" => "NO_AVAILABLE_TEACHER",
            "status" => "failed",
            "refresh" => false,
            "message" => "No availaable teacher."
        ],
        "NO_CLASS_AVAILABLE" => [
            "key" => "NO_CLASS_AVAILABLE",
            "status" => "failed",
            "refresh" => false,
            "message" => "No class available."
        ],
        "NO_CLASS_DATA" => [
            "key" => "NO_CLASS_DATA",
            "status" => "failed",
            "refresh" => false,
            "message" => "No class data found."
        ],
        "CLASS_NOEXIST" => [
            "key" => "CLASS_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Class does not exist."
        ],
        "NO_CLASS_STUDENTS" => [
            "key" => "NO_CLASS_STUDENTS",
            "status" => "failed",
            "refresh" => false,
            "message" => "No students in class."
        ],
        "TEACHERS_NOEXIST" => [
            "key" => "TEACHERS_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "No teachers available!",
        ],
        "NO_AVAILABLE_STUD" => [
            "key" => "NO_AVAILABLE_STUD",
            "status" => "failed",
            "refresh" => false,
            "message" => "No students availabel."
        ],
        "SUCC_STUD_ADDCLASS" => [
            "key" => "SUCC_STUD_ADDCLASS",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly added student to class.",
        ],
        "NO_TEACHER_AVAILABLE" => [
            "key" => "NO_TEACHER_AVAILABLE",
            "status" => "failed",
            "refresh" => false,
            "message" => "No teacher available!",
        ],
        "NO_DAYS_AVAILABLE" => [
            "key" => "NO_DAYS_AVAILABLE",
            "status" => "failed",
            "refresh" => false,
            "message" => "No days available.",
        ],
        "SUCC_ADD_SCHED" => [
            "key" => "SUCC_ADD_SCHED",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly added schedule."
        ],
        "NO_CLASS_SCHED" => [
            "key" => "NO_CLASS_SCHED",
            "status" => "failed",
            "refresh" => false,
            "messagse" => "No class schedules for this class."
        ],
        "PROFESSIONAL_ID_NOEXIST" => [
            "key" => "PROFESSIONAL_ID_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Professional ID does not exist!",
        ],
        "TEACHER_SUBJECTS_NOEXIST" => [
            "key" => "TEACHER_SUBJECTS_NOEXIST",
            "status" => "failed",
            "refresh" => false,
            "message" => "Teacher subjects does not exist!",
        ],
        "TEACHER_NOCLASS" => [
            "key" => "TEACHER_NOCLASS",
            "status" => "failed",
            "refresh" => false,
            "message" => "Teacher class does not exist!"
        ],
        "FAILED_ADD_ASSIG" => [
            "key" => "FAILED_ADD_ASSIG",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed assignment creation!",
        ],
        "FAILED_ADD_ASSIG_ATTC" => [
            "key" => "FAILED_ADD_ASSIG_ATTC",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to upload assignment attachement.",
        ],
        "SUCC_ASSIG_CREATION" => [
            "key" => "SUCC_ASSIG_CREATION",
            "status" => "failed",
            "refresh" => false,
            "message" => "Successfuly created assignment.",
        ],
        "SUCC_ASSIG_SUB" => [
            "key" => "SUCC_ASSIG_SUB",
            "status" => "success",
            "refresh" => true,
            "message" => "Successfuly submitted assignment.",
        ],
        "FAILED_ASSIG_SUB" => [
            "key" => "FAILED_ASSIG_SUB",
            "status" => "failed",
            "refresh" => false,
            "message" => "Failed to submmit assignment.",
        ],
        "NO_COMPLETED_ASSIG" => [
            "key" => "NO_COMPLETED_ASSIG",
            "status" => "failed",
            "refresh" => false,
            "message" => "No submitted assignment.",
        ],
        "NO_NEW_ASSIG_FOUND" => [
            "key" => "NO_NEW_ASSIG_FOUND",
            "status" => "failed",
            "refresh" => false,
            "message" => "No new assignment found!",
        ],
        "NO_PENDING_ASSIG_FOUND" => [
            "key" => "NO_PENDING_ASSIG_FOUND",
            "status" => "failed",
            "refresh" => false,
            "message" => "No pending assignment found!",
        ],
        "NO_TODAY_SUB_ASSIG_FOUND" => [
            "key" => "NO_TODAY_SUB_ASSIG_FOUND",
            "status" => "failed",
            "refresh" => false,
            "message" => "No submitted assignment today is found!",
        ],
        "NO_STUDENT_SUBM" => [
            "key" => "NO_STUDENT_SUBM",
            "status" => "failed",
            "refresh" => false,
            "message" => "No student submissions.",
        ]
    ],
];

Config::setConfig($config);
