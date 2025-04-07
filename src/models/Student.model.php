<?php

declare(strict_types=1);

class StudentModel extends Database {
    protected $middleware;

    public function __construct($middleware) {
        $this->middleware = $middleware;
    }

    public function findRegisteredStudent (string $user_uniqueid, 
    ?bool $sendErrMessage = true): bool {
        $query = "SELECT * FROM registered_students 
        WHERE student_account_id  = :user_uniqueid;";
        $data = ["user_uniqueid" => $user_uniqueid];
        $fetch = $this->setBindedExecution($query, $data)->fetchAll();
        if ($sendErrMessage && empty($fetch)) {
            $this->middleware->alert("failed_reg_student_search");
        }
        return !empty($fetch);
    } 

    public function getRegisteredStudent (string $user_uniqueid, 
    ?bool $sendErrMessage = true): array {
        $query = "SELECT * FROM registered_students 
        WHERE student_account_id = :user_uniqueid;";
        $fetch = $this->setBindedExecution($query, ["user_uniqueid" => $user_uniqueid])->fetchAll();
        if ($sendErrMessage && empty($fetch)) {
            $this->middleware->alert("no_fetched_userdata");
        }
        return $fetch;
    }

    public function getAllMyAssignments (string $classId, string $lrn) {
        $query = "SELECT 
        assig.assignment_id AS AssigId,
        assig.assignment_title AS AssigTitle,
        assig.assignment_description AS AssigDesc,
        assig.assignment_due_date AS AssigDueDate,
        assig.assignment_created_on AS AssigCreatedDate,
        assig.assignment_created_by AS TeacherProfessionalID,
        c.class_section_name AS SectionName,
        subj.subject_name AS SubjectName,
        up.file_stored_name AS StoredName
        FROM assignments assig
        JOIN class c ON c.class_id = assig.class_id
        JOIN subjects subj ON subj.subject_id = assig.subject_id
        LEFT JOIN assignment_attachements assigAttach ON assigAttach.assignment_id = assig.assignment_id
        LEFT JOIN uploaded_files up ON up.file_id = assigAttach.assignment_file_id
        LEFT JOIN class_students cs ON cs.class_id = c.class_id
        LEFT JOIN student_submissions ss 
        ON ss.assignment_id = assig.assignment_id 
        AND ss.student_lrn = cs.class_student_id
        WHERE assig.class_id = :class_id 
        AND cs.class_student_lrn = :lrn
        AND ss.student_lrn IS NULL;";
        $exec = $this->setBindedExecution($query, ["class_id" => $classId, "lrn" => $lrn])->fetchAll();
        return $exec;
    }

    public function countCompletedAssignments (string $lrn, bool $alert = true) {
        $query = "SELECT submission_id, student_lrn FROM student_submissions WHERE student_lrn = :lrn;";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_COMPLETED_ASSIG");
        }
        return $exec;
    }

    public function countNewAssignments (string $classId, bool $alert = true) {
        $query = "SELECT * FROM assignments assig
        LEFT JOIN student_submissions ss ON assig.assignment_id = ss.assignment_id
        WHERE assig.class_id = :class_id AND assignment_created_on >= NOW() - INTERVAL 7 DAY AND ss.assignment_id IS NULL;";
        $fetch = $this->setBindedExecution($query, ["class_id" => $classId])->fetchAll();
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_NEW_ASSIG_FOUND");
        }
        return $fetch;
    }

    public function countPendingAssignments (string $classId, bool $alert = true) {
        $query = "SELECT * FROM assignments assig
        LEFT JOIN student_submissions ss ON assig.assignment_id = ss.assignment_id
        WHERE assig.class_id = :class_id AND ss.assignment_id IS NULL;";
        $fetch = $this->setBindedExecution($query, ["class_id" => $classId])->fetchAll();
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_PENDING_ASSIG_FOUND");
        }
        return $fetch;
    }

    public function countSubmittedAssignmentsToday (string $classId, bool $alert = true) {
        $query = "SELECT * FROM assignments assig
        LEFT JOIN student_submissions ss ON assig.assignment_id = ss.assignment_id
        WHERE assig.class_id = :class_id AND ss.submission_date <= NOW() + INTERVAL 1 DAY AND ss.assignment_id IS NOT NULL;";
        $fetch = $this->setBindedExecution($query, ["class_id" => $classId])->fetchAll();
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_TODAY_SUB_ASSIG_FOUND");
        }
        return $fetch;
    }

    // USED
    public function analyzeDuplicateEnrollment (string $lrn, string $uuid, 
    bool $alert = true): bool {
        $query = "SELECT * FROM registered_students 
        WHERE student_learning_ref_number = :lrn 
        AND student_account_id = :uuid;";
        $data = ["lrn" => $lrn, "uuid" => $uuid];
        $exec = $this->setBindedExecution($query, $data)->fetchAll();
        if (!empty($exec) && $alert) {
            $this->middleware->alert("STU_LRN_USED");
        }
        return false;
    }

    public function registerNewStudent (array $data, ?bool $sendErrMessage = true): void {
        $query = "INSERT INTO registered_students (
            student_learning_ref_number, 
            student_registration_status, 
            student_fname, 
            student_lname, 
            student_mname,
            student_age, 
            student_gender, 
            student_requested_strand,
            student_bday, 
            student_account_id,
            student_birth_certificate,
            student_enrollment_form,
            student_form_127,
            student_good_moral_cerf,
            student_report_card,
            student_photo
        ) VALUES (
            :student_learning_ref_number, 
            :student_registration_status, 
            :student_fname, 
            :student_lname, 
            :student_mname,
            :student_age, 
            :student_gender, 
            :student_requested_strand,
            :student_bday, 
            :student_account_id,
            :student_birth_certificate,
            :student_enrollment_form,
            :student_form_127,
            :student_good_moral_cerf,
            :student_report_card,
            :student_photo
            );";
        $exec = $this->setBindedExecution($query, $data);
        if (!$exec && $sendErrMessage) {
            $this->middleware->alert("STU_REG_FAILED");
        }

        $exec ? $this->middleware->alert("STU_REG_SUCC") 
        : $this->middleware->alert("STU_REG_FAILED");
    }

    public function getMyLrn (string $userid, bool $sendErrMessage = true): string {
        $query = "SELECT student_learning_ref_number 
        FROM registered_students 
        WHERE student_account_id = :userid;";
        $exec = $this->setBindedExecution($query, ["userid" => $userid])->fetchAll();

        if (empty($exec) && $sendErrMessage) {
            $this->middleware->alert("student_not_fully_reg");
        }

        return $exec[0]["student_learning_ref_number"];
    }

    public function addNewSubmittedAssignment (string $assigId, string $lrn, string $assigFileId, string $note, string $assigStatus = "pending", bool $alertMsg = true): bool {
        $query = "INSERT INTO
        student_submissions (
        submission_id,
        assignment_id,
        student_lrn,
        submission_file_id,
        submission_status,
        submission_text 
        ) VALUES (
        :submission_id,
        :assignment_id,
        :student_lrn,
        :submission_file_id,
        :submission_status,
        :submission_text
        )";

        $exec = $this->setBindedExecution($query, [
            "submission_id" => $this->middleware->getUniqueId(10),
            "assignment_id" => $assigId,
            "submission_file_id" => $assigFileId,
            "submission_status" => $assigStatus,
            "student_lrn" => $lrn,
            "submission_text" => $note
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("SUCC_ASSIG_SUB") 
            : $this->middleware->alert("FAILED_ASSIG_SUB");
        }

        return !empty($exec);
    }

    public function getAllSubjects (bool $alertMsg = true): array {
        $query = "SELECT * FROM class_subjects;";
        $exec = $this->setBindedExecution($query)->fetchAll();

        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("class_subject_noexist");
        }

        return $exec;
    }

    public function getAllClass (): array {
        $query = "SELECT * FROM class;";
        return $this->setBindedExecution($query)->fetchAll();
    }

    // public function getAllMyAssignments (string $classId, bool $alertMsg = true): array {
    //     $query = "SELECT
    //     ass.assignment_id as AssignmentId,
    //     ass.assignment_title as AssignmentTitle,
    //     ass.assignment_deadline as Deadline,
    //     ass.assignment_uploaded_on as CreatedOn,
    //     ass.assignment_note_desc as Note,
    //     subt.subtopic_name as Subtopic,
    //     apl.assignment_priority_name as Priority,
    //     rt.teacher_fname as TeacherFirstname,
    //     rt.teacher_lname as TeacherLastname,
    //     subj.class_subject_name as ClassSubject,
    //     subj.class_subject_description as ClassSubjectDesc,
    //     cls.class_id as classId
    //     FROM assignments ass
    //     JOIN class_subject_subtopic subt ON subt.subtopic_id = ass.assignment_subtopic_id
    //     JOIN assignment_priority_level apl ON apl.assignment_priority_id = ass.assignment_priority
    //     JOIN registered_teachers rt ON rt.teacher_professional_id = ass.assignment_created_by
    //     JOIN class_subjects subj ON subj.class_subject_id = ass.assignment_subject_id
    //     JOIN class cls ON cls.class_id = ass.class_id
    //     WHERE cls.class_id = :class_id;
    //     ";
    //     $exec = $this->setBindedExecution($query, ["class_id" => $classId])->fetchAll();

    //     if (empty($exec) && $alertMsg) {
    //         $this->middleware->alert("student_no_assignments");
    //     }

    //     return $exec;
    // }

    public function getMyClassId (string $lrn, bool $alertMsg = true): string {
        $query = "SELECT class_id FROM class_students WHERE class_student_lrn = :lrn;";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn])->fetchAll();
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("student_is_not_inclass");
        }
        return $exec[0]["class_id"];
    }

    public function getMyClassSectionName (string $classId, bool $alert = true) {
        $query = "SELECT class_section_name FROM class WHERE class_id = :class_id;";
        $exec = $this->setBindedExecution($query, ["class_id" => $classId])->fetchAll();
        if (empty($exec) && $alert) {

        }
    }

    public function isStudentAlreadySubmitted (string $lrn, string $assigId, bool $alertMsg = true): bool {
        $query = "SELECT * FROM submitted_assignments WHERE assignment_id = :assig_id AND student_lrn = :lrn;";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn, "assig_id" => $assigId])->fetchAll();

        if (!empty($exec) && $alertMsg) {
            $this->middleware->alert("student_already_sbmt_assig");
        }

        return !empty($exec);
    }

    public function getAllMyTask(string $classId) {


        

        // $myclasId = $this->getMyClassId($lrn);
        // $q2 = "SELECT task_id FROM task_student_compliance WHERE class_id = :class_id;";
        // $e2 = $this->setBindedExecution($q2, ["class_id" => $myclasId])->fetchAll();
        // $fileteredE2 = [];
        // foreach ($e2 as $e) {
        //     $fileteredE2[] = $e["task_id"];
        // }

        // $merged = array_diff($fileteredE1, $fileteredE2);
        // return ["data1" => $fileteredE1, "data2" => $fileteredE2];

        // $data = [];

        // foreach ($merged as $m) {
        $query = "SELECT 
        tk.task_name as Title, 
        tk.task_id as TaskId,
        tk.task_priority as PriorityId,  
        tp.task_priority_name as PriorityName,
        tk.task_deadline_on as Deadline,  
        rt.teacher_fname as Firstname,
        rt.teacher_lname as Lastname,
        tk.task_created_on as CreatedOn,  
        tk.task_notes as Notes
        FROM task_student_compliance tk
        JOIN registered_teachers rt ON rt.teacher_professional_id = tk.task_teacher_profid
        JOIN task_priority tp ON tp.task_priority_id = tk.task_priority
        JOIN task_complied_students tcs ON tcs.task_id 
        WHERE tk.class_id = :class_id;";
        $tasks = $this->setBindedExecution($query, ["class_id" => $classId])->fetchAll();
            // $data[] = $tasks;
        // }

        // $lrn = $this->getMyLrn($_SESSION["current_session"]["current_user_uuid"]);
        // $q1 = "SELECT task_id FROM task_complied_students WHERE student_lrn = :lrn;";
        // $e1 = $this->setBindedExecution($q1, ["lrn" => $lrn])->fetchAll();
        // $fileteredE1 = [];
        // foreach ($e1 as $e) {
        //     $fileteredE1[] = $e["task_id"];
        // }



        // $filter = array_filter($tasks, function ($tsk) {
            // return !in_array($tsk["TaskId"], $fileteredE1) ? $tsk : null;
        // });

        return $tasks;
    }

    public function comply (string $taskId, string $lrn) {
        $query = "INSERT INTO task_complied_students (
        student_lrn,
        task_id,
        comment
        ) VALUES (
        :student_lrn,
        :task_id,
        :comment
        );";

        $this->setBindedExecution($query, [
            "task_compliance_id" => $this->middleware->getUniqueId(),
            "student_lrn" => $lrn,
            "task_id" => $taskId,
            "comment" => "",
        ]);
        
        die(json_encode([
            "status" => "success",
            "message" => "Successfuly complied students",
            "refres" => true,
        ]));
    }
}