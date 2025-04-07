<?php

declare(strict_types=1);

session_start();
session_regenerate_id(true);

include __DIR__ . "/../src/bootstrap.php";

Router::get("/", function () {
    include __DIR__ . "/../src/views/mainpage.php";
    die();
});

Router::get("/download/file", function ($fileManagerController) {
    $fileManagerController->downloadFile($_GET["path"]);
}, $fileManagerController);

Router::get("/views/login", function ($middleware, $userAccountController) {
    if ($middleware->isLoggedIn()) $middleware->roleRedirection();
    include __DIR__ . "/../src/views/auth.page/login.php";
    die();
}, $middleware, $userAccountController);

Router::get("/views/signup", function ($middleware, $userAccountController) {
    if ($middleware->isLoggedIn()) $middleware->roleRedirection();
    include __DIR__ . "/../src/views/auth.page/signup.php";
    die();
}, $middleware, $userAccountController);

Router::get("/views/superAdmin/dashboard", function ($middleware, $superAdministratorController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/superAdmin/dashboard.php";
    die();
}, $middleware, $superAdministratorController);

Router::get("/views/superAdmin/students", function ($middleware, $superAdministratorController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $superAdministratorController->isSuperAdmin();
    include __DIR__ . "/../src/views/superAdmin/students.php";
    die();   
}, $middleware, $superAdministratorController);

Router::get("/views/superAdmin/addSubject", function ($middleware, $superAdministratorController, $userAccountController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $superAdministratorController->isSuperAdmin();
    include __DIR__ . "/../src/views/superAdmin/addSubject.php";
    die();   
}, $middleware, $superAdministratorController, $userAccountController);

Router::get("/views/superAdmin/users", function ($middleware, $superAdministratorController, $userAccountController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $superAdministratorController->isSuperAdmin();
    include __DIR__ . "/../src/views/superAdmin/users.php";
    die();      
}, $middleware, $superAdministratorController, $userAccountController);

Router::get("/views/superAdmin/classManager", function ($middleware, $superAdministratorController, $userAccountController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $superAdministratorController->isSuperAdmin();
    include __DIR__ . "/../src/views/superAdmin/classManager.php";
    die();
}, $middleware, $superAdministratorController, $userAccountController);

Router::get("/views/superAdmin/grades", function ($middleware, $superAdministratorController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $superAdministratorController->isSuperAdmin();
    include __DIR__ . "/../src/views/superAdmin/grades.php";
    die();
}, $middleware, $superAdministratorController);

Router::get("/views/superAdmin/leaveApplications", function ($middleware, $superAdministratorController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $superAdministratorController->isSuperAdmin();
    include __DIR__ . "/../src/views/superAdmin/leaveApplications.php";
    die();   
}, $middleware, $superAdministratorController);

Router::get("/views/superAdmin/studentApproval", function ($middleware, $superAdministratorController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $superAdministratorController->isSuperAdmin();
    include __DIR__ . "/../src/views/superAdmin/studentApproval.php";
    die();      
}, $middleware, $superAdministratorController);

Router::get("/views/superAdmin/fileManagement", function ($middleware, $superAdministratorController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $superAdministratorController->isSuperAdmin();
    include __DIR__ . "/../src/views/superAdmin/fileManagement.php";
    die();
}, $middleware, $superAdministratorController);

Router::get("/views/superAdmin/attendanceReport", function ($middleware, $superAdministratorController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $superAdministratorController->isSuperAdmin();
    include __DIR__ . "/../src/views/superAdmin/attendanceReport.php";
    die();   
}, $middleware, $superAdministratorController);

Router::get("/views/superAdmin/settings", function ($middleware, $superAdministratorController, $userAccountController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $superAdministratorController->isSuperAdmin();
    include __DIR__ . "/../src/views/superAdmin/settings.php";
    die();      
}, $middleware, $superAdministratorController, $userAccountController);


Router::get("/views/student/dashboard", function ($middleware, $studentControllers) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/student/dashboard.php";
    die();
},$middleware, $studentControllers);

Router::get("/views/student/studentInformations", function ($middleware, $studentControllers, $userAccountController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/student/studentInformations.php";
    die();
}, $middleware, $studentControllers, $userAccountController);

Router::get("/views/student/academics", function ($middleware, $studentControllers) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/student/academics.php";
    die();
}, $middleware, $studentControllers);

Router::get("/views/student/tasks", function ($middleware) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/student/tasks.php";
    die();
}, $middleware);

Router::get("/views/student/grades", function ($middleware) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/student/grades.php";
    die();   
}, $middleware);

Router::get("/views/student/leaveApplications", function ($middleware) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/student/leaveApplications.php";
    die();   
}, $middleware);

Router::get("/views/student/fileManagement", function ($middleware, $userAccountController, $fileManagerController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/student/fileManagement.php";
    die();   
}, $middleware, $userAccountController, $fileManagerController);

Router::get("/views/student/attendance", function ($middleware) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/student/attendance.php";
    die();   
}, $middleware);

Router::get("/student/download", function ($fileManagerController) {
    if (isset($_GET["file"])) { 
        $fileManagerController->downloadFile($_GET["file"]);
        header("Location: /views/student/fileManagement");
    }
}, $fileManagerController);

Router::get("/views/student/settings", function ($middleware, $userAccountController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/student/settings.php";
    die();   
}, $middleware, $userAccountController);

Router::get("/views/subjectTeacher/dashboard", function ($middleware) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/subjectTeacher/dashboard.php";
    die();
}, $middleware);

Router::get("/views/subjectTeacher/assignments", function ($middleware, $userAccountController, $subjectTeacherController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    $subjectTeacherController->getAllAssignments();
    include __DIR__ . "/../src/views/subjectTeacher/assignments.php";
    die();
}, $middleware, $userAccountController, $subjectTeacherController);

Router::get("/views/subjectTeacher/settings", function ($middleware, $userAccountController) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/subjectTeacher/settings.php";
    die();
}, $middleware, $userAccountController);



Router::get("/views/adviserTeacher/dashboard", function ($middleware) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/adviserTeacher/dashboard.php";
    die();
}, $middleware);

Router::get("/views/adviserTeacher/settings", function ($middleware) {
    if (!$middleware->isLoggedIn()) header("Location: /views/login");
    $middleware->roleRedirection();
    include __DIR__ . "/../src/views/adviserTeacher/settings.php";
    die();
}, $middleware);












##############################################
###                                        ###
###             REQUEST ROUTES             ###
###                                        ###
##############################################

// For login requests
Router::post("/request/login", function (
    $userAccountController, 
    $middleware
) {
    header("Content-Type: application/json");
    $middleware->isAlreadyLoggedIn();
    $userAccountController->login();
}, $userAccountController, $middleware);

// For signup requests
Router::post("/request/signup", function (
    $userAccountController, 
    $middleware
) {
    header("Content-Type: application/json");
    $middleware->isAlreadyLoggedIn();
    $userAccountController->signup();
}, $userAccountController, $middleware);

// For logout requests
Router::post("/request/logout", function (
    $userAccountController, 
    $middleware
) {
    header("Content-Type: application/json");
    $middleware->isAlreadyLoggedOut();
    $userAccountController->logout();
}, $userAccountController, $middleware);

// For searching students
Router::post("/request/superAdmin/searchStudent", function ($superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    $superAdministratorController->findEnrolledStudent();
}, $superAdministratorController);

// For getting all pending student requests (enrollee)
Router::post("/request/superAdmin/getAllPendingStudents", function ($superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    die(json_encode($superAdministratorController->getAllPendingStudentApplicant()));
}, $superAdministratorController);

// Enroll a student
Router::post("/request/student/enroll", function ($studentControllers) {
    $studentControllers->isAStudent();
    $studentControllers->enrollStudent();
}, $studentControllers);

// Upload files
Router::post("/request/upload", function ($fileManagerController, $userAccountController) {
    $userAccountController->uploadMyFile(); 
}, $fileManagerController, $userAccountController);

// Add Subject
Router::post("/request/addSubject", function ($superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    $superAdministratorController->addSubject();
}, $superAdministratorController);

// Remove subject
Router::post("/request/removeSubject", function ($superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    $superAdministratorController->deleteSubject();
}, $superAdministratorController);

// view student data
Router::post("/request/viewStudentData", function ($superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    $superAdministratorController->viewStudentData();
}, $superAdministratorController);

Router::post("/request/superAdmin/approveStudent", function ($superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    $superAdministratorController->approveStudentEnrollee();
}, $superAdministratorController);

Router::post("/request/superAdmin/rejectStudent", function ($superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    $superAdministratorController->rejectStudentEnrollee();
}, $superAdministratorController);

Router::post("/request/superAdmin/viewClassData", function ($middleware, $superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    $superAdministratorController->viewClassData();
}, $middleware, $superAdministratorController);

Router::post("/request/superAdmin/addStudentToClass", function ($superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    $superAdministratorController->addStudentToClass();
}, $superAdministratorController);

Router::post("/request/superAdmin/addClassSched", function ($superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    $superAdministratorController->addClassSchedule();
}, $superAdministratorController);

Router::post("/request/superAdmin/viewClassSchedule", function ($superAdministratorController) {
    $superAdministratorController->isSuperAdmin();
    $superAdministratorController->getAllClassSchedules();
}, $superAdministratorController);

Router::post("/request/subjectTeacher/getClassSubject", function ($subjectTeacherController) {
    die(json_encode($subjectTeacherController->getClassSubject()));
}, $subjectTeacherController);

Router::post("/request/subjectTeacher/addAssignment", function ($subjectTeacherController) {
    $subjectTeacherController->addAssignment();
}, $subjectTeacherController);

Router::post("/request/student/submitAssignment", function ($studentControllers) {
    $studentControllers->submitAssignment();
}, $studentControllers);

Router::post("/request/subjectTeacher/getAssignmentSubmissions", function ($subjectTeacherController, $middleware) {
    $sub = $subjectTeacherController->getSubmittedAssig();
    foreach ($sub as &$s) {
        $s["SubmittedOn"] = $middleware->getDateTimeFormat($s["SubmittedOn"]);
    }
    die(json_encode([
        "renderData" => [
            ...$sub
        ],
        "status" => "success",
        "render" => true
    ]));
}, $subjectTeacherController, $middleware);

Router::dispatch($_SERVER["REQUEST_URI"], $_SERVER["REQUEST_METHOD"]);