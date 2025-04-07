<?php
declare(strict_types=1);
class UserAccountController {
    protected $database;
    protected $settings;
    protected $session;
    protected $uaccMod;
    protected $middleware;
    protected $fileUploader;

    public function __construct (
        Database $database, 
        Session $session, 
        UserAccountModel $uaccMod, 
        Middleware $middleware,
        FileManagerController $fileUploader
    ) {
        $this->database = $database;
        $this->settings = Config::loadConfig();
        $this->session = $session;
        $this->uaccMod = $uaccMod;
        $this->middleware = $middleware;
        $this->fileUploader = $fileUploader;
    }

    public function login () {
        header("Content-Type: application/json");
        $data = [
            "user_email" => $_POST["login-email"] ?? null,
            "user_pwd" => $_POST["login-password"] ?? null,
            "user_role" => $_POST["login-role"] ?? null,
        ];
        $this->middleware->isAnyColumnEmpty($data);
        $this->middleware->validateEmailFormat($data["user_email"]);
        $getData = $this->uaccMod->findUser($data["user_email"], $data["user_role"])[0];
        $this->middleware->verifyPassword($getData["user_pwd"], $data["user_pwd"]);
        $this->uaccMod->addLogRecord($getData["user_uuid"], "LT001", "LS001");
        $this->setAllAccountData($getData["user_uuid"], $getData["user_role"]);
        $this->middleware->alert("LOGIN_SUCCESS");
    }

    public function signup () {
        header("Content-Type: application/json");
        $data = [
            "user_uuid" => $this->middleware->getUniqueId(),
            "user_fname" => $_POST["signup-fname"] ?? null,
            "user_lname" => $_POST["signup-lname"] ?? null,
            "user_gender" => $_POST["signup-gender"] ?? null,
            "user_email" => $_POST["signup-email"] ?? null,
            "user_role" => "STU",
            "user_pwd" => $_POST["signup-confirm-password"] ?? null, 
        ];
        $password1 = $_POST["signup-create-password"] ?? null;
        $password2 = $_POST["signup-confirm-password"] ?? null;
        $this->middleware->isAnyColumnEmpty($data);
        $this->middleware->isPasswordsMatch($password1, $password2);
        $data["user_pwd"] = $this->middleware->hashPassword($password2);
        $this->uaccMod->isGenderExist($data["user_gender"]);
        $this->uaccMod->isUserEmailAlreadyUsed($data["user_email"]);
        $this->uaccMod->addAccount($data);
        $this->uaccMod->addLogRecord($data["user_uuid"], "LT008", "LS001");
        $this->setAllAccountData($data["user_uuid"], $data["user_role"], false);
        $this->middleware->alert("SUCC_ADDACC");
    }

    public function setAllAccountData (string $uuid, string $role, bool $alert = true) {
        header("Content-Type: application/json");
        $data1 = $this->uaccMod->getAllAccData($uuid, $role)[0];
        $data2 = $this->uaccMod->getAllLogRec($uuid, "ORDER BY log_rec_time ASC", false) ?? null;

        if ((empty($data1) || empty($data2)) && $alert) {
            $this->middleware->alert("FAILED_ACCSETUP");
        }

        $JSON = [
            "CURRENT_USER_UUID" => $uuid,
            "ISLOGGEDIN" => true,
            "ACC_DATA" => [
                ...$data1,
                "ACC_LOG_DATA" => [
                    "LOG_SORTING" => $this->settings["ACC_DEF_SORT_LOG"],
                    "LOGS" => $data2,
                ]
            ]
        ];
        $_SESSION["CURR_SESSION"] = $JSON;
    }

    public function updateAccData () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $role = $_SESSION["CURR_SESSION"]["ACC_DATA"]["RoleId"] ?? null;
        $data = $this->uaccMod->getAllAccData($uuid, $role, false)[0];
        $_SESSION["CURR_SESSION"]["ACC_DATA"] = [...$data];
    }

    public function updateLogRecord () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $sort = $_SESSION["CURR_SESSION"]["ACC_DATA"]["ACC_LOG_DATA"]["LOG_SORTING"] 
        ?? $this->settings["ACC_DEF_SORT_LOG"];
        // $sort = "ORDER BY log_rec_time DESC";
        $_SESSION["CURR_SESSION"]["ACC_DATA"]["ACC_LOG_DATA"]["LOG_SORTING"] = $sort;
        $data = $this->uaccMod->getAllLogRec($uuid, $sort, false);
        $_SESSION["CURR_SESSION"]["ACC_DATA"]["ACC_LOG_DATA"]["LOGS"] = [...$data];
    }

    public function logout () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $this->uaccMod->addLogRecord($uuid, "LT002", "LS001");
        session_regenerate_id(true);
        session_unset();
        session_destroy();
        $this->middleware->alert("SUCC_LOGOUT");
    }

    public function updatePassword () {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $lastPasswordUpdate = $_SESSION["CURR_SESSION"]["ACC_DATA"]["PasswordLastChanged"] ?? null;
        $data = [
            "current_pwd" => $_POST["current-password"],
            "create_new_pwd" => $_POST["create-new-password"],
            "confirm_new_pwd" => $_POST["confirm-new-password"],
        ];
        $currentPassword = $this->uaccMod->getMyPassword($uuid);
        $this->middleware->verifyPassword($currentPassword, $data["current_pwd"]);
        $this->middleware->isPasswordsMatch($data["create_new_pwd"], $data["confirm_new_pwd"]);
        $this->middleware->isTimeNotAllowedUpdatePwd($lastPasswordUpdate);
        $this->uaccMod->updatePassword($uuid, $data["confirm_new_pwd"], $data["current_pwd"]);
    }

    public function addLog () {

    }

    public function updateName () {

    }

    public function updateEmail () {

    }

    public function updateGender () {

    }

    public function changePassword () {
        $data = [
            "current_password" => $_POST["current-password"] ?? null,
            "create_new_password" => $_POST["create-new-password"],
            "confirm_new_password" => $_POST["confirm-new-password"],
        ];
        
        $this->middleware->isPasswordsMatch($data["create_new_password"], $data["confirm_new_password"]);
        // $this->middleware->isPasswords
    }

    ###############################
    ###                         ###
    ###     DEFAULT GETTERS     ###
    ###                         ###
    ###############################
    public function getUserRoles (): array {
        $fetch = $this->uaccMod->getAllUserRoles();
        return $fetch;
    }

    public function getGenders (): array {
        $fetch = $this->uaccMod->getAllGenders();
        return $fetch;
    }

    public function changeBuildingSelection (): void { 
        $JSON = $this->middleware->spillJSON();
        $buildingId = $JSON["building_id"] ?? null;
        if (empty($buildingId)) $this->middleware->alert("INCOMPLETE_DATA");
        $floors = $this->uaccMod->getAllFloors($buildingId);
        die(json_encode(["floors" => $floors]));
    }

    public function uploadMyFile () {
        $data = [
            "file" => $_FILES["user-upload-file"] ?? null,
            "doctype" => "other",
        ];

        $this->middleware->isAnyColumnEmpty($data);
        $this->fileUploader->uploadFile(
            $data["file"], 
            "other",
            "",
            true,
        );
    }

    public function getMyFiles (bool $alert = true): array {
        $uuid = $_SESSION["CURR_SESSION"]["CURRENT_USER_UUID"] ?? null;
        $fetch = $this->uaccMod->getAllMyFiles($uuid, $alert);
        return $fetch;
    }

    public function getAllSubjectTypes (bool $alert = true): array | null {
        $fetch = $this->uaccMod->getAllSubjectTypes(false) ?? null;
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_SUBJ_TYPES");
        }
        return $fetch;
    }

    public function getAllStrand (bool $alert = true): array | null {
        $fetch = $this->uaccMod->getAllStrand(false) ?? null; 
        if (empty($fetch) && $alert) {
            $this->middleware->alert("STRAND_NOALLEXIST");
        }
        return $fetch;
    }

    public function getAllRooms (bool $alert = true): array | null {
        $fetch = $this->uaccMod->getAllRooms(false) ?? null; 
        if (empty($fetch) && $alert) {
            $this->middleware->alert("ROOMS_NOEXIST");
        }
        return $fetch;
    }

    public function getAllTeachers (bool $alert = true): array {
        $fetch = $this->uaccMod->getAllTeachers(false) ?? null;
        if (empty($fetch) && $alert) {
            $this->middleware->alert("TEACHERS_NOEXIST");
        }
        return $fetch;
    }

    public function getDays (bool $alert = true) {
        $fetch = $this->uaccMod->getAllDays(false);
        if (empty($fetch) && $alert) {
            $this->middleware->alert("NO_DAYS_AVAILABLE");
        }
        return $fetch;
    }
}