<?php

declare(strict_types=1);

class SuperAdministratorController {
    protected $superAdministratorModel;
    protected $middleware;
    protected $session;
    protected $fileManager;

    public function __construct(
        SuperAdministratorModel $superAdministratorModel,
        Middleware $middleware,
        Session $session,
        FileManagerController $fileManager
    ) {
        $this->superAdministratorModel = $superAdministratorModel;
        $this->middleware = $middleware;
        $this->session = $session;
        $this->fileManager = $fileManager;
    }

    ## IS SUPER ADMINISTRATOR
    public function isSuperAdmin (bool $alert = true): bool {
        $role = $_SESSION["CURR_SESSION"]["ACC_DATA"]["RoleId"] ?? null;
        if ($role !== "SUADM") {
            $this->middleware->alert("INV_SUADM");
        }
        return true;
    }

    ## GET ALL CUSTOM ADD USERS
    public function customAddUsers () {
        $data = [
            "user_uuid" => $this->middleware->getUniqueId(),
            "user_fname" => $_POST["user-fname"] ?? null,
            "user_lname" => $_POST["user-lname"] ?? null,
            "user_gender" => $_POST["user-gender"] ?? null,
            "user_email" => $_POST["user-email"] ?? null,
            "user_role" => $_POST["user-role"] ?? null,
            "user_pwd" => $_POST["user-confirm-password"] ?? null, 
        ];
        $password1 = $_POST["user-create-password"] ?? null;
        $password2 = $_POST["user-confirm-password"] ?? null;
        $this->middleware->isAnyColumnEmpty($data);
        $this->middleware->isPasswordsMatch($password1, $password2);
        $data["user_pwd"] = $this->middleware->hashPassword($password2);
        $this->superAdministratorModel->isUserEmailAlreadyUsed($data["user_email"]);
        $this->superAdministratorModel->addAccount($data);
        $this->superAdministratorModel->addLogRecord($data["user_uuid"], "LT008", "LS001");
        $this->middleware->alert("SUADM_ADD_USER_SUCC");
    }

    ## GET ALL PENDING STUDENT APPLICANT
    public function getAllPendingStudentApplicant (bool $alert = true) {
        $fetch = $this->superAdministratorModel->getAllPendingStudentRegistration($alert);
        return $fetch;
    }

    ## GET ALL ENROLLED STUDENTS
    public function getAllEnrolledStudents (bool $alert = true) {
        $fetch = $this->superAdministratorModel->getAllEnrolledStudent();
        if (empty($fetch) && $alert) $this->middleware->alert("NO_ENR_STU");
        return $fetch;
    }

    ## FIND ENROLLED STUDENT
    public function findEnrolledStudent (bool $alert = true): void {
        $JSON = $this->middleware->spillJSON();
        $data = ["search" => $JSON["data"]];
        $fetch  = $this->superAdministratorModel->findEnrolledStudent($data["search"]);
        if (empty($fetch) && $alert) $this->middleware->alert("NO_ENR_STU");
        die(json_encode($fetch));
    }

    ## APPROVE STUDENT
    public function approveStudent () { 
        $lrn = $this->middleware->spillJSON();
        $this->superAdministratorModel->approveStudentEnrollment($lrn["LRN"]);
    }

    ## REJECT STUDENT 
    public function rejectStudent () {
        $lrn = $this->middleware->spillJSON();
        $this->superAdministratorModel->rejectStudentEnrollment($lrn["LRN"]);
    }

    ## ADD STUDENT TO CLASS
    public function addStudentToClass () {
        $data = [
            "class_student_id" => $this->middleware->getUniqueId(5),
            "class_student_lrn" => $_POST["student-lrns"],
            "class_id" => $_POST["class-id"],
        ];

        foreach ($data["class_student_lrn"] as $lrn) {
            if ($this->superAdministratorModel->isUserAlreadyInClass($lrn, $data["class_id"], false)) continue;
            $d = [
                "class_student_id" => $this->middleware->getUniqueId(10),
                "class_student_lrn" => $lrn,
                "class_id" => $data["class_id"],
            ];
            $this->superAdministratorModel->addStudentToClass($d, false);
        }

        $this->middleware->alert("SUCC_STUD_ADDCLASS");
    }

    public function countStudentInClass () {

    }

    ## ADD SUBJECT
    public function addSubject () {
        $data = [
            "subject_id" => $this->middleware->getUniqueId(10),
            "subject_name" => $_POST["subject-name"],
            "subject_description" => $_POST["subject-description"],
            "subject_type" => $_POST["subject-type"],
            "subject_strand" => $_POST["subject-strand"],
        ];

        $this->middleware->isAnyColumnEmpty($data);
        $this->superAdministratorModel->isSubjectNameAlreadyUsed($data["subject_name"]);
        $this->superAdministratorModel->addSubject($data); 
    }

    public function getAllSubjects (bool $alert = true): array | null {
        $fetch = $this->superAdministratorModel->getAllSubjects($alert);        
        if (empty($fetch) && $alert) {
            $this->middleware->alert("SUBJ_NOEXISTS");
        }
        return $fetch;
    }

    public function getAllUsers () {
        return $this->superAdministratorModel->getAllUsers();
    }

    public function viewUser () {
        $JSON = $this->middleware->spillJSON();
    }

    public function getAllGlobalFiles () {
        $fetch = $this->superAdministratorModel->getAllGlobalFiles();
        return $fetch;
    }

    public function getAllStudentsNotInClass (bool $alert = true): array {
        $fetch = $this->superAdministratorModel->getAllStudentsNotInClass();
        if (empty($fetch) && $alert) $this->middleware->alert("NO_AVAILABLE_STUD");
        return $fetch;
    }

    public function deleteSubject () {
        $JSON = $this->middleware->spillJSON();
        $data = ["subject_id" => $JSON["subject-id"]];
        $this->middleware->isAnyColumnEmpty($data);
        $this->superAdministratorModel->isSubjectExist($data["subject_id"]);
        $this->superAdministratorModel->dropSubject($data);
    }

    public function viewStudentData () {
        $JSON = $this->middleware->spillJSON();
        $data = ["lrn" => $JSON["stu_lrn"]];
        $this->middleware->isAnyColumnEmpty($data);
        $this->superAdministratorModel->isStudentAnEnrollee($data["lrn"]);
        $studentData = $this->superAdministratorModel->getAllStudentEnrolleeData($data["lrn"]);
        $studentData[0]["RegisteredOn"] = $this->middleware->getModifiedTime($studentData[0]["RegisteredOn"]);
        $studentData[0]["Birthdate"] = $this->middleware->getModifiedTime($studentData[0]["Birthdate"]);
        die(json_encode([
            "serverData" => $studentData[0], 
            "status" => "success",
            "initiate" => true,
        ]));
    }

    public function viewClassData (): void {
        $JSON = $this->middleware->spillJSON();
        $data = ["class_id" => $JSON["classId"]];
        $this->middleware->isAnyColumnEmpty($data);
        $this->superAdministratorModel->isClassExist($data["class_id"]);
        $classBasicId = $this->superAdministratorModel->viewAllClassData($data["class_id"]);
        $classStudents = $this->superAdministratorModel->viewAllClassStudents($data["class_id"], false);

        $fullData = [
            "basicClassData" => [...$classBasicId],
            "classStudents" => [...$classStudents]
        ];

        die(json_encode($fullData));
    }

    public function approveStudentEnrollee (): void {
        $JSON = $this->middleware->spillJSON();
        $data = ["LRN" => $JSON["LRN"]];
        $this->middleware->isAnyColumnEmpty($data);
        $this->superAdministratorModel->approveStudentEnrollment($data["LRN"]);
    }

    public function rejectStudentEnrollee (): void {
        $JSON = $this->middleware->spillJSON();
        $data = ["LRN" => $JSON["LRN"]];
        $this->middleware->isAnyColumnEmpty($data);
        $this->superAdministratorModel->rejectStudentEnrollment($data["LRN"]);
    }

    public function getAllClass (bool $alert = true) {
        $fetch = $this->superAdministratorModel->getAllClass();
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_CLASS_AVAILABLE");
        }
        return $fetch;
    }

    public function getAllUnassignedTeachersClass (bool $alert = true) {
        $fetch = $this->superAdministratorModel->getAllUnassignedTeacherClass();
        if (empty($fetch) && $alert) $this->middleware->alert("NO_AVAILABLE_TEACHER");
        return $fetch;
    }

    public function getAllUnassignedRoomClass (bool $alert = true) {
        $fetch = $this->superAdministratorModel->getAllUnassignedRoomClass();
        if (empty($fetch) && $alert) $this->middleware->alert("NO_UNASSG_ROOM_CLASS");
        return $fetch;
    }

    public function getAllTeachers (bool $alert = true): array {
        $fetch = $this->superAdministratorModel->getAllTeachers(false);
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_TEACHER_AVAILABLE");
        }
        return $fetch;
    }

    public function addClassSchedule () {
        $data = [
            "class_id" => $_POST["class-id"] ?? null,
            "subject_id" => $_POST["subject-id"] ?? null,
            "teacher_id" => $_POST["teacher-id"] ?? null,
            "day_of_week" => $_POST["dayOfWeek"] ?? null,
            "start_time" => $_POST["start-time"] ?? null,
            "end_time" => $_POST["end-time"] ?? null,
        ];

        $this->middleware->isAnyColumnEmpty($data);
        $this->superAdministratorModel->checkForScheduleConflict($data["class_id"], $data["day_of_week"], $data["start_time"], $data["end_time"]);
        $this->superAdministratorModel->checkForTeacherSchedConflict($data["day_of_week"], $data["teacher_id"], $data["start_time"], $data["end_time"]);
        $this->superAdministratorModel->addSchedule($data);
    }
    
    public function getAllClassSchedules () {
        $JSON = $this->middleware->spillJSON();
        $data = ["class_id" => $JSON["classId"]];
        $fetch = $this->superAdministratorModel->getAllClassSchedule($data["class_id"]);
        die(json_encode($fetch));
    }



















    ## GET ALL STRAND
    // public function getAllStrand (bool $alert = true) {
    //     $fetch = $this->superAdministratorModel->getAllStrand();
    //     if (empty($fetch) && $alert) {
    //         $this->middleware->alert("STRAND_NOALLEXIST");
    //     }
    //     return $fetch;
    // }



    ## GET ALL UNREGISTERED TEACHERS
    public function getAllUnregisteredTeachers () {
        // $fetch = $this->superAdministratorModel->getAllUnregisteredTeachers();
        // return $fetch;
    }



    public function createClass () {
        $data = [
            "class_id" => $this->middleware->getUniqueId(10) ?? null,
            "class_adviser" => $_POST["class-adviser"] ?? null,
            "class_section_name" => $_POST["class-section-name"] ?? null,
            "class_student_limit" => $_POST["class-student-limit"] ?? null,
            "class_strand" => $_POST["class-strand"] ?? null,
            "class_room" => $_POST["class-room"] ?? null,
            "class_grade_level" => $_POST["class-grade-level"] ?? null,
        ];

        // $this->middleware->isAnyColumnEmpty($data);
        // $this->superAdministratorModel->isTeacherExist($data["class_adviser"]);
        // $this->superAdministratorModel->isStrandExist($data["class_strand"]);
        // $this->superAdministratorModel->isRoomExist($data["class_room"]);
        // $this->superAdministratorModel->isSectionNameAlreadyUsed($data["class_section_name"]);
        // $this->superAdministratorModel->addClass($data);
    }

    public function registerTeacher () { 
        $data = [
            "teacher_professional_id" => $_POST["teacher-professional-id"] ?? null,
            "teacher_account_id" => $_POST["teacher-uuid"] ?? null,
            "teacher_registration_status" => "REG" ?? null,
            "teacher_fname" => $_POST["teacher-fname"] ?? null,
            "teacher_lname" => $_POST["teacher-lname"] ?? null,
            "teacher_mname" => $_POST["teacher-mname"] ?? null,
            "teacher_age" => $_POST["teacher-age"] ?? null,
            "teacher_gender" => $_POST["teacher-gender"] ?? null,
            "teacher_bday" => $_POST["teacher-bday"] ?? null,
        ];

        $this->middleware->isAnyColumnEmpty($data);
        // $this->superAdministratorModel->isUserExist($data["teacher_account_id"]);
        // $this->superAdministratorModel->isTeacherAlreadyRegistered($data["teacher_account_id"]);
        // $this->superAdministratorModel->addTeacher($data);
    }

    public function getAllStudents (): void {

    }

    public function getAllStudentGrades (): void {

    }

    public function getAllLeaveApplications (): void {
        
    }
    
    public function getAllStudentEnrollees (): void {

        
    }

    public function getAllAttendanceReport (): void {

    }
}
