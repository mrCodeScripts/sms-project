<?php

declare(strict_types=1);

class UserAccountModel extends Database {
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

    public function getAllLogRec (string $uuid, string $sorting, bool $alert = true): array {
        $query = "SELECT 
        lr.log_rec_id as LogID,
        lr.log_rec_time as LogTime,
        lt.log_type_name as LogType,
        lt.log_type_description as LogTypeDesc,
        ls.log_status_name as LogStatus,
        ls.log_status_description as LogStatusDesc 
        FROM log_records lr
        JOIN log_type lt ON lt.log_type_id = lr.log_rec_type
        JOIN log_status ls ON ls.log_status_id = lr.log_rec_status
        WHERE lr.log_rec_uuid = :uuid {$sorting};";

        $data = ["uuid" => $uuid];
        $exec = $this->setBindedExecution($query, $data)->fetchAll();

        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_LOG_REC");
        }

        return $exec;
    }

    public function getAllUserRoles (): array {
        $query = "SELECT * FROM user_roles;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        return $exec;
    }

    public function getAllGenders (): array {
        $query = "SELECT * FROM genders;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        return $exec;
    }

    public function getAccount () {}
    public function getPassword () {}
    public function isUserPasswordMatch () {}
    public function isUserAlreadyExist () {}

    public function getAllAccData (string $uuid, string $role, bool $alert = true): array {
        $query = "SELECT 
        ua.user_uuid as UUID,
        ua.user_fname as Firstname,
        ua.user_lname as Lastname,
        ua.user_email as Email,
        ua.user_pwd_last_changed as PasswordLastChanged,
        ua.user_created_on as AccountCreatedOn,
        g.gender_name as Gender,
        g.gender_description as GenderDesc,
        ur.role_id as RoleId,
        ur.role_name as UserRole 
        FROM user_accounts ua
        JOIN genders g ON g.gender_id = ua.user_gender
        JOIN user_roles ur ON ur.role_id = ua.user_role
        WHERE ua.user_uuid = :uuid AND ur.role_id = :role;"; 
        $exec = $this->setBindedExecution($query, ["uuid" => $uuid, "role" => $role])->fetchAll();

        if (empty($exec) && $alert) {
            $this->middleware->alert("ACC_NODATA");
        }

        return $exec;
    }

    public function findUser (string $email, string $role, bool $alert = true): array {
        $query = "SELECT * FROM user_accounts 
        WHERE user_email = :user_email AND user_role = :user_role;";

        $data = ["user_email" => $email, "user_role" => $role];
        $exec = $this->setBindedExecution($query, $data)->fetchAll();

        if (empty($exec) && $alert) {
            $this->middleware->alert("USER_NOEXIST");
        }

        return $exec;
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

    public function getMyPassword (string $uuid, bool $alert = true): string {
        $query = "SELECT user_pwd FROM user_accounts 
        WHERE user_uuid = :uuid;";
        $data = ["uuid" => $uuid];
        $exec = $this->setBindedExecution($query, $data)->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("ACC_NOPWD");
        }
        return $exec[0];
    }

    public function updatePassword (string $uuid, string $newPwd, 
    string $currentPwd, bool $alert = true) {
        $query = "UPDATE user_accounts SET user_pwd = :new_pwd 
        WHERE user_uuid = :uuid;";
        $data = [
            "new_pwd" => $newPwd,
            ":uuid" => $uuid,
        ];
        $exec = $this->setBindedExecution($query, $data);
        if ($alert) {
            $exec ? $this->middleware->alert("")
            : $this->middleware->alert("");
        }
    }

    public function getAllFloors (string $buildingId, bool $alert = true) {
        $query = "SELECT * FROM floor WHERE floor_building = :building;";
        $exec = $this->setBindedExecution($query, ["building" => $buildingId])->fetchAll();
        if ($alert && empty($exec)) $this->middleware->alert("BLDG_NOEXIST");
    }

    public function isGenderExist (string $genderId, bool $alert = true) {
        $query = "SELECT * FROM genders WHERE gender_id = :id;";
        $exec = $this->setBindedExecution($query, ["id" => $genderId])->fetchAll() ?? null;
        if (empty($exec) && $alert) {
            $this->middleware->alert("GENDER_NOEXIST");
        }
    }

    public function getAllMyFiles (string $uuid, bool $alert = true): array { 
        $query = "SELECT
        uf.file_id as FileId,
        uf.file_name as FileNames,
        uf.file_path as FilePath,
        uf.file_stored_name as FileStoredName,
        uf.file_type as File_type,
        uf.file_doc_type as DocType,
        uf.file_size as FileSize,
        uf.file_upload_date as UploadedOn
        FROM uploaded_files uf
        WHERE uf.file_uploaded_by = :uuid;";
        $exec = $this->setBindedExecution($query, ["uuid" => $uuid])->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_FILE_FETCHED");
        }
        return $exec;
    }

    public function getAllSubjectTypes (bool $alert = true): array {
        $query = "SELECT * FROM subject_types;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_SUBJ_TYPES");
        }
        return $exec;
    }

    public function getAllStrand (bool $alert = true): array {
        $query = "SELECT * FROM strand;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("STRAND_NOALLEXIST");
        }
        return $exec;
    }

    public function getAllRooms (bool $alert = true): array {
        $query = "SELECT * FROM room;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("ROOMS_NOEXIST");
        }
        return $exec;
    }

    public function getAllTeachers (bool $alert = true): array {
        $query = "SELECT * FROM registered_teachers WHERE teacher_registration_status = 'REG';";
        $exec = $this->setBindedExecution($query)->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("TEACHERS_NOEXIST");
        }
        return $exec;
    }

    public function getAllDays (bool $alert = true): array {
        $query = "SELECT * FROM days;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        if (empty($exec) && $alert){
            $this->middleware->alert("NO_DAYS_AVAILABLE");
        }
        return $exec;
    }
}
