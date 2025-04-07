<?php
declare(strict_types=1);

class SuperAdministratorModel extends Database {
    protected $middleware;

    public function __construct(Middleware $middleware)
    {
        $this->middleware = $middleware;
    }

    public function addAccount (array $data, bool $alert = true) {
        $query = "INSERT INTO user_accounts (user_uuid, user_fname, user_lname, 
        user_pwd, user_email, user_gender, user_role) VALUES 
        (:user_uuid, :user_fname, :user_lname, :user_pwd,
        :user_email, :user_gender, :user_role);";

        $exec = $this->setBindedExecution($query, $data);

        if (!$exec && $alert) {
            $this->middleware->alert("FAIL_ADDACC");
        }

        return boolval($exec);
    }

    public function isUserEmailAlreadyUsed (string $email, bool $alert = true) {
        $query = "SELECT user_email FROM user_accounts WHERE user_email = :user_email;";
        $data = ["user_email" => $email];
        $exec = $this->setBindedExecution($query, $data)->fetchAll();
        if (!empty($exec) && $alert) {
            $this->middleware->alert("EMAIL_INUSE");
        }
        return !empty($exec);
    }

    public function addLogRecord (string $uuid, string $type, string $status,  bool $alert = true): bool {
        $query = "INSERT INTO log_records (log_rec_id, 
        log_rec_type, log_rec_status, log_rec_uuid) 
        VALUES (:log_rec_id, :log_rec_type,
        :log_rec_status, :log_rec_uuid);";

        $data = [
            "log_rec_id" => $this->middleware->getUniqueId(15),
            "log_rec_type" => $type,
            "log_rec_status" => $status,
            "log_rec_uuid" => $uuid
        ];

        $exec = $this->setBindedExecution($query, $data);

        if (!$exec && $alert) {
            $this->middleware->alert("LOG_FAILED");
        }

        return $alert;
    }

    public function createNewBuilding (bool $alert = true) {
        $data = [
            "building_id" => $this->middleware->getUniqueId(10),
            "building_name" => $_POST["building-name"],
            "building_description" => $_POST["building-descrition"]
        ];
        $this->middleware->isAnyColumnEmpty($data);
        $query = "INSERT INTO registered_teachers (
        teacher_professional_id,
        teacher_account_id,
        teacher_registration_status,
        teacher_fname,
        teacher_lname,
        teacher_mname,
        teacher_age,
        teacher_gender,
        teacher_bday
        ) VALUES (
        :teacher_professional_id,
        :teacher_account_id,
        :teacher_registration_status,
        :teacher_fname,
        :teacher_lname,
        :teacher_mname,
        :teacher_age,
        :teacher_gender,
        :teacher_bday);";
        $exec = $this->setBindedExecution($query, $data);
        if ($alert) {
            $exec ? $this->middleware->alert("SUCC_ADD_TEA")
            : $this->middleware->alert("FAILED_ADD_TEA");
        }
    }

    public function getAllPendingStudentRegistration (bool $alert = true): array {
        $query = "SELECT 
        rs.student_learning_ref_number as LRN,
        rs.student_fname as Firstname,
        rs.student_lname as Lastname,
        rs.student_mname as Middlename,
        rs.student_age as Age,
        rs.student_gender as GenderId,
        g.gender_name as Gender,
        rs.student_bday as Birthdate,
        rs.student_registration_status as Stat,
        uf_birth_cert.file_path AS birth_certificate_path,
        uf_enrollment.file_path AS enrollment_form_path,
        uf_form_127.file_path AS form_127_path,
        uf_good_moral.file_path AS good_moral_cert_path,
        uf_report_card.file_path AS report_card_path,
        uf_photo.file_path AS student_photo_path
        FROM registered_students rs
        JOIN genders g ON g.gender_id = rs.student_gender
        LEFT JOIN uploaded_files uf_birth_cert ON rs.student_birth_certificate = uf_birth_cert.file_id
        LEFT JOIN uploaded_files uf_enrollment ON rs.student_enrollment_form = uf_enrollment.file_id
        LEFT JOIN uploaded_files uf_form_127 ON rs.student_form_127 = uf_form_127.file_id
        LEFT JOIN uploaded_files uf_good_moral ON rs.student_good_moral_cerf = uf_good_moral.file_id
        LEFT JOIN uploaded_files uf_report_card ON rs.student_report_card = uf_report_card.file_id
        LEFT JOIN uploaded_files uf_photo ON rs.student_photo = uf_photo.file_id 
        WHERE rs.student_registration_status = 'PND';";

        $exec = $this->setBindedExecution($query)->fetchAll();

        return $exec;
    }

    public function getAllEnrolledStudent (): array {
        $query = "SELECT 
        rs.student_learning_ref_number as LRN,
        rs.student_fname as Firstname,
        rs.student_lname as Lastname,
        rs.student_mname as Middlename,
        rs.student_age as Age,
        rs.student_gender as GenderId,
        g.gender_name as Gender,
        rs.student_bday as Birthdate,
        rs.student_registration_status as Stat,
        uf_birth_cert.file_path AS birth_certificate_path,
        uf_enrollment.file_path AS enrollment_form_path,
        uf_form_127.file_path AS form_127_path,
        uf_good_moral.file_path AS good_moral_cert_path,
        uf_report_card.file_path AS report_card_path,
        uf_photo.file_path AS student_photo_path
        FROM registered_students rs
        JOIN genders g ON g.gender_id = rs.student_gender
        LEFT JOIN uploaded_files uf_birth_cert ON rs.student_birth_certificate = uf_birth_cert.file_id
        LEFT JOIN uploaded_files uf_enrollment ON rs.student_enrollment_form = uf_enrollment.file_id
        LEFT JOIN uploaded_files uf_form_127 ON rs.student_form_127 = uf_form_127.file_id
        LEFT JOIN uploaded_files uf_good_moral ON rs.student_good_moral_cerf = uf_good_moral.file_id
        LEFT JOIN uploaded_files uf_report_card ON rs.student_report_card = uf_report_card.file_id
        LEFT JOIN uploaded_files uf_photo ON rs.student_photo = uf_photo.file_id 
        WHERE rs.student_registration_status = 'ENR';";
        $exec = $this->setBindedExecution($query)->fetchAll();
        return $exec;
    }

    public function getAllStudentsNotInClass () {
        $query = "SELECT
        rs.student_learning_ref_number as LRN,
        rs.student_fname as Firstname,
        rs.student_lname as Lastname,
        rs.student_mname as Middlename,
        rs.student_age as Age,
        rs.student_gender as GenderId,
        rs.student_bday as Birthdate,
        rs.student_registration_status as Stat,
        s.strand_name as Strand,
        s.strand_description as StrandDesc,
        g.gender_name as Gender
        FROM registered_students rs
        LEFT JOIN genders g ON rs.student_gender = g.gender_id
        LEFT JOIN class_students cs ON rs.student_learning_ref_number = cs.class_student_lrn
        JOIN strand s ON s.strand_id = rs.student_requested_strand
        WHERE rs.student_registration_status = 'ENR' && cs.class_student_lrn IS NULL;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        return $exec;
    } 

    public function approveStudentEnrollment (string $lrn, bool $alert = true) {
        $query = "UPDATE registered_students
        SET student_registration_status = 'ENR' 
        WHERE student_learning_ref_number = :lrn";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn]);
        if ($alert) {
            $exec ? $this->middleware->alert("STU_APPROVED_SUCC")
            : $this->middleware->alert("STU_APPROVE_FAILED");
        }
    }

    public function rejectStudentEnrollment (string $lrn, bool $alert = true) {
        $query = "UPDATE registered_students
        SET student_registration_status = 'REJ' 
        WHERE student_learning_ref_number = :lrn";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn]);
        if ($alert) {
            $exec ? $this->middleware->alert("STU_APPROVED_SUCC")
            : $this->middleware->alert("STU_APPROVED_FAILED");
        }
    }

    public function isUserAlreadyInClass (string $lrn, string $classID, bool $alert = true) { 
        $query = "SELECT  * FROM class_students
        WHERE class_student_lrn = :lrn;";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn])->fetchAll();
        if (!empty($exec) && $alert) {
            $this->middleware->alert("STU_ALRINCLASS");
        }
        return !empty($exec);
    }

    public function addStudentToClass (array $data, bool $alert = true) {
        $query = "INSERT INTO class_students (
        class_student_lrn,
        class_student_id,
        class_id) VALUES (
        :class_student_lrn,
        :class_student_id,
        :class_id);";
        $exec = $this->setBindedExecution($query, $data);
        if ($alert) {
            $exec ? $this->middleware->alert("SUCC_STU_ADCLASS")
            : $this->middleware->alert("FAILED_STU_ADCLASS");
        }
    }

    public function getAllGlobalFiles () {
        $query = "SELECT
        up.file_name as fileNames,
        up.file_path as FilePaths,
        ft.file_type_name as FileTypes,
        ft.file_
        ;";
        $exec = $this->setBindedExecution($query)->fetchAll();
    }

    public function findEnrolledStudent(string $searchQuery): array {
        $query = "SELECT 
            rs.student_learning_ref_number as LRN,
            rs.student_fname as Firstname,
            rs.student_lname as Lastname,
            rs.student_mname as Middlename,
            rs.student_age as Age,
            rs.student_gender as GenderId,
            g.gender_name as Gender,
            rs.student_bday as Birthdate,
            rs.student_registration_status as Stat,
            uf_photo.file_path AS student_photo_path
        FROM registered_students rs
        JOIN genders g ON g.gender_id = rs.student_gender
        LEFT JOIN uploaded_files uf_photo ON rs.student_photo = uf_photo.file_id
        WHERE rs.student_registration_status = 'ENR'
        AND (
            rs.student_learning_ref_number LIKE :searchQuery 
            OR rs.student_fname LIKE :searchQuery 
            OR rs.student_lname LIKE :searchQuery 
            OR rs.student_mname LIKE :searchQuery
        )";

        $searchQuery = "%{$searchQuery}%";
        return $this->setBindedExecution($query, ['searchQuery' => $searchQuery])->fetchAll();
    }

    public function addSubject (array $data, bool $alert = true){
        $query = "INSERT INTO subjects (
        subject_id, 
        subject_type, 
        subject_name, 
        subject_description, 
        subject_strand) VALUES (
        :subject_id, 
        :subject_type, 
        :subject_name, 
        :subject_description, 
        :subject_strand);";

        $exec = $this->setBindedExecution($query, $data);
        if ($alert) {
            $exec ? $this->middleware->alert("SUCC_SUBJECT_ADD")
            : $this->middleware->alert("FAILED_SUBJECT_ADD");
        }
    }

    public function isSubjectNameAlreadyUsed(string $subject, bool $alert = true): bool {
        $query = "SELECT * FROM subjects WHERE subject_name = :subject_name;";
        $exec = $this->setBindedExecution($query, ["subject_name" => $subject])->fetchAll();
        if (!empty($exec) && $alert) {
            $this->middleware->alert("SUBJ_NAME_ALREADYEXIST");
        }
        return empty($exec);
    }

    public function getAllSubjects (bool $alert = true): array  {
        $query = "SELECT 
        sbj.subject_id as SubjectId,
        sbj.subject_name as SubjectName,
        sbj.subject_description as SubjectDesc,
        sbjt.subject_type_name as SubjectType,
        sbjt.subject_type_desc as SubjectTypeDesc,
        s.strand_name as SubjectStrand
        FROM subjects sbj
        JOIN subject_types sbjt ON sbjt.subject_type_id = sbj.subject_type
        JOIN strand s ON s.strand_id = sbj.subject_strand;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("SUBJ_NOEXISTS");
        }
        return $exec;
    }

    public function getAllUnassignedTeacherClass () {
        $query = "SELECT rt.*
        FROM registered_teachers rt
        LEFT JOIN class c ON rt.teacher_professional_id = c.class_adviser
        WHERE c.class_adviser IS NULL;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        return $exec;
    }

    public function getAllUnassignedRoomClass (): array {
        $query = "select r.*
        from room r
        left join class c on r.room_id = c.class_room
        where c.class_room is null";
        $exec = $this->setbindedexecution($query)->fetchall();
        return $exec;
    }

    public function getAllClass () {
        $query = "SELECT
        c.class_id as ClassId,
        c.class_section_name as Section,
        c.class_student_limit as StudentLimit,
        c.class_grade_level as GradeLevel,
        r.room_id as Room,
        b.building_name as Building,
        f.floor_name as Floor,
        rt.teacher_professional_id as AdviserId,
        rt.teacher_fname as AdviserFname,
        rt.teacher_lname as AdviserLname,
        s.strand_name as Strand,
        s.strand_description as StrandDescription
        FROM class c
        JOIN strand s ON s.strand_id = c.class_strand
        JOIN room r ON r.room_id = c.class_room
        JOIN floor f ON f.floor_id = r.room_floor
        JOIN building b ON b.building_id = f.floor_building
        JOIN registered_teachers rt ON rt.teacher_professional_id = c.class_adviser;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        return $exec;
    }

    public function getAllUsers() {
        $query = "SELECT 
        ua.user_fname as FirstName,
        ua.user_lname as LastName, 
        ua.user_email as EmailAddress,
        ua.user_created_on as CreatedOn,
        g.gender_name as Gender,
        g.gender_id as GenderId,
        ur.role_id as RoleId,
        ur.role_name as RoleName,
        ur.role_description as RoleDescription
        FROM user_accounts ua
        JOIN user_roles ur ON ur.role_id = ua.user_role
        JOIN genders g ON g.gender_id = ua.user_gender;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        return $exec;
    }

    public function isSubjectExist (string $id, bool $alert = true) {
        $query = "SELECT * FROM subjects WHERE subject_id = :id;";
        $exec = $this->setBindedExecution($query, ["id" => $id])->fetchAll();
        if (empty($exec) &&$alert) {
            $this->middleware->alert("SUBJ_NOEXIST");
        }
    }

    public function dropSubject (array $data, bool $alert = true) {
        $query = "DELETE FROM subjects WHERE subject_id = :subject_id;";
        $exec = $this->setBindedExecution($query, $data);
        if ($alert) {
            $exec ? $this->middleware->alert("SUCC_SUBJ_DEL")
            : $this->middleware->alert("FAILED_SUBJ_DEL");
        }
    }

    public function isStudentAnEnrollee (string $lrn, bool $alert = true): bool {
        $query = "SELECT * FROM registered_students 
        WHERE student_registration_status = 'PND' AND student_learning_ref_number = :lrn;";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("STU_ALREADY_ENROLLED");
        }
        return !empty($exec);
    }

    public function getAllStudentEnrolleeData (string $lrn, bool $alert = true): array {
        $query = "SELECT 
        rs.student_learning_ref_number as LRN,
        rs.student_account_id as AccountId,
        rs.student_fname as Firstname,
        rs.student_lname as Lastname,
        rs.student_mname as Middlename,
        rs.student_age as Age,
        g.gender_name as Gender,
        rs.student_bday as Birthdate,
        rs.student_registered_on as RegisteredOn,
        uf_birth_cert.file_stored_name as BirthCert,
        uf_enrollment.file_stored_name as EnrollmentForm,
        uf_form_127.file_stored_name as File137,
        uf_good_moral.file_stored_name as GoodMoralCert,
        uf_report_card.file_stored_name as StudentReportCard,
        uf_photo.file_stored_name as StudentPhoto
        FROM registered_students rs
        JOIN genders g ON g.gender_id = rs.student_gender
        LEFT JOIN uploaded_files uf_birth_cert ON rs.student_birth_certificate = uf_birth_cert.file_id
        LEFT JOIN uploaded_files uf_enrollment ON rs.student_enrollment_form = uf_enrollment.file_id
        LEFT JOIN uploaded_files uf_form_127 ON rs.student_form_127 = uf_form_127.file_id
        LEFT JOIN uploaded_files uf_good_moral ON rs.student_good_moral_cerf = uf_good_moral.file_id
        LEFT JOIN uploaded_files uf_report_card ON rs.student_report_card = uf_report_card.file_id
        LEFT JOIN uploaded_files uf_photo ON rs.student_photo = uf_photo.file_id 
        WHERE rs.student_learning_ref_number = :lrn;";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("STU_ENROLLEE_NO_DATA");
        }
        return $exec;
    }

    public function isClassExist (string $classId, bool $alert = true): bool {
        $query = "SELECT * FROM class WHERE class_id = :id;";
        $exec = $this->setBindedExecution($query, ["id" => $classId])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("CLASS_NOEXIST");
        }
        return !empty($exec);
    }

    public function viewAllClassData (string $classId, bool $alert = true): array {
        $query = "SELECT 
        c.class_id as ClassId,
        c.class_student_limit as ClassStudentLimit,
        c.class_grade_level as GradeLevel,
        c.class_section_name as SectionName,
        rt.teacher_professional_id as AdviserProfessionalId,
        rt.teacher_fname as AdviserFname,
        rt.teacher_lname as AdviserLname,
        rt.teacher_mname as AdviserMname,
        s.strand_name as StrandName,
        s.strand_id as StrandId,
        r.room_id as RoomId,
        r.room_description as RoomDesc,
        g.gender_name as AdviserGender
        FROM class c
        LEFT JOIN registered_teachers rt ON rt.teacher_professional_id = c.class_adviser
        LEFT JOIN genders g ON rt.teacher_gender = g.gender_id
        LEFT JOIN strand s ON c.class_strand = s.strand_id
        LEFT JOIN room r ON r.room_id = c.class_room WHERE c.class_id = :class_id;";
        $exec = $this->setBindedExecution($query, ["class_id" => $classId])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_CLASS_DATA");
        }
        return $exec;
    }

    public function viewAllClassStudents (string $classId, bool $alert = true): array {
        $query = "SELECT 
        cs.class_student_id as StudentId,
        cs.class_student_lrn as StudentLRN,
        cs.class_id as ClassId
        FROM class_students cs
        JOIN registered_students rs ON rs.student_learning_ref_number = cs.class_student_lrn
        JOIN genders g ON g.gender_id = rs.student_gender
        WHERE cs.class_id = :id;";
        $exec = $this->setBindedExecution($query, ["id" => $classId])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_CLASS_STUDENTS");
        }
        return $exec;
    }

    public function getAllTeachers (bool $alert = true): array {
        $query = "SELECT * FROM registered_teachers WHERE teacher_registration_status = 'REG';";
        $exec = $this->setBindedExecution($query)->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_TEACHER_AVAILABLE");
        }
        return $exec;
    }

    public function checkForScheduleConflict(string $classId, array $days, string $start, string $end, bool $alert = true) {
        $conflicts = [];
        foreach ($days as $dayId) {
            $query = "
                SELECT cs.class_id, d.day_name, cs.start_time, cs.end_time
                FROM class_schedules cs
                JOIN days d ON cs.day_of_week = d.day_id
                WHERE cs.day_of_week = :day_id
                AND cs.class_id = :class_id
                AND (
                        (cs.start_time < :end_time AND cs.end_time > :start_time) OR
                        (cs.start_time >= :start_time AND cs.start_time < :end_time)
                    )
            ";

            $results = $this->setBindedExecution($query, [
                'day_id' => $dayId,
                'class_id' => $classId,
                'start_time' => $start,
                'end_time' => $end
            ])->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $conflicts[] = "⚠️ Class conflict on {$row['day_name']} between {$row['start_time']} and {$row['end_time']}";
            }
        }

        if ($alert && !empty($conflicts)) {
            die(json_encode(['status' => 'conflict', 'messages' => $conflicts]));
        }

        return empty($conflicts);
    }

    public function checkForTeacherSchedConflict(array $days, string $teacherId, string $start, string $end, bool $alert = true) {
    $conflicts = [];

    foreach ($days as $dayId) {
            $query = "
            SELECT cs.teacher_id, d.day_name, cs.start_time, cs.end_time
            FROM class_schedules cs
            JOIN days d ON cs.day_of_week = d.day_id
            WHERE cs.day_of_week = :day_id
            AND cs.teacher_id = :teacher_id
            AND (
                (cs.start_time < :end_time AND cs.end_time > :start_time) OR
                (cs.start_time >= :start_time AND cs.start_time < :end_time)
            )";

            $results = $this->setBindedExecution($query, [
                'day_id' => $dayId,
                'teacher_id' => $teacherId,
                'start_time' => $start,
                'end_time' => $end
            ])->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $conflicts[] = "Teacher has a schedule conflict on {$row['day_name']} between {$row['start_time']} and {$row['end_time']}";
            }
        }

        if ($alert && !empty($conflicts)) {
            die(json_encode(['status' => 'conflict', 'messages' => $conflicts]));
        }

        return empty($conflicts);
    }

    public function addSchedule (array $data, bool $alert = true) {

        foreach ($data["day_of_week"] as $day) {
             $query = "INSERT INTO class_schedules (schedule_id, class_id, teacher_id, subject_id, day_of_week, start_time, end_time)
            VALUES (:schedule_id, :class_id, :teacher_id, :subject_id, :day_of_week, :start_time, :end_time)";

            $this->setBindedExecution($query, [
                "schedule_id" => $this->middleware->getUniqueId(10),
                'class_id'    => $data["class_id"],
                'teacher_id'  => $data["teacher_id"],
                'subject_id'  => $data["subject_id"],
                'day_of_week' => $day,
                'start_time'  => $data["start_time"],
                'end_time'    => $data["end_time"],
            ]);
        }
        
        $this->middleware->alert("SUCC_ADD_SCHED");
    }

    public function getAllClassSchedule (string $classId, bool $alert = true): array {
        $query = "SELECT 
        s.subject_id,
        s.subject_name,
        rt.teacher_fname as Firstname,
        rt.teacher_lname as Lastname,
        rt.teacher_mname as Middlename,
        CONCAT(DATE_FORMAT(cs.start_time, '%h:%i %p'), ' - ', DATE_FORMAT(cs.end_time, '%h:%i %p')) AS time_range,
        GROUP_CONCAT(d.day_name ORDER BY 
            CASE d.day_name
                WHEN 'Sunday' THEN 1
                WHEN 'Monday' THEN 2
                WHEN 'Tuesday' THEN 3
                WHEN 'Wednesday' THEN 4
                WHEN 'Thursday' THEN 5
                WHEN 'Friday' THEN 6
                WHEN 'Saturday' THEN 7
            END) AS days
        FROM class_schedules cs
        JOIN registered_teachers rt ON rt.teacher_professional_id = cs.teacher_id
        JOIN subjects s ON cs.subject_id = s.subject_id
        JOIN days d ON cs.day_of_week = d.day_id
        WHERE cs.class_id = :class_id 
        GROUP BY s.subject_id, s.subject_name, cs.start_time, cs.end_time
        ORDER BY s.subject_name;";

        $exec = $this->setBindedExecution($query, ["class_id" => $classId])->fetchAll();

        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_CLASS_SCHED");
        }

        foreach ($exec as &$schedule) {
            $schedule['days'] = explode(',', $schedule['days']);
        }

        return $exec;

    }
}