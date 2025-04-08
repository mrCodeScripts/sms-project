<?php

declare(strict_types=1);

class StudentController  {
    protected $studentModel;
    protected $middleware;
    protected $session;
    protected $fileManager;

    public function __construct(
        StudentModel $studentModel, 
        Middleware $middleware, 
        Session $session,  
        FileManagerController $fileManager
    ) {
        $this->studentModel = $studentModel;
        $this->middleware = $middleware;
        $this->session = $session;
        $this->fileManager = $fileManager;
    }

    public function getAllClass (): array {
        return $this->studentModel->getAllClass();
    }

    public function isAStudent ($sendErrorMsg = true): bool {
        $role = $_SESSION["CURR_SESSION"]["ACC_DATA"]["RoleId"] ?? null;
        if ($role !== "STU" && $sendErrorMsg) {
            $this->middleware->alert("not_a_student");
        }
        return !empty($role);
    }

    public function isFullyVerifiedStudent (?bool $sendErrorMsg = true): bool {
        $userId = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $fetch = $this->studentModel->findRegisteredStudent($userId, false);
        if (!$fetch && $sendErrorMsg) { $this->middleware->alert("student_not_fully_reg"); }
        return !empty($fetch);
    }

    public function loadFormEnrollmentForm (): bool {
        $userId = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $enrollmentStatus = $this->studentModel->getRegisteredStudent($userId, false)[0]["student_registration_status"] ?? null;
        if ($enrollmentStatus == "PND") {
            echo "
                <h1 class='alert-enr'>
                    Your enrollment is pending. We will notify you once its status updates.
                </h1>
            ";
            return true;
        } else if ($enrollmentStatus == "ENR") {
            echo "
                <h1 class='alert-enr'>
                    You are already enrolled.
                </h1>
            ";
            return true;
        }
        return false;
    }

    public function enrollStudent () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $data = [
            "student_learning_ref_number" => $_POST["student-lrn"],
            "student_account_id" => $uuid,
            "student_registration_status" => "PND",
            "student_fname" => $_POST["student-fname"] ?? null,
            "student_lname" => $_POST["student-lname"] ?? null,
            "student_mname" => $_POST["student-mname"] ?? null,
            "student_requested_strand" => $_POST["student-strand"] ?? null,
            "student_age" => $_POST["student-age"] ?? null,
            "student_gender" => $_POST["student-gender"] ?? null,
            "student_bday" => $_POST["student-bday"] ?? null,
        ];
        $data2 = [
            "student_birth_certificate" => $_FILES["student-birth-cert"] ?? null,
            "student_enrollment_form" => $_FILES["student-enr-form"] ?? null,
            "student_form_127" => $_FILES["student-form-137"] ?? null,
            "student_good_moral_cerf" => $_FILES["student-good-moral-cert"] ?? null,
            "student_report_card" => $_FILES["student-report-card"] ?? null,
            "student_photo" => $_FILES["student-photo"] ?? null,
        ];

        $this->middleware->isAnyColumnEmpty($data);
        $this->middleware->isAnyColumnEmpty($data2);
        $this->studentModel->analyzeDuplicateEnrollment($data["student_learning_ref_number"], $data["student_account_id"]);
        
        $lrn = $data["student_learning_ref_number"] ?? null;
        $fId1 = $this->uploadBirthCert($data2["student_birth_certificate"], $lrn);        
        $fId2 = $this->uploadEnrollmentForm($data2["student_enrollment_form"], $lrn);
        $fId3 = $this->uploadForm137($data2["student_form_127"], $lrn);
        $fId4 = $this->uploadGoodMoralCert($data2["student_good_moral_cerf"], $lrn);
        $fId5 = $this->uploadReportCard($data2["student_report_card"], $lrn);
        $fId6 = $this->uploadStudentPhoto($data2["student_photo"], $lrn);
        $finalData = [
            ...$data,
            "student_birth_certificate" => $fId1,
            "student_enrollment_form" => $fId2,
            "student_form_127" => $fId3,
            "student_good_moral_cerf" => $fId4,
            "student_report_card" => $fId5,
            "student_photo" => $fId6,
        ];
        $this->studentModel->registerNewStudent($finalData);
    }

    public function uploadBirthCert(array $file, string $lrn): string {
        [$a, $b, $c] = $this->fileManager->uploadFile($file, "birth_cert", $lrn, false);
        $this->fileManager->uploadFile($file, "other", "", false);
        return $c;
    }

    public function uploadEnrollmentForm(array $file, string $lrn): string {
        [$a, $b, $c] = $this->fileManager->uploadFile($file, "enrollment_form", $lrn, false);
        $this->fileManager->uploadFile($file, "other", "", false);
        return $c;
    }

    public function uploadForm137 (array $file, string $lrn): string {
        [$a, $b, $c] = $this->fileManager->uploadFile($file, "form137", $lrn, false);
        $this->fileManager->uploadFile($file, "other", "", false);
        return $c;
    }

    public function uploadGoodMoralCert (array $file, string $lrn): string {
        [$a, $b, $c] = $this->fileManager->uploadFile($file, "good_moral", $lrn, false);
        $this->fileManager->uploadFile($file, "other", "", false);
        return $c;
    }

    public function uploadReportCard (array $file, string $lrn): string {
        [$a, $b, $c] = $this->fileManager->uploadFile($file, "report_card", $lrn, false);
        $this->fileManager->uploadFile($file, "other", "", false);
        return $c;
    }

    public function uploadStudentPhoto (array $file, string $lrn): string {
        [$a, $b, $c] = $this->fileManager->uploadFile($file, "student_photo", $lrn, false);
        $this->fileManager->uploadFile($file, "other", "", false);
        return $c;
    }

    public function getAllAssignments () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $lrn = $this->studentModel->getMyLrn($uuid);
        $myClassId = $this->studentModel->getMyClassId($lrn);
        $assignments = $this->studentModel->getAllMyAssignments($myClassId, $lrn);
        return $assignments;
    }

    public function submitAssignment () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"];
        $data = [
            "submission_id" => $this->middleware->getUniqueId(10),
            "assignment_id" => $_POST["assignment-id"] ?? null,
            "student_lrn" => $this->studentModel->getMyLrn($uuid),
            "submission_text" => $_POST["assignment-note"] ?? null,
            "submission_status" => "pending",
            "submission_file_id" => $_FILES["assignment-file"] ?? null,
        ];

        $this->middleware->isAnyColumnEmpty($data);
        [$a2, $b2, $c2] = $this->fileManager->uploadFile($data["submission_file_id"], "other", "", false); 
        [$a, $b, $c] = $this->fileManager->uploadFile($data["submission_file_id"], "assignment", $uuid, false);
        $data["submission_file_id"] = $c;
        $this->studentModel->addNewSubmittedAssignment($data["assignment_id"], $data["student_lrn"], $data["submission_file_id"], "pending");
    }

    public function countCompleteAssig (bool $alert = true) {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $lrn = $this->studentModel->getMyLrn($uuid);
        $myClassId = $this->studentModel->getMyClassId($lrn);
        $fetch = $this->studentModel->countCompletedAssignments($lrn, $myClassId, false);
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_COMPLETED_ASSIG");
        } 
        return $fetch[0]["completed_assignments"];
    }
    
    public function countNewAssig (bool $alert = true) {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $lrn = $this->studentModel->getMyLrn($uuid);
        $myClassId = $this->studentModel->getMyClassId($lrn);
        $fetch = $this->studentModel->countNewAssignments($lrn, $myClassId, false);
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_NEW_ASSIG_FOUND");
        } 
        return $fetch[0]["pending_or_upcoming_assignments"];
    }

    public function countPendingAssig (bool $alert = true) {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $lrn = $this->studentModel->getMyLrn($uuid);
        $myClassId = $this->studentModel->getMyClassId($lrn);
        $fetch = $this->studentModel->countPendingAssignments($lrn, $myClassId, false);
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_PENDING_ASSIG_FOUND");
        } 
        return $fetch[0]["pending_assignments"];
    }

    public function countSubmittedAssigToday (bool $alert = true) {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $lrn = $this->studentModel->getMyLrn($uuid);
        $myClassId = $this->studentModel->getMyClassId($lrn);
        $fetch = $this->studentModel->countSubmittedAssignmentsToday($lrn, $myClassId, false);
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_TODAY_SUB_ASSIG_FOUND");
        } 
        return $fetch[0]["submmitted_assig_today"];
    }
    
}





























    /*
    public function getStudentAccRegistrationStatus (?bool $sendErrorMsg = true): mixed {
        $userId = $_SESSION["current_session"]["current_user_uuid"] ?? null;
        $fetched = $this->studentModel->getRegisteredStudent($userId, false);
        if (empty($fetched) && $sendErrorMsg) {
            $this->middleware->alert("teacher_not_fully_reg");
        }
        return $fetched[0]["student_registration_status"] ?? null;
    }

    public function isStudentAlreadyReg (string $user_uniqueId, ?bool $sendErrorMsg = true): bool {
        $search = $this->studentModel->findRegisteredStudent($user_uniqueId, false);
        if ($sendErrorMsg && !empty($search)) {
            $this->middleware->alert("student_already_reg");
        }
        return !empty($search);
    }

    public function registerStudentAccount (): void {
        $studentLRN = $_POST["student-lrn"] ?? null;
        $studentFirstName = $_POST["student-first-name"] ?? null;
        $studentLastName = $_POST["student-last-name"] ?? null;
        $studentAge = $_POST["student-age"] ?? null;
        $studentGenderId = $_POST["student-gender"] ?? null;
        $studentBirthDate = $_POST["student-birth-date"] ?? null;
        $userAccId = $_SESSION["current_session"]["current_user_uuid"] ?? null;
        $studentFilesInfo = $_FILES["enrollment-file-upload"] ?? null;

        if (empty($studentLRN) || empty($studentFirstName) 
        || empty($studentLastName) || empty($studentAge) || 
        empty($studentGenderId) || empty($studentBirthDate)) 
        {
            $this->middleware->alert("incomplete_data");
        }

        $this->isValidUserId($userAccId);
        $this->isValidGenderID($studentGenderId, false);
        $this->isStudentAlreadyReg($userAccId, false);
        $this->middleware->isInteger($studentAge);

        $extension = "{$userAccId}/{$studentLRN}";
        $this->fileManager->uploadFile($studentFilesInfo, "enrollment_form", $extension, false);

        $refinedData = [
            "student_lrn" => $studentLRN,
            "student_registration_status" => "PND",
            "student_fname" => $this->middleware->filterString($studentFirstName),
            "student_lname" => $this->middleware->filterString($studentLastName),
            "student_age" => intval($studentAge),
            "student_gender" => $studentGenderId,
            "student_bday" => $studentBirthDate,
            "student_account_id" => $userAccId,
        ];
        $this->studentModel->registerNewStudent($refinedData);
    }

    public function submitAssignment (bool $sendErrorMsg = true) {
        $userId = $_SESSION["current_session"]["current_user_uuid"] ?? null;
        $lrn = $this->studentModel->getMyLrn($userId);
        $assignmentFile = $_FILES["assig-file-upload"] ?? null;
        $assignmentId = $_POST["assig-id"] ?? null;
        $assignmentDesc = $_POST["assig-desc"] ?? null;
        $assignmentClassId = $this->studentModel->getMyClassId($lrn);

        if (empty($userId) || empty($assignmentFile) || 
        empty($assignmentClassId) || empty($assignmentId)) {
            $this->middleware->alert("incomplete_data");
        }
        
        $studentLRN = $this->studentModel->getMyLrn($userId);
        $customPath = $studentLRN . "/" . $assignmentClassId;

        // $this->studentModel->isStudentAlreadySubmitted($lrn, $assignmentId);

        [$exec2, $store2, $fileId2] = $this->fileManager->uploadFile($assignmentFile, "assignment", 
        $customPath, false);

        [$exec1, $store1, $fileId1] = $this->fileManager->uploadFile($assignmentFile, "other", "", false);

        $exec3 = $this->studentModel->addNewSubmittedAssignment($assignmentId, $studentLRN, $fileId2, $assignmentDesc);

        $sendErrorMsg && $exec1 && $exec2 && $exec3 ? $this->middleware->alert("successful_assig_sub") 
        : $this->middleware->alert("failed_assig_sub");
    }

    public function getAllMyTasks (): array {
        $lrn = $this->studentModel->getMyLrn($_SESSION["current_session"]["current_user_uuid"]);
        $classid = $this->studentModel->getMyClassId($lrn);

        $tasks = $this->studentModel->getAllMyTask($classid);
        return $tasks;
    }

    // public function getAllMyAssignments (): array {
        // $userid = $_SESSION["current_session"]["current_user_uuid"];
        // $lrn = $this->studentModel->getMyLrn($userid);
        // $data = $this->studentModel->getAllMyAssignments($lrn);
        // return $data;
    // }

    public function complyTask() {
        $JSON = $this->middleware->spillJSON();
        $mylrn = $this->studentModel->getMyLrn($_SESSION["current_session"]["current_user_uuid"]);
        $this->studentModel->comply($JSON["comply"], $mylrn);
    }


    public function getAllSubjects ():array {
        return $this->studentModel->getAllSubjects();
    }

    public function getClassFilesPath () {

    }

    public function addNewTasks () {
        # TODO
    }

    // public function getAllMyGrades () {
    //     # TODO
    // }

    public function getAllAvailableLearningMaterials () {
        # TODO
    }

    public function downloadAvailableLearningMaterials () {
        # TODO 
    }

    public function uploadLearningMaterial () {
        # TODO
    }

    public function getAllMyClassSchedules () {
        $query = "SELECT
        cs.class_sched_start as StartTime,
        cs.class_sched_end as EndTime,
        subj.subject_name as SubjectName,
        subj.subject_description as SubejectDesc,
        da.day_name as Day
        FROM class_schedule_alloc_day cda
        JOIN class_schedules_days da ON da.day_id = cda.class_schedule_day
        JOIN class_schedules cs ON cs.class_sched_id = cda.class_schedule_id
        JOIN class_subjects subj ON cs.class_sched_subject_id = subj.class_subject_id
        JOIN class_subjects subj ON cs.class_sched_subject_id = subj.class_subject_id
        WHERE cs.class_sched_class_id = :class_id;
        ";

        $classId = $this->studentModel->getMyClassId($_SESSION["current_session"]["current_user_uuid"]);
        $fetch = $this->setBindedExecution($query, ["class_id" => $classId])->fetchAll();

        return $fetch;
    }

    public function getMyAssig (): array {
        $myLrn = $this->studentModel->getMyLrn($_SESSION["current_session"]["current_user_uuid"]);
        $classId = $this->studentModel->getMyClassId($myLrn);
        return $this->studentModel->getAllMyAssignments($classId, false);
    }

    public function getAllMyGrades (): array {
        $myLrn = $this->studentModel->getMyLrn($_SESSION["current_session"]["current_user_uuid"]);

        $query = "SELECT
        grd.grade_id as GradeId,
        grd.grade_value as Grade,
        grt.term_name as Term,
        grt.term_desc as TermDescription,
        grr.grade_remark_name as Remark,
        grr.grade_remark_desc as RemarkDesc,
        stu.student_fname as StudentFirstname,
        stu.student_lname as StudentLastname,
        stu.student_learning_ref_number as LRN,
        subj.class_subject_name as SubjectName,
        subj.class_subject_id as SubjectId,
        subj.class_subject_description as SubjectDescription
        FROM registered_students stu
        JOIN grades grd ON stu.student_learning_ref_number = grd.grade_student_lrn
        JOIN grade_term grt ON grt.term_id = grd.grade_term
        JOIN class_subjects subj ON subj.class_subject_id = grd.grade_subject
        JOIN grade_remark grr ON grr.grade_remark_id = grd.grade_remarks
        WHERE grd.grade_student_lrn = :lrn;";
        $exec = $this->setBindedExecution($query, [
            "lrn" => $myLrn
        ])->fetchAll();  
        return $exec;
    }
    */