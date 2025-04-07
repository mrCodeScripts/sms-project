<?php

declare(strict_types=1);

class AdministratorModel extends Database {
    protected $middleware;

    public function __construct($middleware) {
        $this->middleware = $middleware;
    }

    public function getAllStudentEnr (string $statId): array {
        $query = "SELECT 
            rs.student_learning_ref_number as lrn,
            rs.student_fname as firstName,
            rs.student_lname as lastName,
            rs.student_registered_on as registeredOn,
            rs.student_registration_approved_on as approvedOn,
            rs.student_rejected_reg_on as rejectedOn,
            us.user_mobile_number as mobileNumber,
            ua.gender_name as genderName,
            ua.gender_description as genderDescription,
            rst.registration_status_name as registrationStatusName,
            rst.registration_status_description as registrationStatusDescription
            FROM registered_students rs
            JOIN user_accounts us ON rs.student_account_id = us.user_uniqueid
            JOIN user_genders ua ON ua.gender_id = rs.student_gender
            JOIN registration_status rst ON rst.registration_status_id = rs.student_registration_status
            WHERE rs.student_registration_status = :stat_id;
        ";
        $fetch = $this->setBindedExecution($query, ["stat_id" => $statId])->fetchAll();
        return $fetch ?? null;
    }

    ################# NEW METHODS ########################
    public function getAllBuildings (bool $alert = true) {
        $query = "SELECT * FROM building;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_BDLG");
        }
        return $exec;
    }
    public function getAllFloors (bool $alert = true) {
        $query = "SELECT 
        f.floor_name as FloorName,
        f.floor_id as FloorId,
        f.floor_description as FloorDesc,
        b.building_name as BuldingName,
        b.building_id as BuildingId,
        b.building_description as BuildingDesc
        FROM floor f
        JOIN building b ON b.building_id = f.floor_building;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_FLOORS");
        }
        return $exec;
    }
    public function getAllRooms (bool $alert = true) {
        $query = "SELECT 
        b.building_name as BuildingName,
        b.building_id as BuildingId,
        b.building_description as BuildingDesc,
        r.room_id as RoomId,
        r.room_description as RoomDesc,
        f.floor_name as FloorName,
        f.floor_id as FloorId,
        f.floor_description as FloorDesc,
        b.building_name as BuldingName,
        b.building_id as BuildingId,
        b.building_description as BuildingDesc
        FROM room r
        JOIN floor f ON f.floor_id = r.room_floor
        JOIN building b ON b.building_id = f.floor_building
        WHERE f.floor_id = r.room_floor;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        if (empty($exec) && $alert) {
            $this->middleware->alert("NO_FLOORS");
        }
        return $exec;
    }
    ######################################################

    public function getAllTeacherEnr (string $statId): array {
        $query = "SELECT 
            rt.teacher_professional_id as professionalId,
            rt.teacher_fname as firstName,
            rt.teacher_lname as lastName,
            rt.teacher_registered_on as registeredOn,
            rt.teacher_registration_approved_on as approvedOn,
            rt.teacher_rejected_reg_on as rejectedOn,
            us.user_mobile_number as mobileNumber,
            ua.gender_name as genderName,
            ua.gender_description as genderDescription,
            rs.registration_status_name as registrationStatusName,
            rs.registration_status_description as registrationStatusDescription
            FROM registered_teachers rt
            JOIN user_accounts us ON rt.teacher_account_id = us.user_uniqueid
            JOIN user_genders ua ON ua.gender_id = rt.teacher_gender
            JOIN registration_status rs ON rs.registration_status_id = rt.teacher_registration_status
            WHERE rt.teacher_registration_status = :stat_id;
        ";
        $fetch = $this->setBindedExecution($query, ["stat_id" => $statId])->fetchAll();
        return $fetch ?? null;
    }


    ################################### BEING USED #####################################
    public function createNewBuilding (array $data, ?bool $alertMsg = true): bool {
        $query = "INSERT INTO building (
        building_id, 
        building_name,
        building_description
        ) VALUES (
        :building_id, 
        :building_name,
        :building_description);";

        $exec = $this->setBindedExecution($query, $data);

        if ($alertMsg) {
            $exec ?  $this->middleware->alert("SUCC_BLDG_CREATE") 
            : $this->middleware->alert("FAILED_BLDG_CREATE");
        }
        return $exec;
    }

    public function createNewFloor(array $data, bool $alert = true) {
        $query = "INSERT INTO floor (floor_id,
        floor_name, floor_building, floor_description) VALUES (
        :floor_id, :floor_name, :floor_building, :floor_description);";
        $exec = $this->setBindedExecution($query, $data);
        if ($alert) {
            $exec ? $this->middleware->alert("SUCC_FLOOR_CREATION") 
            : $this->middleware->alert("FAILED_FLOOR_CREATION");
        }
    } 

    public function isBuildingExist (string $buildingId, ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM building WHERE building_id = :building_id;";
        $exec = $this->setBindedExecution($query, ["building_id" => $buildingId])->fetchAll();

        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("BLDG_NOEXIST");
        }

        return !empty($exec);
    }

    public function isFloorNameAlreadyUsed (string $floorName, string $buildingId, 
    ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM floor WHERE floor_name = :floor_name 
        AND floor_building = :building_id;";

        $exec = $this->setBindedExecution($query, ["floor_name" => $floorName, 
        "building_id" => $buildingId])->fetchAll();

        if (!empty($exec) && $alertMsg) {
            $this->middleware->alert("FLOOR_NM_ALREXIST");
        }
        return !empty($exec);
    }

    public function createRoom (array $data, ?bool $alertMsg = true): bool {
        $query = "INSERT INTO room (room_id, room_floor, room_description) 
        VALUES (:room_id, :room_floor, :room_description);";
        $exec = $this->setBindedExecution($query, $data);
        if ($alertMsg) { 
            $exec ? $this->middleware->alert("SUCC_ROOM_CREATION") 
            : $this->middleware->alert("FAILED_ROOM_CREATION");
        }
        return $exec;
    }


    ####################################################################################















    // public function getAllApprovedStudentEnr (): array { }
    // public function getAllRejectedStudentEnr (): array { }
    // public function getAllPendingTeacherReg (): array { }
    // public function getAllApprovedTeacherReg (): array { }
    // public function getAllRejectedTeacherReg (): array { }

    public function approveTeacher (string $professionalId): void {
        [$now1, $now2] = $this->middleware->getCurrentTime();
        $query = "UPDATE registered_teachers SET teacher_registration_status = 'REG', teacher_registration_approved_on = :dt 
        WHERE teacher_professional_id = :prof_id;";
        $exec = $this->setBindedExecution($query, ["prof_id" => $professionalId, "dt" => $now2]);
        $exec ? $this->middleware->alert("successful_approved_data") 
        : $this->middleware->alert("failed_approved_tea");
    }

    public function rejectTeacher (string $professionalId): void {
        [$now1, $now2] = $this->middleware->getCurrentTime();
        $query = "UPDATE registered_teachers SET teacher_registration_status = 'REJ', teacher_rejected_reg_on = :dt 
        WHERE teacher_professional_id = :prof_id;";
        $exec = $this->setBindedExecution($query, ["prof_id" => $professionalId, "dt" => $now2]);
        $exec ? $this->middleware->alert("successful_rejected_data") 
        : $this->middleware->alert("failed_rejected_tea");
    }

    public function approveStudent (string $lrn): void {
        [$now1, $now2] = $this->middleware->getCurrentTime();
        $query = "UPDATE registered_students SET student_registration_status = 'REG', student_registration_approved_on = :dt 
        WHERE student_learning_ref_number = :lrn;";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn, "dt" => $now2]);
        $exec ? $this->middleware->alert("successful_approved_data") 
        : $this->middleware->alert("failed_approved_tea");
    }

    public function rejectStudent (string $lrn): void {
        [$now1, $now2] = $this->middleware->getCurrentTime();
        $query = "UPDATE registered_students SET student_registration_status = 'REJ', student_rejected_reg_on = :dt 
        WHERE student_learning_ref_number = :prof_id;";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn, "dt" => $now2]);
        $exec ? $this->middleware->alert("successful_rejected_data") 
        : $this->middleware->alert("failed_rejected_tea");
    }

    public function isGradeLevelNameExist (string $gradeLevelName, ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM grade_levels WHERE grade_level_name = :grade_level_name;";
        $fetch = $this->setBindedExecution($query, ["grade_level_name" => $gradeLevelName]);
        if (!empty($fetch) && $alertMsg) {
            $this->middleware->alert("grade_level_name_already_exist");
        }
        return !empty($fetch);
    }

    public function createNewGradeLevel (string $gradeLevelName, string $gradeLevelDesc) {
        $query = "INSERT INTO grade_levels (grade_level_id, grade_level_name, grade_level_description) 
        VALUES (:grade_level_id, :grade_level_name, :grade_level_description);";       
        $exec = $this->setBindedExecution($query, [
            "grade_level_id" => $this->middleware->getUniqueId(),
            "grade_level_name" => $this->middleware->filterString($gradeLevelName),
            "grade_level_description" => $this->middleware->filterString($gradeLevelDesc)
        ]);
        $exec ? $this->middleware->alert("successful_grade_level_creation") 
        : $this->middleware->alert("failed_grade_level_creation");
    }

    public function getAllRegisteredTeachers (?bool $alertMsg = true): array {
        $query = "SELECT * FROM registered_teachers WHERE teacher_registration_status = 'REG';";
        $exec = $this->setBindedExecution($query)->fetchAll() ?? null;
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("no_available_tea");
        }
        return $exec;
    }
    
    public function  getAllSyLevels (?bool $alertMsg = true): array {
        $query = "SELECT * FROM sy_levels;";
        $exec = $this->setBindedExecution($query)->fetchAll() ?? null;
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("no_school_year");
        }
        return $exec;
    }

    public function getAllGLvls (?bool $alertMsg = true): array {
        $query = "SELECT * FROM grade_levels;";
        $exec = $this->setBindedExecution($query)->fetchAll() ?? null;
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("no_grade_levels");
        }
        return $exec;
    }

    public function getAllStrnd (?bool $alertMsg = true): array {
        $query = "SELECT * FROM strand;";
        $exec = $this->setBindedExecution($query)->fetchAll() ?? null;
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("no_strn");
        }
        return $exec;
    }

    public function getAllRoom (?bool $alertMsg = true): array {
        $query = "SELECT * FROM room;";
        $exec = $this->setBindedExecution($query)->fetchAll() ?? null;
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("no_rooms");
        }
        return $exec;
    }

    public function getRoom (string $roomId, ?bool $alertMsg = true): array {
        $query = "SELECT * FROM room_id = :room_id;";
        $exec = $this->setBindedExecution($query, ["room_id" => $roomId])->fetchAll();

        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("no_rooms");
        }

        return $exec;
    }

    public function isTeacherExist (string $userId, ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM registered_teachers 
        WHERE teacher_professional_id = :teacher_professional_id;";
        $exec = $this->setBindedExecution($query, [
            "teacher_professional_id" => $userId,
        ])->fetchAll();
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("teacher_noexist");
        }
        return !empty($exec);
    }

    public function isSyLevelExist (string $syLevelId, ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM sy_levels WHERE sy_level_id = :sy_level_id;";
        $exec = $this->setBindedExecution($query, [
            "sy_level_id" => $syLevelId,
        ])->fetchAll();
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("sy_level_noexist");
        }
        return !empty($exec);
    }

    public function isGradeLevelExist (string $gradeLvlId, ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM grade_levels WHERE grade_level_id = :grade_level_id;";
        $exec = $this->setBindedExecution($query, [
            "grade_level_id" => $gradeLvlId
        ])->fetchAll();
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("grade_level_noexist");
        }
        return !empty($exec);
    }

    public function isStrandExist (string $classStrandId, ?bool $alertMsg = true): bool {
        $query  = "SELECT * FROM strand WHERE strand_id = :strand_id;";
        $exec = $this->setBindedExecution($query, [
            "strand_id" => $classStrandId
        ])->fetchAll();
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("strand_noexist");
        }
        return !empty($exec);
    }

    public function isSectionNameAlreadyUsed (string $classSectionName, ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM class WHERE class_section_name = :class_section_name;";
        $exec = $this->setBindedExecution($query, [
            "class_section_name" => $classSectionName
        ])->fetchAll();
        if (!empty($exec) && $alertMsg) {
            $this->middleware->alert("class_section_name_already_exist");
        }
        return !empty($exec);
    }

    public function isRoomExist (string $roomId, ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM room WHERE room_id = :room_id;";
        $exec = $this->setBindedExecution($query, [
            "room_id" => $roomId
        ])->fetchAll();
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("room_noexist");
        }
        return !empty($exec);
    }

    public function schoolClassCreation (string $classSectionName, string $classAdvisorId, 
    string $classSyId, string $classGradeLevelId, string $classStrandId, $classRoomId, bool $alertMsg = true): bool {
        $query = "INSERT INTO class (class_id, 
        class_section_name, 
        class_teacher_advisory_id, 
        class_sy_id, 
        class_grade_level_id, 
        class_strand_id,
        class_room_id) VALUES (
        :class_id, 
        :class_section_name, 
        :class_teacher_advisory_id, 
        :class_sy_id, 
        :class_grade_level_id, 
        :class_strand_id,
        :class_room_id);";
        $exec = $this->setBindedExecution($query, [
            "class_id" => $this->middleware->getUniqueId(),
            "class_section_name" => $this->middleware->filterString($classSectionName),
            "class_teacher_advisory_id" => $classAdvisorId,
            "class_sy_id" => $classSyId,
            "class_grade_level_id" => $classGradeLevelId,
            "class_strand_id" => $classStrandId,
            "class_room_id" => $classRoomId
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_class_creation")
            : $this->middleware->alert("failed_class_creation");
        }

        return $exec;
    }

    public function fetchAllFloors (?bool $alertMsg = true): array {
        $query1 = "SELECT * FROM floor;";
        $exec = $this->setBindedExecution($query1)->fetchAll();
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("");
        }
        return $exec;   
    }

    public function isBuildingNameAlreadyUsed (string $buildingName, ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM building WHERE building_name = :building_name;";
        $exec = $this->setBindedExecution($query, [
            "building_name" => $buildingName
        ])->fetchAll();
        if (!empty($exec) && $alertMsg) { 
            $this->middleware->alert("BLDG_NAME_ALRUSED"); 
        }
        return !empty($exec);
    }


    public function updateBuildingName (string $buildingId, string $buildingName, 
    ?bool $alertMsg = true): bool {
        $query = "UPDATE building SET building_name = :building_name 
        WHERE building_id = :building_id;";
        $exec = $this->setBindedExecution($query, 
        ["building_name" => $buildingName, "building_id" => $buildingId]);

        if ($alertMsg) {
            $exec ?  $this->middleware->alert("successful_building_name_modif") 
            : $this->middleware->alert("failed_building_name_modif");
        }

        return $exec;
    }

    public function updateFloorName (string $floorId, string $buildingId, string $floorName, 
    ?bool $alertMsg = true): bool {
        $query = "UPDATE floor SET floor_name = :floor_name 
        WHERE building_id = :building_id and floor_id = :floor_id;";
        $exec = $this->setBindedExecution($query, [
            "floor_name" => $floorName,
            "floor_id" => $floorId, 
            "building_id" => $buildingId
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_floor_name_modif") 
            : $this->middleware->alert("failed_floor_name_modif");
        }

        return $exec;
    }
 


    public function updateFloorRoomMaxNum (int $floorRoomNum, string $floorId, 
    string $buildingId, ?bool $alertMsg = true): bool {
        $query2 = "UPDATE floor 
        SET floor_max_room_number = :floor_max_room_number 
        WHERE floor_id = :floor_id AND building_id = :building_id;";

        $exec = $this->setBindedExecution($query2, ["floor_max_room_number" => $floorRoomNum, 
        "floor_id" => $floorId, "building_id" => $buildingId]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("sucessful_floor_max_room_modif") 
            : $this->middleware->alert("failed_floor_max_room_modif");
        }

        return $exec;
    }

    public function updateFloorDesc (string $floorId, string $buildingId, 
    string $floorDescription, ?bool $alertMsg = true): bool {
        $floorDesc = $this->middleware->filterString($floorDescription);
        $query2 = "UPDATE floor 
        SET floor_description = :floor_description 
        WHERE floor_id = :floor_id AND building_id = :building_id;";
        $exec = $this->setBindedExecution($query2, ["floor_description" => $floorDesc, 
        "floor_id" => $floorId, "building_id" => $buildingId]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_floor_desc_modif") 
            : $this->middleware->alert("failed_floor_desc_modif");
        }

        return $exec;
    }

    public function isFloorFull (string $floorId, string $buildingId, ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM floor WHERE floor_id = :floor_id AND building_id = :building_id;";
        $exec = $this->setBindedExecution($query, ["floor_id" => $floorId, "building_id" => $buildingId])->fetchAll();

        $maxRoomNum = $exec[0]["floor_max_room_number"];
        $roomIsFull = $maxRoomNum <= sizeof($exec);

        if ($roomIsFull && $alertMsg) {
            $this->middleware->alert("floor_reached_maxrooms");
        }

        return $roomIsFull;
    }

    public function changeRoomDesc (string $roomId, string $roomDescription, 
    ?bool $alertMsg = true): bool {
        $fetch = $this->getRoom($roomId);
        if (empty($fetch[0]["room_description"]) && $alertMsg) {
            $this->middleware->alert("room_desc_notset"); 
        }

        $query2 = "UPDATE room 
        SET room_description = :room_description 
        WHERE room_id = :room_id;";
        $exec = $this->setBindedExecution($query2, ["room_description" => 
        $this->middleware->filterString($roomDescription), "room_id" => $roomId]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_room_desc_modif") 
            : $this->middleware->alert("failed_room_desc_modif");
        }

        return $exec;
    }

    public function isTrackExist (string $trackId, ?bool $alertMsg = true): bool {
        $query = "SELECT * FROM track WHERE track_id = :track_id;";
        $exec = $this->setBindedExecution($query, ["track_id" => $trackId])->fetchAll();

        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("track_noexist");
        }
        return !empty($exec);
    }

    public function isTrackNameAlreadyUsed (string $trackName, ?bool $alertMsg = true) {
        $query = "SELECT * FROM track WHERE track_name = :track_name;";
        $exec = $this->setBindedExecution($query, ["track_name" => $trackName])->fetchAll();

        if (!empty($exec) && $alertMsg) {
            $this->middleware->alert("track_name_already_used");
        }

        return !empty($exec);
    }

    public function updateTrackName (string $trackName, string $trackId, 
    ?bool $alertMsg = true): bool {
        $query = "UPDATE track SET track_name = :track_name WHERE track_id = :track_id;";
        $exec = $this->setBindedExecution($query, [
            "track_name" => $this->middleware->filterString($trackName), 
            "track_id" => $trackId
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_track_name_modif") 
            : $this->middleware->alert("failed_track_name_modif");
        }

        return $exec;
    }

    public function createNewTrack (string $trackId, string $trackName, 
    string $trackDescription, ?bool $alertMsg = true) {
        $query = "INSERT INTO track (track_id, track_name, track_description) 
        VALUES (:track_id, :track_name, :track_description);";
        $exec = $this->setBindedExecution($query, [
            "track_id" => $trackId,
            "track_name" => $this->middleware->filterString($trackName),
            "track_description" => $this->middleware->filterString($trackDescription),
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_track_creation") 
            : $this->middleware->alert("failed_track_creation"); 
        }

        return $exec;
    }

    public function updateTrackDesc (string $trackId, string $trackDescription, ?bool $alertMsg = true): bool {
        $query = "UPDATE track SET track_description = :track_desc WHERE track_id = :track_id;";
        $exec = $this->setBindedExecution($query, [
            "track_desc" => $this->middleware->filterString($trackDescription), 
            "track_id" => $trackId
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_track_name_modif") 
            : $this->middleware->alert("failed_track_name_modfi");
        }
        
        return $exec;
    }

    public function isStrandNameAlreadyUsed (string $strandName, ?bool $alertMsg = true): bool {
        $query = "SELECT strand_name FROM strand WHERE strand_name = :strand_name;";
        $exec = $this->setBindedExecution($query, ["strand_name" => $strandName])->fetchAll();

        if (!empty($exec) && $alertMsg) {
            $this->middleware->alert("strand_name_already_used");
        }
        
        return !empty($exec);
    }

    public function createNewStrand (string $strandId, string $strandName,
    string $strandDesc, bool $alertMsg = true): bool {
        $query = "INSERT INTO track (track_id, 
        track_name, track_description) 
        VALUES (:track_id, :track_name, 
        :track_description);";
        $exec = $this->setBindedExecution($query, [
            "strand_id" => $strandId,
            "strand_name" => $this->middleware->filterString($strandName),
            "strand_description" => $this->middleware->filterString($strandDesc),
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_strand_creation") 
            : $this->middleware->alert("failed_strand_creation");
        }

        return $exec;
    }

    public function updateStrandName (string $strandName, string $strandId, bool $alertMsg = true): bool {
        $query = "UPDATE strand 
        SET strand_name = :strand_name 
        WHERE strand_id = :strand_id;";
        $exec = $this->setBindedExecution($query, [
            "strand_name" => $this->middleware->filterString($strandName),
            "strand_id" => $strandId
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_strand_name_modif") 
            : $this->middleware->alert("failed_strand_name_modif");
        }

        return $exec;
    }

    public function updateStrandDesc (string $strandId, string $strandDesc, bool $alertMsg = true): bool {
        $query = "UPDATE strand SET strand_description = :strand_description WHERE strand_id = :strand_id;";
        $exec = $this->setBindedExecution($query, ["strand_description" => 
        $this->middleware->filterString($strandDesc), "strand_id" => $strandId]);

        if ($alertMsg) { 
            $exec ? $this->middleware->alert("successful_strand_desc_modif") 
            : $this->middleware->alert("failed_strand_desc_modif");
        }

        return $exec;
    }

    public function updateStrandTrack (string $newTrackId, string $strandId, bool $alertMsg = true): bool {
        $query = "UPDATE strand SET track_id = :new_track_id WHERE strand_id = :strand_id;";
        $exec = $this->setBindedExecution($query, [
            "new_track_id" => $newTrackId, 
            "strand_id" => $strandId
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_strand_track_modif") 
            : $this->middleware->alert("failed_strand_track_modif");
        }

        return $exec;
    }

    public function updateGradeLevelName (string $gradeLevelName, string $gradeLevelId, bool $alertMsg = false): bool {
        $query = "UPDATE grade_level SET grade_level_name = :grade_level_name WHERE grade_level_id = :grade_level_id;";

        $exec = $this->setBindedExecution($query, [ "grade_level_id" => $gradeLevelId, "grade_level_name" => $gradeLevelName]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_grade_level_name_modif") 
            : $this->middleware->alert("failed_grade_level_name_modif");
        }

        return $exec;
    }

    public function updateGradeLevelDesc (string $gradeLevelId, string $gradeLevelDesc, bool $alertMsg = true): bool {
        $query2 = "UPDATE grade_level SET grade_level_desc = :grade_level_desc WHERE grade_level_id = :grade_level-id;";

        $exec = $this->setBindedExecution($query2, [
            "grade_level_id" => $gradeLevelId,
            "grade_level_description" => $gradeLevelDesc,
        ]);
         
        if ($exec) {
            $exec ? $this->middleware->alert("successful_grade_level_desc_modif") 
            : $this->middleware->alert("failed_grade_level_desc_modif");
        }

        return $exec;
    }

    public function createNewSyLevel(string $syLevelId, string $syLevelName, string $syLevelDesc, bool $alertMsg = true): bool {
        $query3 = "INSERT INTO sy_levels (sy_level_id, sy_level_name, sy_level_description) VALUES 
        (:sy_level_id, :sy_level_name, :sy_level_description)";
        $exec = $this->setBindedExecution($query3, [
            "sy_level_id" => $syLevelId,
            "sy_level_name" => $this->middleware->filterString($syLevelName),
            "sy_level_description" => $this->middleware->filterString($syLevelDesc)
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_sy_level_creation") 
            : $this->middleware->alert("failed_sy_level_creation");
        }

        return $alertMsg;
    }

    public function isClassExist (string $classId, bool $alertMsg = true): bool {
        $query = "SELECT * FROM class WHERE class_id = :class_id;"; 
        $exec = $this->setBindedExecution($query, ["class_id" => $classId])->fetchAll();

        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("class_noexist");
        }

        return !empty($exec);
    }

    public function isClassSubjectExist (string $subjectId, bool $alertMsg = true): bool {
        $query = "SELECT * FROM class_subjects WHERE class_subject_id = :class_subject_id;";
        $exec = $this->setBindedExecution($query, ["class_subject_id" => $subjectId])->fetchAll();

        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("class_subject_noexist");
        }

        return !empty($exec);
    }

    public function createNewSubjectSched (string $classSchedStart, string $classSchedEnd, string $classSchedClassId, string $classSchedSubjectId, string $classSchedSubjectTeacherId, array $selectedDays, string $classId, bool $alertMsg = true): bool  {

        


        $query1 = "INSERT INTO class_schedules (
        class_sched_id, 
        class_sched_start, 
        class_sched_end, 
        class_sched_class_id, 
        class_sched_subject_id, 
        class_sched_subject_teacher_id
        ) VALUES (
        :class_sched_id, 
        :class_sched_start, 
        :class_sched_end, 
        :class_sched_class_id, 
        :class_sched_subject_id, 
        :class_sched_subject_teacher_id);";
        $exec1 = $this->setBindedExecution($query1, [
            "class_sched_id" => $classId,
            "class_sched_start" => $classSchedStart,
            "class_sched_end" => $classSchedEnd,
            "class_sched_class_id" => $classSchedClassId,
            "class_sched_subject_id" => $classSchedSubjectId,
            "class_sched_subject_teacher_id" => $classSchedSubjectTeacherId
        ]);

        foreach ($selectedDays as $da) {
            $query2 = "INSERT INTO class_schedule_alloc_day (class_schedule_id, class_schedule_day) VALUES (:class_schedule_id, :class_schedule_day);";
            $exec2 = $this->setBindedExecution($query2, [
                "class_schedule_day" => $da,
                "class_schedule_id" => $classId,
            ]);
        }
        
        if ($alertMsg) {
            $exec1 ? $this->middleware->alert("successful_class_sched_creation")
            : $this->middleware->alert("failed_class_sched_creation");
        }

        return $exec1;
    }

    public function getAllClassSubject (bool $alertMsg = true): array {
        $query = "SELECT * FROM class_subjects;";
        $exec = $this->setBindedExecution($query)->fetchAll();

        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("class_subject_noexist");
        }

        return $exec;
    }

    public function isSubtopicNameAlreadyUsed (string $subtopicName, bool $alertMsg = true): bool {
        $query = "SELECT * FROM class_subject_subtopic WHERE subtopic_name = :subtopic_name;";
        $exec = $this->setBindedExecution($query, ["subtopic_name" => $subtopicName])->fetchAll();

        if ($exec) {
            $this->middleware->alert("subtopic_name_already_used");
        }

        return !empty($exec);
    }

    public function createNewSubjectSubtopic (string $subtopicId, string $subtopicName, string $subtopicSubjectId, string $subtopicDescription, bool $alertMsg = true): bool {
        $query3 = "INSERT INTO class_subject_subtopic (
        subtopic_id, 
        subtopic_name, 
        subtopic_subject_id, 
        subtopic_description
        ) VALUES (
        :subtopic_id, 
        :subtopic_name, 
        :subtopic_subject_id, 
        :subtopic_description);";
        $exec = $this->setBindedExecution($query3, [
            "subtopic_id" => $subtopicId,
            "subtopic_name" => $this->middleware->filterString($subtopicName),
            "subtopic_subject_id" => $subtopicSubjectId,
            "subtopic_description" => $this->middleware->filterString($subtopicDescription)
        ]);

        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_class_subject_subtopic_creation")
            : $this->middleware->alert("failed_class_subject_subtopic_creation");
        }

        return $exec;
    }

    public function getEnrolledStudent (string $lrn, bool $alertMsg = true): array | null {
        $query = "SELECT * FROM registered_students WHERE student_learning_ref_number = :lrn;";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn])->fetchAll();
        if (empty($exec) && $alertMsg) {
            $this->middleware->alert("student_not_fully_reg");
        }
        return $exec;
    }

    public function isStudentVerified ($lrn, bool $alertMsg = true): bool {
        $fetch = $this->getEnrolledStudent($lrn, false);
        if (empty($fetch) && $alertMsg) {
            $this->middleware->alert("student_not_fully_reg");
        }
        return !empty($fetch);
    }

    public function addNewStudentToClass (string $lrn, string $classId, bool $alertMsg = true): bool {
        $query = "INSERT INTO class_students (class_student_lrn, class_id) VALUES (:lrn, :class);";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn, "class" => $classId]);
        if ($alertMsg) {
            $exec ? $this->middleware->alert("successful_student_addtoclass")
            : $this->middleware->alert("failed_student_addtoclass"); 
        }

        return $exec;
    }

    public function isStudentAlreadyInClass (string $lrn, bool $alertMsg = true): bool {
        $query = "SELECT * FROM class_students WHERE class_student_lrn = :lrn;";
        $exec = $this->setBindedExecution($query, ["lrn" => $lrn])->fetchAll();
        if (!empty($exec) && $alertMsg) {
            $this->middleware->alert("student_already_inclass");
        }
        return !empty($exec);
    }
    

    // public function 

    public function getAllClass (): array {
        // $query = "SELECT 
        // c.class_id as classId,
        // c.class_section_name as sectionName,
        // c.class_teacher_adivisory_id as advisorId,
        // s.strand_name as strandName,
        // s.strand_id as strandId,
        // trk.track_name as trackName,
        // trk.track_id as trackId,
        // rt.teacher_fname as advisorFname,
        // rt.teacher_lname as advisorLname,
        // rt.teacher_age as advisorAge,
        // rt.teacher_account_id as advisorAccountId,
        // sy.sy_level_name as schoolYear,
        // gl.grade_level_name as gradeLevel,
        // ug.gender_name as advisor.gender
        // FROM class;";

        $query = "SELECT * FROM class;";
        return $this->setBindedExecution($query)->fetchAll();
    }

    public function getAllTheDays (): array {
        $query = "SELECT * FROM class_schedule_days;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        return $exec;
    }

    public function isDaysExist (array $selectedDays, bool $alertMsg = true): bool {
        $query = "SELECT day_id from class_schedules_days;";
        $exec = $this->setBindedExecution($query)->fetchAll();
        $err = false;
        $daycol = [];
        foreach ($exec as $d) { 
            $daycol[] = $d["day_id"]; 
        }

        foreach ($selectedDays as $d) {
            if (!in_array($d, $daycol)) {
                $err = true;
                break;
            }
        }

        if ($err && $alertMsg) {
            $this->middleware->alert("inv_days_selected");
        }

        return $err;
    }
}
