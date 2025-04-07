<?php

declare(strict_types=1);

class Middleware {
    protected $settings;

    public function __construct() {
        $this->settings = Config::loadConfig();
    }

    public function isLoggedIn (): bool {
        return !empty($_SESSION["CURR_SESSION"]["ISLOGGEDIN"]);
    }

    public function roleRedirection (): void {
        $role = $_SESSION["CURR_SESSION"]["ACC_DATA"]["RoleId"] ?? null;
        switch ($role) {
            case $role == "ADVTEA":
                if (
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/adviserTeacher/settings" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/adviserTeacher/dashboard"
                ) break;
                header("Location: /views/adviserTeacher/dashboard");
                break;
            case $role == "SUBJTEA":
                if (
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/subjectTeacher/settings" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/subjectTeacher/assignments" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/subjectTeacher/dashboard"
                ) break;
                header("Location: /views/subjectTeacher/dashboard");
                break;
            case $role == "SUADM":
                if (
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/dashboard" || 
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/dashboard" || 
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/students" || 
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/addSubject" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/classManager" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/grades" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/studentApproval" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/fileManagement" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/settings" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/attendanceReport" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/superAdmin/leaveApplication"
                    
                ) break;
                header("Location: /views/superAdmin/dashboard");
                break;
            case $role == "STU":
                if (
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/student/dashboard" || 
                    strtok($_SERVER["REQUEST_URI"], "?") === "/student/download" || 
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/student/studentInformations" || 
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/student/academics" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/student/tasks" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/student/grades" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/student/leaveApplications" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/student/fileManagement" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/student/attendance" ||
                    strtok($_SERVER["REQUEST_URI"], "?") === "/views/student/settings" 
                ) break;
                header("Location: /views/student/dashboard");
                break;
            case $role == "ADM":
                if (
                    $_SERVER["REQUEST_URI"] === "/administrator/dashboard" || 
                    $_SERVER["REQUEST_URI"] === "/administrator/settings" ||
                    $_SERVER["REQUEST_URI"] === "/administrator/accManager" ||
                    $_SERVER["REQUEST_URI"] === "/administrator/classManager" ||
                    $_SERVER["REQUEST_URI"] === "/administrator/acadClassManager" ||
                    $_SERVER["REQUEST_URI"] === "/administrator/officialDocs" ||
                    $_SERVER["REQUEST_URI"] === "/administrator/enrollment" ||
                    $_SERVER["REQUEST_URI"] === "/administrator/fileManager" 
                ) break;
                header("Location: /administrator/dashboard");
                break;
        }
    }

    public function alert (string $type) {
        die(json_encode($this->settings["SYS_ERR_MSG"][$type]));
    }

    public function filterString (string $string): string {
        return htmlspecialchars(stripslashes($string));
    }

    public function hashPassword (string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function filterEmail (string $email): string {
        return filter_var($email, FILTER_SANITIZE_EMAIL) ?? "";
    }

    public function validateGender (string $genderId, ?bool $sendErrMessage = null): bool {
        $db = new Database();
        $genders = $db->setBindedExecution("SELECT gender_id FROM user_genders;")->fetchAll();
        $genderIds = [];
        foreach($genders as $genderKey) { $genderIds[] = $genderKey["gender_id"]; }
        $is = in_array($genderId, $genderIds, true);
        if (!$is && $sendErrMessage) {
            die(json_encode($genderIds));
        } 
        return $is;
    }

    public function isInteger (mixed $integer, ?bool $sendErrMessage = true): bool {
        $isInteger = filter_var($integer, FILTER_VALIDATE_INT);
        if (!$isInteger && $sendErrMessage) {
            $this->alert("not_an_integer");
        }
        return $isInteger !== false;
    }

    public function validateRole (string $roleId, ?bool $sendErrMessage = null): bool {
        $db = new Database();
        $roles = $db->setBindedExecution("SELECT * FROM user_roles;")->fetchAll();
        $roleIds = [];
        foreach ($roles as $roleId) { $roleIds[] = $roleId["role_id"];}
        $is = in_array($roleId, $roleIds, true);
        if (!empty($is) && $sendErrMessage) {
            die(json_encode($this->alert("role_err")));
        } 
        return $is;
    }

    public function validateEmailFormat(string $email, ?bool $sendErrMessage = null): bool {
        $domain = substr(strrchr($email,"@"),1);
        if (!filter_var($email,FILTER_VALIDATE_EMAIL) || strtolower($domain) !== 'gmail.com' && $sendErrMessage) {
            die(json_encode($this->alert("INCOR_EMAIL_FORMAT")));
        };
        if (!filter_var($email,FILTER_VALIDATE_EMAIL) || strtolower($domain) !== 'gmail.com') return false;
        return true;
    } public function validateMobileNumber (string $mobileNumeber, ?bool $sendErrMessage = null): bool {
        if (strlen($mobileNumeber) < 11 && $sendErrMessage) {
            die(json_encode($this->alert("incorrect_mobile_number")));
        }
        return $mobileNumeber < 11;
    }

    public function isEmailAddressAlreadyUsed (string $emailAddress, ?bool $sendErrMessage = null): bool {
        $db = new Database();
        $find = $db->setBindedExecution("SELECT * FROM user_accounts WHERE user_email_address = :user_email_address;", ["user_email_address" => $emailAddress])->fetchAll();
        if (!empty($find) && $sendErrMessage) {
            $this->alert("already_used_account");
        }
        return !empty($find);
    }

    public function getUniqueId (int $randomBytes = 8): string {
        return bin2hex(random_bytes($randomBytes));
    }

    public function isMobileNumberAlreadyUsed (string $mobileNumber, ?bool $sendErrMessage = null): bool {
        $db = new Database();
        $find = $db->setBindedExecution("SELECT * FROM user_accounts WHERE user_mobile_number = :user_mobile_number;", ["user_mobile_number" => $mobileNumber])->fetchAll();
        if (!empty($find) && $sendErrMessage) {
            die(json_encode($this->alert("already_used_account")));
        }
        return !empty($find);
    }

    public function getTimeDiff(string $previous): array {
        $dateTimeZone = new DateTimeZone("Asia/Taipei");
        $pastDate = new DateTime(
            $previous,
            $dateTimeZone
        );
        $currentDate = new DateTime();
        $gap = $currentDate->diff($pastDate);
        $timeFormatArray = [];

        $year = $gap->y;
        $month = $gap->m;
        $day = $gap->d;
        $hour = $gap->h;
        $minute = $gap->i;
        $second = $gap->s;

        if (!empty($year)) $year > 1 ? $timeFormatArray["yr"] = "{$year} years" : $timeFormatArray["yr"] = "{$year} year";
        if (!empty($month)) $month > 1 ? $timeFormatArray["mo"] = "{$month} months" : $timeFormatArray["mo"] = "{$month} month";
        if (!empty($month)) $day > 1 ? $timeFormatArray["da"] = "{$day} days" : $timeFormatArray["da"] = "{$year} day";
        if (!empty($hour)) $hour > 1 ? $timeFormatArray["hr"] = "{$hour} hours" : $timeFormatArray["hr"] = "{$hour} hour";
        if (!empty($minute)) $minute > 1 ? $timeFormatArray["min"] = "{$minute} minutes" : $timeFormatArray["min"] = "{$minute} minute";
        if (!empty($second)) $second > 1 ? $timeFormatArray["sec"] = "{$second} seconds" : $timeFormatArray["sec"] = "{$second} second";
        return $timeFormatArray;
    }

    public function getModifiedTime(string $currentTime): string | null {
        $timeZone = new DateTimeZone("Asia/Taipei");
        $time = new DateTime($currentTime, $timeZone);
        $timeFormat = $time->format("F j, Y");
        return $timeFormat ?? null;
    }

    public function getTimeModif (string $time): string | null {
        $formatedTime = date("h:i A", strtotime($time));
        return $formatedTime;
    }

    public function getCurrentTime($timeZone = "Asia/Taipei"): array
    {
        $dTimeZone = new DateTimeZone($timeZone);
        $taskCompleted = new DateTime("now", $dTimeZone);
        $now1 = $taskCompleted->format("Y-m-d h-i-s");
        $now2 = $taskCompleted->format("Y-m-d H:i:s");
        return [$now1, $now2];
    }

    public function spillJSON(): array
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    public function validateInboxTitle (string $inboxTitleId, bool $sendErrMessage): bool {
        $query = "SELECT * FROM inbox_custom_titles WHERE inbox_title_id = :inbox_title_id;";
        $db = new Database();
        $fetch = $db->setBindedExecution($query, ["inbox_title_id" => $inboxTitleId])->fetchAll();
        if (empty($fetch && $sendErrMessage)) {
            die($this->alert("unknown_inbox_title"));
        }
        return !empty($fetch);
    }

    public function validateInboxPriorityId(string $inboxPriorityId, bool $sendErrMessage) {
        $query = "SELECT * FROM inbox_priority_levels WHERE inbox_priority_id = :inbox_priority_id;";
        $db = new Database();
        $fetch = $db->setBindedExecution($query, ["inbox_priority_id" => $inboxPriorityId])->fetchAll();
        if (empty($fetch) && $sendErrMessage) {
            die($this->alert("unknown_inbox_priority"));
        }
        return !empty($fetch);
    }




    ############################################################
    ####                                                    ####
    ####                    NEW FUNCTIONS                   ####
    ####                                                    ####
    ############################################################

    public function isAllowedAccess (): bool {
        if (!$this->settings["APP_ENABLE"]) {
            include __DIR__ . "/views/err/system.disabled.php";
            die();
        }
        return true;
    }
    
    public function genNonceToken (int $byteLength = 20): string {
        $nonce = base64_encode(random_bytes($byteLength));
        return $nonce;
    }

    public function activateCSP (string $nonce): void {
        header("Content-Security-Policy: script-src 'self' 'nonce-$nonce' 'strict-dynamic';");
    }


    ## MUST BE CONTINUED FOR AUTOMATIC LOGIN VIA GOOGLE ACCOUNT
    ## to continue development, go to Google.cloud.console or ChatGPT
    public function AuthGoogleLogin () {
        $client = new Google_Client();
        $client->setClientId($this->settings["GOOGLE_CLIENT_ID"]);
        $client->setClientSecret($this->settings["GOOGLE_CLIENT_SECRET"]);
        $client->setRedirectUri($this->settings["GOOGLE_REDIRECT_URI"]);
        $client->addScope("email");
        $client->addScope("profile");

        if (isset($_GET["code"])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);
            
            if (!isset($token["error"])) {
                $client->setAccessToken($token["access_token"]);

                $oauth = new Google_Service($client);
            }
        }
    }

    public function isPasswordsMatch (string $pws_1, string $pws_2, bool $alertMessage = true): bool {
        if ($pws_1 !== $pws_2 && $alertMessage)  $this->alert("PWD_NOMATCH");
        return $pws_1 === $pws_2;
    }

    public function isAnyColumnEmpty(array $data, bool $alertMessage = true): bool {
        $isEmpty = false;
        foreach ($data as $d) {
            if (empty($d)) {
                $isEmpty = true;
                break;
            }
        }
        if ($alertMessage && $isEmpty) {
            $this->alert("INCOMPLETE_DATA");
        }
        return $isEmpty;
    }

    public function verifyPassword (string $hashedPassword, string $password, bool $alert = true): bool {
        $verification = password_verify($password, $hashedPassword);
        if (!$verification && $alert) {
            $this->alert("INVALID_PWD");
        }
        return $verification;
    }

    public function isAlreadyLoggedIn (bool $alert = true): bool {
        $isLogedIn = $_SESSION["CURR_SESSION"]["ISLOGGEDIN"] ?? null;   
        if ($isLogedIn && $alert) {
            $this->alert("ALREADY_LOGGED");
        }
        return boolval($isLogedIn);
    }

    public function isAlreadyLoggedOut (bool $alert = true): bool {
        $isLogedIn = $_SESSION["CURR_SESSION"]["ISLOGGEDIN"] ?? null;   
        if (!$isLogedIn && $alert) {
            $this->alert("ALREADY_LOGGED_OUT");
        }
        return boolval(!$isLogedIn);
    }

    public function isTimeNotAllowedUpdatePwd (string $lastPasswordUpdate, bool $alert = true) {

    }

    public function getDateTimeFormat (string $time): string {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $time); 
        $time = $date->format('F j, Y g:i A');
        return $time;
    }
}
