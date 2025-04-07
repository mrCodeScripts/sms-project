<?php 

declare(strict_types=1);

class SubjectTeacherModel extends Database {
    protected $middleware;
    
    public function __construct(Middleware $middleware)
    {
        $this->middleware = $middleware; 
    }

    public function getAllAssignments (string $profId, bool $alert = true) {
        $query = "SELECT 
        assig.assignment_id AS AssigId,
        assig.assignment_title AS AssigTitle,
        assig.assignment_description AS AssigDesc,
        assig.assignment_due_date AS AssigDueDate,
        assig.assignment_created_on AS AssigCreatedDate,
        assig.assignment_created_by AS TeacherProfessionalID,
        c.class_section_name AS SectionName,
        up.file_stored_name AS StoredName,
        subj.subject_name AS SubjectName
        FROM assignments assig
        JOIN class c ON c.class_id = assig.class_id
        JOIN subjects subj ON subj.subject_id = assig.subject_id
        LEFT JOIN assignment_attachements assigAttach ON assigAttach.assignment_id = assig.assignment_id
        LEFT JOIN uploaded_files up ON up.file_id = assigAttach.assignment_file_id
        WHERE assig.assignment_created_by = :prof_id;";
        $fetch = $this->setBindedExecution($query, ["prof_id" => $profId])->fetchAll();
        return $fetch;
    }

    public function getAllAssignmentStudentSubmission (string $assigId) {
        $query = "SELECT
        ss.submission_date as SubmittedOn,
        ss.submission_text as SubmissionNote,
        uf.file_stored_name as FileStoredName,
        ss.submission_status as SubmissionStatus,
        rs.student_fname as StudentFirstname,
        rs.student_lname as StudentLastname,
        rs.student_mname as StudentMiddlename,
        rs.student_learning_ref_number as LRN,
        c.class_section_name as SectionName
        FROM student_submissions ss
        JOIN assignments assig ON ss.assignment_id = assig.assignment_id
        JOIN registered_students rs ON rs.student_learning_ref_number = ss.student_lrn
        JOIN class c ON assig.class_id = c.class_id
        JOIN uploaded_files uf ON uf.file_id = ss.submission_file_id
        WHERE ss.assignment_id = :assig_id;";
        $exec = $this->setBindedExecution($query, ["assig_id" => $assigId])->fetchAll();
        return $exec;
    }

    public function getAllClass (string $id, bool $alert = true) {
        $query = "SELECT DISTINCT
        c.class_id,
        c.class_section_name
        FROM class_schedules cs
        JOIN class c ON cs.class_id = c.class_id
        WHERE cs.teacher_id = :teacher_id
        ORDER BY c.class_section_name;";
        $exec = $this->setBindedExecution($query, ["teacher_id" => $id])->fetchAll();
        if ($alert && empty($exec)) {
            $this->middleware->alert("TEACHER_NOCLASS");
        }
        return $exec;
    }

    public function getAllSubjects (string $teacherId, bool $alert = true) {
        $query = "SELECT DISTINCT
        cs.class_id,
        cs.subject_id as SubjectId,
        s.subject_name as SubjectName,
        c.class_section_name AS section_name
        FROM class_schedules cs
        JOIN registered_teachers rt ON cs.teacher_id = rt.teacher_professional_id
        JOIN class c ON cs.class_id = c.class_id
        JOIN subjects s ON s.subject_id = cs.subject_id
        WHERE rt.teacher_professional_id = :teacher_id
        ORDER BY cs.class_id;";
        $exec = $this->setBindedExecution($query, ["teacher_id" => $teacherId])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("TEACHER_SUBJECTS_NOEXIST");
        }
        return $exec;
    }

    public function getClassSubjects (string $teacherId, string $classId, bool $alert = true) {
        $query = "SELECT DISTINCT
        cs.subject_id as SubjectId,
        s.subject_name as SubjectName
        FROM class_schedules cs
        JOIN registered_teachers rt ON cs.teacher_id = rt.teacher_professional_id
        JOIN class c ON cs.class_id = c.class_id
        JOIN subjects s ON s.subject_id = cs.subject_id
        WHERE rt.teacher_professional_id = :teacher_id
        AND cs.class_id = :class_id
        ORDER BY cs.class_id;";
        $exec = $this->setBindedExecution($query, ["teacher_id" => $teacherId, "class_id" => $classId])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("TEACHER_SUBJECTS_NOEXIST");
        }
        return $exec;
    }

    public function getMyProfessionalId (string $id, bool $alert = true): string {
        $query = "SELECT teacher_professional_id FROM registered_teachers WHERE teacher_account_id = :account_id;";
        $exec = $this->setBindedExecution($query, ["account_id" => $id])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("PROFESSIONAL_ID_NOEXIST");
        }
        return $exec[0]["teacher_professional_id"];
    }
    
    public function addAssignment(array $data, bool $alert = true) {
        $query = "INSERT INTO assignments (
        assignment_id, 
        assignment_title, 
        assignment_description,
        assignment_due_date, 
        class_id, 
        subject_id, 
        assignment_created_by)
        VALUES (
        :assignment_id, 
        :assignment_title, 
        :assignment_description, 
        :assignment_due_date,
        :class_id, 
        :subject_id, 
        :assignment_created_by);";
        $exec = $this->setBindedExecution($query, $data);
        if ($alert && empty($exec)) {
            $this->middleware->alert("FAILED_ADD_ASSIG");
        }
    }
    
    public function addAssignmentAttachement (array $data, bool $alert = true) {
        $query = "INSERT INTO assignment_attachements (
        attachement_id, 
        assignment_id, 
        assignment_file_id
        ) VALUES (
        :attachement_id, 
        :assignment_id, 
        :assignment_file_id
        );";
        $exec = $this->setBindedExecution($query, $data);
        if (!empty($exec) && $alert) {
            $this->middleware->alert("FAILED_ADD_ASSIG_ATTC");
        }
    }
}