<?php

declare(strict_types=1);

class SubjectTeacherController {
    protected $subjectTeacherModel;
    protected $middleware;
    protected $session;
    protected $fileManager;

    public function __construct(
        SubjectTeacherModel $subjectTeacherModel, 
        Middleware $middleware,
        Session $session,
        FileManagerController $fileManager
    ) {
        $this->subjectTeacherModel = $subjectTeacherModel; 
        $this->middleware = $middleware;
        $this->session = $session;
        $this->fileManager = $fileManager;
    }

    public function getAllClass () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $professionalId = $this->subjectTeacherModel->getMyProfessionalId($uuid, false);
        return $this->subjectTeacherModel->getAllClass($professionalId, false);
    }

    public function getAllAssignments () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $professionalId = $this->subjectTeacherModel->getMyProfessionalId($uuid, false);
        $fetch = $this->subjectTeacherModel->getAllAssignments($professionalId, false);
        return $fetch;
    }

    public function getClassSubject () {
        $JSON = $this->middleware->spillJSON();
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $professionalId = $this->subjectTeacherModel->getMyProfessionalId($uuid, false);
        $fetch = $this->subjectTeacherModel->getClassSubjects($professionalId, $JSON["classId"]);
        return $fetch;
    }

    public function getAllSubjects () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $professionalId = $this->subjectTeacherModel->getMyProfessionalId($uuid, false);
        $fetch = $this->subjectTeacherModel->getAllSubjects($professionalId, false);
        return $fetch;
    }

    public function addAssignment () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $professionalId = $this->subjectTeacherModel->getMyProfessionalId($uuid, false);
        $data = [
            "assignment_id" => $this->middleware->getUniqueId(10),
            "assignment_title" => $_POST["assig-title"] ?? null,
            "assignment_description" => $_POST["assig-notes"] ?? null,
            "assignment_due_date" => $_POST["assig-deadline"] ?? null,
            "assignment_created_by" => $professionalId,
            "class_id" => $_POST["assig-class"] ?? null,
            "subject_id" => $_POST["assig-subject"] ?? null,
        ];
        $file = $_FILES["assig-fileAttachement"] ?? null;
        $this->middleware->isAnyColumnEmpty($data);
        $this->subjectTeacherModel->addAssignment($data, false);

        if (!empty($file)) {
            [$a, $b, $specialC] = $this->fileManager->uploadFile($file, 
            "assignment", $data["class_id"], false);
            $data2 = [
                "attachement_id" => $this->middleware->getUniqueId(10),
                "assignment_id" => $data["assignment_id"],
                "assignment_file_id" => $specialC
            ];
            $this->middleware->isAnyColumnEmpty($data2);
            $this->subjectTeacherModel->addAssignmentAttachement($data2, false);
        }
        $this->middleware->alert("SUCC_ASSIG_CREATION");
    }

    public function getSubmittedAssig (bool $alert = true) {
        $JSON = $this->middleware->spillJSON();
        $assig_id = $JSON["assignment_id"];
        $exec = $this->subjectTeacherModel->getAllAssignmentStudentSubmission($assig_id);
        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_STUDENT_SUBM");
        }
        return $exec;
    }

    public function addScore () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"];
        $profId = $this->subjectTeacherModel->getMyProfessionalId($uuid);
        $data = [
            "score_record_id" => $this->middleware->getUniqueId(10),
            "score_value" => $_POST["add-assignment-score"] ?? null,
            "submitted_assignment" => $_POST["submitted-assig-id"] ?? null,
            "subject_teacher" => $profId,
            "student_lrn" => $_POST["student-lrn-score"] ?? null  
        ];

        $this->middleware->isAnyColumnEmpty($data);
        $this->subjectTeacherModel->isSubjectTeacherExist($data["subject_teacher"]);
        $this->subjectTeacherModel->isThisStudentAssigAlreadyGraded(
            $data["submitted_assignment"], 
            $data["student_lrn"]
        );
        $this->isThisGradeValid($data["score_value"]);
        $this->subjectTeacherModel->addScore($data);
    }

    private function isThisGradeValid(int | string $grade) {
        if (filter_var($grade, FILTER_VALIDATE_INT) === false) {
            $this->middleware->alert("INV_GRADE_VALUE_CONTAINS_STR");
        }
        if (intval($grade) > Config::loadConfig()["SCHOOL_SETTINGS"]["MAX_SCORE_VALUE"]) {
           $this->middleware->alert("INV_GRADE_VALUE_MAX_REACHED");
        }
    }
}