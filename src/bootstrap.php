<?php

include_once __DIR__ . "/../vendor/autoload.php";
include_once __DIR__ . "/config.php";
include_once __DIR__ . "/router.php";

include_once __DIR__ . "/database.php";
include_once __DIR__ . "/router.php";
include_once __DIR__ . "/middleware.php";
include_once __DIR__ . "/session.php";
include_once __DIR__ . "/models/UserAccount.model.php";
include_once __DIR__ . "/controllers/UserAccount.controller.php";
include_once __DIR__ . "/controllers/Administrator.controller.php";
include_once __DIR__ . "/models/Administrator.model.php";
include_once __DIR__ . "/controllers/SuperAdministrator.controller.php";
include_once __DIR__ . "/models/SuperAdministrator.model.php";
include_once __DIR__ . "/controllers/FileManager.controller.php";
include_once __DIR__ . "/models/FileManager.model.php";
include_once __DIR__ . "/controllers/Student.controller.php";
include_once __DIR__ . "/models/Student.model.php";
include_once __DIR__ . "/models/SubjectTeacher.model.php";
include_once __DIR__ . "/controllers/SubjectTeacher.controller.php";

$database = new Database();
$session = new Session();
$middleware = new Middleware();
$userAccountModel = new UserAccountModel($middleware);
$fileManagerModel = new FileManagerModel($middleware);
$fileManagerController = new FileManagerController(__DIR__ . "/../uploads", $fileManagerModel, $middleware, 50000000);
$administratorModel = new AdministratorModel($middleware);
$administratorController = new AdministratorController($administratorModel, $middleware, $session, $fileManagerController);
$userAccountController = new UserAccountController($database, $session, $userAccountModel, $middleware, $fileManagerController);
$superAdministratorModel = new SuperAdministratorModel($middleware);
$superAdministratorController = new SuperAdministratorController($superAdministratorModel, $middleware, $session, $fileManagerController);
$studentModel = new StudentModel($middleware);
$studentControllers = new StudentController($studentModel, $middleware, $session, $fileManagerController);
$subjectTeacherModel = new SubjectTeacherModel($middleware);
$subjectTeacherController = new SubjectTeacherController($subjectTeacherModel, $middleware, $session, $fileManagerController);


/**
 * FOUR ROLES:
 * 
 * Principals
 * Administrators
 * Teacher Advisers
 * Subject Teachers
 * Librarians
 * Students
 */