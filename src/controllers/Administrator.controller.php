<?php
    
declare(strict_types=1);
class AdministratorController {
    protected $administratorClassModel;
    protected $middleware;
    protected $session;
    protected $fileManager;

    public function __construct (
        AdministratorModel $administratorClassModel, 
        Middleware $middleware, 
        Session $session, 
        FileManagerController $fileManager
    ) {
        $this->administratorClassModel = $administratorClassModel;
        $this->middleware = $middleware;
        $this->session = $session;
        $this->fileManager = $fileManager;
    }

    public function isAnAdministrator ($sendErrorMsg = true): bool {
        $role = $_SESSION["CURR_SESSION"]["ACC_DATA"]["RoleId"] ?? null;
        if ($role !== "ADM" && $sendErrorMsg) {
            $this->middleware->alert("NOT_AN_ADMIN");
        }
        return !empty($role);
    }

    public function addBuilding ():void {
        header("Content-Type: application/json");
        $data = [
            "building_id" => $_POST["building-id"] ??  null,
            "building_name" => $_POST["building-name"] ?? null,
            "building_description" => $_POST["building-description"] ?? "No Description" ?? null, 
        ];
        $this->middleware->isAnyColumnEmpty($data);
        $this->administratorClassModel->isBuildingNameAlreadyUsed($data["building_name"]);
        $this->administratorClassModel->createNewBuilding($data);
    }

    public function addFloor ():void {
        header("Content-Type: application/json");
        $data = [
            "floor_id" => $_POST["floor-id"] ?? null,
            "floor_name" => $_POST["floor-name"] ?? null,
            "floor_building" => $_POST["floor-building"] ?? null,
            "floor_description" => $_POST["floor-description"] ?? "No description",
        ];
        $this->middleware->isAnyColumnEmpty($data);
        $this->administratorClassModel->isBuildingExist($data["floor_building"]);
        $this->administratorClassModel->isFloorNameAlreadyUsed($data["floor_name"], $data["floor_building"]);
        $this->administratorClassModel->createNewFloor($data);
    }

    public function addRoom (): void {
        header("Content-Type: application/json");

        $data = [
            "room_id" => $_POST["room-id"] ?? null, 
            "room_floor" => $_POST["room-floor"] ?? null,
            "room_description" => $_POST["room-description"] ?? "No description",
        ];

        $this->middleware->isAnyColumnEmpty($data);
        $this->administratorClassModel->createRoom($data);
    }

    public function getAllStructure (): array {
        $data = [
            "buildings" => $this->administratorClassModel->getAllBuildings(),
            "floors" => $this->administratorClassModel->getAllFloors(),
            "rooms" => $this->administratorClassModel->getAllRooms(),
        ];
        return $data;
    }
}




























    /*
    public function getAllBuilding (): array {
        $query1 = "SELECT * FROM building;";
        $fetch = $this->setBindedExecution($query1)->fetchAll();
        return $fetch;
    }

    public function getAllFloor (): array {
        return $this->administratorClassModel->fetchAllFloors();
    }

    public function getFloors (bool $sendErroMsg = true): array {
        $JSON = $this->middleware->spillJSON();
        $buildingId = $JSON["buildingId"] ?? null;
        if (empty($buildingId)) $this->middleware->alert("incomplete_data");
        $query1 = "SELECT * FROM floor WHERE building_id = :building_id;"; 
        $fetch = $this->setBindedExecution($query1, ["building_id" => $buildingId])->fetchAll();
        if (empty($fetch) && $sendErroMsg) { $this->middleware->alert("floor_not_exist"); }
        return $fetch;
    }

    public function getAllClass (): array {
        return $this->administratorClassModel->getAllClass();
    }

    public function getAllRoom (): array {
        return $this->administratorClassModel->getAllRoom(false) ?? null;
    }

    public function getAllRegisteredTeachers (): array {
        return $this->administratorClassModel->getAllRegisteredTeachers(false) ?? null;
    }

    public function getAllClassSubject (): array {
        return $this->administratorClassModel->getAllClassSubject();
    }

    public function getAllSchoolYear (): array {
        return $this->administratorClassModel->getAllSyLevels(false) ?? null;
    }

    public function getAllGradeLevel (): array {
        return $this->administratorClassModel->getAllGLvls(false) ?? null;
    }

    public function getAllStrands (): array {
        return $this->administratorClassModel->getAllStrnd(false) ?? null; 
    }

    # GET ALL REGISTERED USERS
    public function getAllRegisteredUsers (): array {
        $timelimit = intval(Settings::settings()['system_session_cookie_lifetime']);
        [$time1, $time2] = $this->middleware->getCurrentTime();
        $timeRule = date("Y-m-d H:i:s", strtotime($time2) - $timelimit);
        $mainQuery = "SELECT * FROM user_accounts;";
        $mainQueryFetch = $this->setBindedExecution($mainQuery)->fetchAll();
        $newContainer = [];

        for ($i = 0; $i < sizeof($mainQueryFetch); $i++) {
            $query1 = "SELECT 
            ua.user_firstname as firstname,
            ua.user_lastname as lastname,
            ua.user_uniqueid as userId,
            r.role_name as userRole,
            l.log_time as timeLog,
            lt.log_type_name as logType
            FROM log_records l
            JOIN user_accounts ua ON ua.user_uniqueid = l.user_uniqueid
            JOIN log_type lt ON lt.log_type_id = l.log_type_id
            JOIN user_roles r ON r.role_id = ua.user_role_id
            WHERE ua.user_uniqueid = :user_uniqueid ORDER BY log_time DESC;";
            $query1Fetch = $this->setBindedExecution($query1, [
                "user_uniqueid" => $mainQueryFetch[$i]["user_uniqueid"]
            ])->fetchAll()[0];
            $newContainer[] = [
                ...$query1Fetch, 
                "isOnline" => ( $query1Fetch["timeLog"] >= $timeRule && 
                ($query1Fetch["logType"] == "Login" || $query1Fetch["logType"] == "Signup"))
            ];
        }
        return $newContainer;
    }

    ###########################################################################
    ####                                                                   ####
    ####                           NOT YET IMPLEMENTED                     ####
    ####                                                                   ####
    ###########################################################################

    ###########################################################################
    ####                                                                   ####
    ####                        UPDATE WITH JSON SPILLER                   ####
    ####                                                                   ####
    ###########################################################################

    # ADD BUILDINGS 
    # IMPLEMENTED
    
    # IMPLEMENTED
    public function modifBuildingName (): void {
        header("Content-Type: application/json");
        $buildingId = $_POST["building-id"] ?? null;
        $buildingName = $_POST["building-name"] ?? null;
        if (empty($buildingId) || empty($buildingName)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isBuildingExist($buildingId);
        $this->administratorClassModel->isBuildingNameAlreadyUsed($buildingName);
        $buildingName = $this->middleware->filterString($buildingName);
        $this->administratorClassModel->updateBuildingName($buildingId, $buildingName);
    }

    # ADD FLOORS
    # IMPLEMENTED 

    # IMPLEMENTED
    public function modifFloorName (): void { 
        header("Content-Type: application/json");
        $floorName = $_POST["floor-name"] ?? null;
        $floorId = $_POST["floor-id"] ?? null;
        $buildingId = $_POST["building-id"] ?? null;

        if (empty($floorName) || empty($floorId)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isFloorExist($floorId, $buildingId);
        $this->administratorClassModel->isFloorNameAlreadyUsed($floorName, $buildingId);
        $this->administratorClassModel->updateFloorName($floorId, $buildingId, $floorName);
    }

    public function modifFloorMaxRoomNumber (): void { 
        header("Content-Type: application/json");
        $buildingId = $_POST["building-id"] ?? null;
        $floorId = $_POST["floor_id"] ?? null;
        $floorRoomNum = $_POST["floor_room_number"] ?? null;

        if (empty($floorId) || empty($floorRoomNum)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isFloorExist($floorId, $buildingId);
        $this->administratorClassModel->updateFloorRoomMaxNum($floorRoomNum, $floorId, $buildingId);
    }

    public function modifFloorDesc (): void {
        header("Content-Type: application/json");
        $buildingId = $_POST["building_id"] ?? null;
        $floorId = $_POST["floor_id"] ?? null;
        $floorDescription = $_POST["floor_description"] ?? null;

        if (empty($floorId) || empty($floorDescription)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isFloorExist($floorId, $buildingId);
        $this->administratorClassModel->updateFloorDesc($floorId, $buildingId, $floorDescription);
    }

    # IMPLEMENTED
    public function addRoom (): void {
        header("Content-Type: application/json");
        $roomId = $_POST["room-id"] ?? null;
        $floorId = $_POST["floor-id"] ?? null;
        $roomDescription = $_POST["room-description"] ?? null;
        $buildingId = $_POST["building-id"] ?? null;

        if (empty($roomId) || empty($floorId) || empty($roomDescription)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isFloorExist($floorId, $buildingId);
        $this->administratorClassModel->isFloorFull($floorId, $buildingId);
        $this->administratorClassModel->createRoom($roomId, $floorId, $roomDescription);
    }
    
    // public function modifSwitchRoomId (): void {
    //     header("Content-Type: application/json");
    //     $room1 = $_POST["first_room_id"] ?? null;
    //     $room2 = $_POST["second_room_id"] ?? null;

    //     $query1 = "SELECT * FROM room WHERE room_id = :room_id;";

    //     $fetch1 = $this->setBindedExecution($query1, ["room_id" => $room1])->fetchAll();
    //     $fetch2 = $this->setBindedExecution($query1, ["room_id" => $room2])->fetchAll();

    //     if (empty($fetch1)) {
    //         $this->middleware->alert("first_selected_room_noexist");
    //     }

    //     if (empty($fetch2)) {
    //         $this->middleware->alert("second_selected_room_noexist");
    //     }

    //     $query2 = "UPDATE room SET room_id = 'xxx' WHERE room_id = :first_room_id;
    //     UPDATE room SET room_id = :first_room_id WHERE room_id = :second_room_id;
    //     UPDATE room SET room_id = :second_room_id WHERE room_id = 'xxx';";

    //     $exec = $this->setBindedExecution($query2, 
    //     ["first_room_id" => $fetch1[0]["room_id"], "second_room_id" => $fetch2[0]["room_id"]]);

    //     $exec ? $this->middleware->alert("successful_room_switch") 
    //     : $this->middleware->alert("failed_room_switch");
    // }

    public function modifRoomDesc (): void {
        header("Content-Type: application/json");
        $roomDesc = $_POST["room_description"] ?? null;
        $roomId = $_POST["room_id"];

        $query1 = "SELECT * FROM room WHERE room_id = :room_id;";
        $fetch1 = $this->setBindedExecution($query1, ["room_id" => $roomId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("room_noexist");
        }

        $this->administratorClassModel->isRoomExist($roomId);
        $this->administratorClassModel->changeRoomDesc($roomId, $roomDesc);
    }

    public function addTrack (): void {
        header("Content-Type: application/json");
        $trackId = $this->middleware->getUniqueId() ?? null;
        $trackName = $_POST["track-name"] ?? null;
        $trackDescription = $_POST["track_description"] ?? null;

        if (empty($trackId) || empty($trackName) || empty($trackDescription)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isTrackNameAlreadyUsed($trackName);
        $this->administratorClassModel->createNewTrack($trackId, $trackName, $trackDescription);
    }

    public function modifTrackName (): void {
        header("Content-Type: application/json");
        $trackName = $_POST["track-name"] ?? null;
        $trackId = $_POST["track-id"] ?? null;

        if (empty($trackName) || empty($trackId)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isTrackExist($trackId);
        $this->administratorClassModel->isTrackNameAlreadyUsed($trackName); 
        $this->administratorClassModel->updateTrackName($trackName, $trackId);
    }

    public function modifTrackDesc (): void {
        header("Content-Type: application/json");
        $trackDescription = $_POST["track-desc"] ?? null;
        $trackId = $_POST["track-id"] ?? null;

        if (empty($trackName) || empty($trackId)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isTrackExist($trackId);
        $this->administratorClassModel->updateTrackDesc($trackId, $trackDescription);
    }

    public function addStrand (): void {
        header("Content-Type: application/json");
        $strandId = $this->middleware->getUniqueId() ?? null;
        $trackId = $_POST["track-id"] ?? null;
        $strandName = $_POST["strand-name"] ?? null;

        if (empty($trackId) || empty($strandId) || empty($strandName) || empty($strandDesc)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isStrandNameAlreadyUsed($strandName);
        
    }

    public function modifStrandName (): void {
        header("Content-Type: application/json");
        $strandName = $_POST["strand_name"] ?? null;
        $strandId = $_POST["strand_id"] ?? null;

        if (empty($strandName) || empty($strandId)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isStrandExist($strandId);
        $this->administratorClassModel->isStrandNameAlreadyUsed($strandName);
        $this->administratorClassModel->updateStrandName($strandName, $strandId);
    }  

    public function modifStrandDesc (): void {
        header("Content-Type: application/json");
        $strandDesc = $_POST["strand-desc"] ?? null;
        $strandId = $_POST["strand-id"] ?? null;

        if (empty($strandDesc) || empty($strandId)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isStrandExist($strandId);
        $this->administratorClassModel->updateStrandDesc($strandId, $strandDesc);
    }

    public function modifStrandTrack (): void {
        header("Content-Type: application/json");
        $newTrackId = $_POST["track-id"] ?? null;
        $strandId = $_POST["strand-id"] ?? null;

        if (empty($newTrackId) || empty($strandId)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isStrandExist($strandId);
        $this->administratorClassModel->isTrackExist($newTrackId);
        $this->administratorClassModel->updateStrandTrack($newTrackId, $strandId);
    }

    ## GRADE LEVELS AND SCHOOL YEARS
    public function addGradeLevel (): void {
        header("Content-Type: application/json");
        $gradeLevelName = $_POST["grade-level-name"] ?? null;
        $gradeLevelDesc = $_POST["grade-level-description"] ?? null;

        if (empty($gradeLevelDesc) || empty($gradeLevelName)) {
            $this->middleware->alert("incomplete_data");
        }
        $this->administratorClassModel->isGradeLevelNameExist($gradeLevelName);
        $this->administratorClassModel->createNewGradeLevel($gradeLevelName, $gradeLevelDesc);
    }

    public function modifyGradeLevelName (): void {
        header("Content-Type: application/json");
        $gradeLevelName = $_POST["grade-name"] ?? null;
        $gradeLevelId = $_POST["grade-id"] ?? null;

        if (empty($gradeLevelName) || empty($gradeLevelDesc)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isGradeLevelExist($gradeLevelId);
        $this->administratorClassModel->isGradeLevelNameExist($gradeLevelName);
        $this->administratorClassModel->updateGradeLevelName($gradeLevelName, $gradeLevelId);
    }

    public function modifyGradeLevelDesc (): void {
        header("Content-Type: application/json");
        $gradeLevelDesc = $_POST["grade-level-description"] ?? null;
        $gradeLevelId = $_POST["grade-level-id"] ?? null;

        if (empty($gradeLevelId) || empty($gradeLevelDesc)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isGradeLevelExist($gradeLevelId);
        $this->administratorClassModel->updateGradeLevelDesc($gradeLevelId, $gradeLevelDesc);
    }

    public function addSyLevel (): void {
        header("Content-Type: application/json");
        $syLevelId = $this->middleware->getUniqueId() ?? null;
        $syLevelName = $_POST["sy-level-name"] ?? null;
        $syLevelDescription = $_POST["sy-level-description"] ?? null;

        if (empty($syLevelId) || empty($syLevelName) || empty($syLevelDescription)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isSyLevelExist($syLevelId);
        $this->administratorClassModel->createNewSyLevel($syLevelId, $syLevelName, $syLevelDescription);
    }

    public function modifSyLevelName (): void {
        header("Content-Type: application/json");
        $syLevelName = $_POST["sy-level-name"] ?? null;
        $syLevelId = $_POST["sy-level-id"] ?? null;

        if (empty($syLevelName) || empty($syLevelId)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isSyLevelExist($syLevelId);

        $query2 = "SELECT * FROM sy_levels WHERE sy_level_name = :sy_level_name;";
        $fetch2 = $this->setBindedExecution($query2, ["sy_level_name" => $syLevelName])->fetchAll();

        if (!empty($fetch2)) {
            $this->middleware->alert("sy_level_name_already_used");
        }

        $query3 = "UPDATE sy_levels SET sy_level_name = :sy_level_name WHERE sy_level_id = :sy_level_id;";
        $exec = $this->setBindedExecution($query3, [
            "sy_level_name" => $this->middleware->filterString($syLevelName),
            "sy_level_id" => $syLevelId,
        ]);

        $exec ? $this->middleware->alert("successful_sy_leveL_name_modif") 
        : $this->middleware->alert("failed_sy_leveL_name_modif");
    }

    public function modifSyLevelDesc (): void {
        header("Content-Type: application/json");
        $syLevelDescription = $_POST["sy-level-description"] ?? null;
        $syLevelId = $_POST["sy-level-id"] ?? null;

        if (empty($syLevelName) || empty($syLevelId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM sy_levels WHERE sy_level_id = :sy_level_id;"; 
        $fetch1 = $this->setBindedExecution($query1, ["sy_level_id" => $syLevelId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("sy_level_noexist");
        }

        if (empty($fetch1[0]["sy_level_description"])) {
            $this->middleware->alert("sy_level_name_already_used");
        }

        $query3 = "UPDATE sy_levels SET sy_level_description = :sy_level_description WHERE sy_level_id = :sy_level_id;";
        $exec = $this->setBindedExecution($query3, [
            "sy_level_description" => $this->middleware->filterString($syLevelDescription),
            "sy_level_id" => $syLevelId,
        ]);

        $exec ? $this->middleware->alert("successful_sy_leveL_desc_modif") 
        : $this->middleware->alert("failed_sy_leveL_desc_modif");
    }

    ## CLASSES 
    public function addClass (): void {
        header("Content-Type: application/json");
        $classId = $this->middleware->getUniqueId() ?? null;
        $classSectionName = $_POST["class-section-name"] ?? null;
        $classAdvisorId = $_POST["class-adviser-id"] ?? null;
        $classSyId = $_POST["class-sy-id"] ?? null;
        $classGradeLevelId = $_POST["class-grade-level-id"] ?? null;
        $classStrandId = $_POST["class-strand-id"] ?? null;
        $classRoomId = $_POST["class-room-id"] ?? null;
        // $classDays = 

        if (empty($classId) || empty($classSectionName) || 
        empty($classAdvisorId) || empty($classSyId) || 
        empty($classGradeLevelId) || empty($classStrandId) || empty($classRoomId)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isTeacherExist($classAdvisorId);
        $this->administratorClassModel->isRoomExist($classRoomId);
        $this->administratorClassModel->isSyLevelExist($classSyId);
        $this->administratorClassModel->isGradeLevelExist($classGradeLevelId);
        $this->administratorClassModel->isStrandExist($classStrandId);
        $this->administratorClassModel->isSectionNameAlreadyUsed($classSectionName);         
        $this->administratorClassModel->schoolClassCreation($classSectionName, $classAdvisorId, 
        $classSyId, $classGradeLevelId, $classStrandId, $classRoomId);
    }

    public function modifClassSectionName (): void {
        header("Content-Type: application/json");
        $classSectionName = $_POST["class-section-name"] ?? null;
        $classId = $_POST["class-id"] ?? null;

        if (empty($classSectionName) || empty($classId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class WHERE class_id = :class_id;"; 
        $fetch1 = $this->setBindedExecution($query1, ["class_id" => $classId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_noexist");
        }

        $query2 = "SELECT * FROM class WHERE class_section_name = :class_section_name;";
        $fetch2 = $this->setBindedExecution($query2, ["class_section_name" => $classSectionName])->fetchAll();

        if (!empty($fetch2)) {
            $this->middleware->alert("class_section_name_already_exist");
        }

        $query3 = "UPDATE class SET class_section_name = :class_section_name 
        WHERE class_id = :class_id;";
        $exec = $this->setBindedExecution($query3, [
            "class_section_name" => $this->middleware->filterString($classSectionName),
            "class_id" => $classId,
        ]);

        $exec ? $this->middleware->alert("successful_class_sectionL_name_modif") 
        : $this->middleware->alert("failed_class_section_name_modif");
    }

    public function modifClassTeacherAdvisory (): void {
        header("Content-Type: application/json");
        $classAdvisorId = $_POST["class-advisor-id"] ?? null;
        $classId = $_POST["class-id"] ?? null;

        if (empty($classAdvisorId) || empty($classId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class WHERE class_id = :class_id;";
        $fetch1 = $this->setBindedExecution($query1, ["class_id" => $classId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_noexist");
        }

        $query2 = "SELECT * FROM registered_teachers 
        WHERE teacher_professional_id = :teacher_professional_id;";
        $fetch1 = $this->setBindedExecution($query1, [ "teacher_professional_id" => $classAdvisorId ])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("teacher_noexist");
        }

        $query3 = "UPDATE class SET class_teacher_advisory_id = :class_teacher_advisory_id 
        WHERE class_id = :class_id;";
        $exec = $this->setBindedExecution($query3, ["class_teacher_advisory_id" => $classAdvisorId, 
        "class_id" => $classId]);

        $exec ? $this->middleware->alert("successful_class_advisor_modif") 
        : $this->middleware->alert("failed_class_adivor_modif");
    }

    public function modifClassSy (): void {
        header("Content-Type: application/json");
        $classSyId = $_POST["class-sy-id"] ?? null;
        $classId = $_POST["class-id"] ?? null;

        if (empty($classSyId) || empty($classId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class WHERE class_id = :class_id;";
        $fetch1 = $this->setBindedExecution($query1, ["class_id" => $classId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_noexist");
        }

        $query2 = "SELECT * FROM sy_levels WHERE sy_level_id = :sy_level_id;";
        $fetch2 = $this->setBindedExecution($query2, ["sy_level_id" => $classSyId])->fetchAll();

        if (empty($fetch2)) {
            $this->middleware->alert("sy_level_noexist");
        }

        $query3 = "UPDATE class SET class_sy_id = :class_sy_id 
        WHERE class_id = :class_id;";
        $exec = $this->setBindedExecution($query3, ["class_sy_id" => $classSyId, 
        "class_id" => $classId]);

        $exec ? $this->middleware->alert("successful_class_sy_level_modif") 
        : $this->middleware->alert("failed_class_sy_level_modif");
    }

    public function modifClassGrade (): void {
        header("Content-Type: application/json");
        $classGradeLevelId = $_POST["class-grade-id"] ?? null;
        $classId = $_POST["class-id"] ?? null;

        if (empty($classGradeLevelId) || empty($classId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class WHERE class_id = :class_id;";
        $fetch1 = $this->setBindedExecution($query1, ["class_id" => $classId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_noexist");
        }
        
        $query3 = "SELECT * FROM grade_levels WHERE grade_level_id = :grade_level_id;";
        $fetch3 = $this->setBindedExecution($query3, ["grade_level_id" => $classGradeLevelId])->fetchAll();

        if (empty($fetch3)) {
            $this->middleware->alert("grade_level_noexist");
        }

        $query3 = "UPDATE class SET class_grade_level_id = :class_grade_level_id 
        WHERE class_id = :class_id;";
        $exec = $this->setBindedExecution($query3, ["class_grade_level_id" => $classGradeLevelId, 
        "class_id" => $classId]);

        $exec ? $this->middleware->alert("successful_class_grade_level_modif") 
        : $this->middleware->alert("failed_class_grade_level_modif");
    }

    public function modifClassStrand (): void {
        header("Content-Type: application/json");
        $classStrandId = $_POST["class-strand-id"] ?? null;
        $classId = $_POST["class-id"] ?? null;

        if (empty($classGradeLevelId) || empty($classId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class WHERE class_id = :class_id;";
        $fetch1 = $this->setBindedExecution($query1, ["class_id" => $classId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_noexist");
        }

        $query4  = "SELECT * FROM strand WHERE strand_id = :strand_id;";
        $fetch4 = $this->setBindedExecution($query4, ["strand_id" => $classStrandId])->fetchAll();
        
        if (empty($fetch4)) {
            $this->middleware->alert("strand_noexist");
        } 
       
        $query3 = "UPDATE class SET class_strand_id = :class_strand_id 
        WHERE class_id = :class_id;";
        $exec = $this->setBindedExecution($query3, ["class_strand_id" => $classStrandId, 
        "class_id" => $classId]);

        $exec ? $this->middleware->alert("successful_class_strand_modif") 
        : $this->middleware->alert("failed_class_strand_modif");
    }

    ## CLASS SUBJECT TYPES 
    public function addClassSubjType (): void {
        $subjectTypeId = $this->middleware->getUniqueId() ?? null; 
        $subjectTypeName = $_POST["subject-type-name"] ?? null;
        $subjectTypeDescription = $_POST["subject-type-description"] ?? null;

        if (empty($subjectTypeId) || empty($subjectTypeName) || empty($subjectTypeDescription)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_subject_types WHERE subject_type_name = :subject_type_name;";
        $fetch1 = $this->setBindedExecution($query1, ["subject_type_name" => $subjectTypeName])->fetchAll();

        if (!empty($fetch1)) {
            $this->middleware->alert("subject_type_name_already_exist");
        }

        $query2 = "INSERT INTO class_subject_types (subject_type_id, subject_type_name, subject_type_description) 
        VALUES (:subject_type_id, :subject_type_name, :subject_type_description);";
        $exec = $this->setBindedExecution($query2, [
            "subject_type_id" => $this->middleware->getUniqueId(),
            "subject_name" => $this->middleware->filterString($subjectTypeName),
            "subject_description" => $this->middleware->filterString($subjectTypeDescription),
        ]);

        $exec ? $this->middleware->alert("failed_subject_type_creation")
        : $this->middleware->alert("successful_subject_type_creation");
    }

    public function modifClassSubjTypeName (): void {
        header("Content-Type: application/json");
        $subjectTypeId = $_POST["subject-type-name"] ?? null;
        $subjectTypeName = $_POST["subject-type-id"] ?? null;

        if (empty($subjectTypeId) || empty($subjectTypeName)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_subject_types WHERE subject_type_id = :subject_type_id;"; 
        $fetch1 = $this->setBindedExecution($query1, ["subject_type_id" => $subjectTypeId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("subject_type_noexist");
        }

        $query1 = "SELECT * FROM class_subject_types WHERE subject_type_name = :subject_type_name;";
        $fetch1 = $this->setBindedExecution($query1, ["subject_type_name" => $subjectTypeName])->fetchAll();

        if (!empty($fetch1)) {
            $this->middleware->alert("subject_type_name_already_exist");
        }

        $query3 = "UPDATE class_subject_types SET subject_type_name = :subject_type_name WHERE subject_type_id = :subject_type_id;";
        $exec = $this->setBindedExecution($query3, [
            "subject_type_name" => $this->middleware->filterString($subjectTypeName),
            "subhject_type_id" => $subjectTypeId,
        ]);

        $exec ? $this->middleware->alert("successful_subject_type_name_modif") 
        : $this->middleware->alert("failed_subject_type_name_modif");
    }

    public function modifClassSubjTypeDesc (): void {
        header("Content-Type: application/json");
        $subjectTypeId = $_POST["subject-type-name"] ?? null;
        $subjectTypeDescription = $_POST["subject-type-description"] ?? null;

        if (empty($subjectTypeId) || empty($subjectTypeName)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_subject_types WHERE subject_type_id = :subject_type_id;"; 
        $fetch1 = $this->setBindedExecution($query1, ["subject_type_id" => $subjectTypeId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("subject_type_noexist");
        }

        $query2 = "SELECT subject_type_description FROM class_subject_types WHERE subject_type_id = :subject_type_id;";
        $fetch2 = $this->setBindedExecution($query2, ["subject_type_id" => $subjectTypeId])->fetchAll();

        if (!empty($fetch2)) {
            $this->middleware->alert("subject_type_desc_notset");
        }

        $query3 = "UPDATE class_subject_types SET subject_type_name = :subject_type_name WHERE subject_type_id = :subject_type_id;";
        $exec = $this->setBindedExecution($query3, [
            "subject_type_description" => $this->middleware->filterString($subjectTypeDescription),
            "subject_type_id" => $subjectTypeId,
        ]);

        $exec ? $this->middleware->alert("successful_subject_type_desc_modif") 
        : $this->middleware->alert("failed_subject_type_desc_modif");
    }

    ## CLASS SUBJECTS
    public function addClassSubj (): void {
        $classSubjectId = $this->middleware->getUniqueId() ?? null;
        $classSubjectName = $_POST["class-subject-name"] ?? null;
        $classSubjectTypeId = $_POST["class-subject-type-id"] ?? null;
        $classSubjectStrandId = $_POST["class-subject-strand-id"] ?? null;
        $classSubjectDescription = $_POST["class-subject-description"] ?? null;

        if (empty($classSubjectId) || empty($classSubjectName) || empty($classSubjectTypeId) 
        || empty($classSubjectStrandId) || empty($classSubjectDescription)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT class_subject_name FROM class_subjects WHERE class_subject_name = :class_subject_name;";
        $fetch1 = $this->setBindedExecution($query1, ["class_subject_name" => $classSubjectName])->fetchAll();

        if (!empty($fetch1)) {
            $this->middleware->alert("class_subject_name_already_exist");
        }

        $query2 = "SELECT * FROM class_subject_types WHERE subject_type_id = :subject_type_id;";
        $fetch2 = $this->setBindedExecution($query2, ["subject_type_id" => $classSubjectTypeId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("subject_type_noexist");
        }

        $query3 = "SELECT * FROM strand WHERE strand_id = :strand_id;";
        $fetch3 = $this->setBindedExecution($query3, ["strand_id" => $classSubjectStrandId])->fetchAll();

        if (empty($fetch3)) {
            $this->middleware->alert("strand_noexist");
        }

        $query4 = "INSERT INTO class_subjects (class_subject_id, class_subject_name, class_subject_type_id, class_subject_strand_id, class_subject_description) VALUES (:class_subject_id, :class_subject_name, :class_subject_type_id, :class_subject_strand_id, :class_subject_description);";
        $exec = $this->setBindedExecution($query4, [
            "class_subject_id" => $classSubjectId,
            "class_subject_name" => $this->middleware->filterString($classSubjectName),
            "class_subject_type_id" => $classSubjectTypeId,
            "class_subject_strand_id" => $classSubjectStrandId,
            "class_subject_description" => $this->middleware->filterString($classSubjectDescription),
        ]);

        $exec ? $this->middleware->alert("successful_class_subject_creation")
        : $this->middleware->alert("failed_class_subject_creation");
    }
    
    public function modifClassSubjName (): void {
        header("Content-Type: application/json");
        $classSubjectId = $_POST["class-subject-id"] ?? null;
        $classSubjectName = $_POST["class-subject-name"] ?? null;

        if (empty($classSubjectId) || empty($classSubjectName)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_subjects WHERE class_subject_id = :class_subject_id;";
        $fetch2 = $this->setBindedExecution($query1, ["class_subject_id" => $classSubjectId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_subject_noexist");
        }

        $query2 = "SELECT class_subject_name FROM class_subjects WHERE class_subject_name = :class_subject_name;";
        $fetch2 = $this->setBindedExecution($query2, ["class_subject_name" => $classSubjectName])->fetchAll();

        if (!empty($fetch1)) {
            $this->middleware->alert("class_subject_name_already_exist");
        }       

        $query3 = "UPDATE class_subjects SET class_subject_name = :class_subject_name WHERE class_subject_id = :class_subject_id;";
        $exec = $this->setBindedExecution($query3, ["class_subject_name" => $classSubjectName, "class_subject_id" => $classSubjectId]);

        $exec ? $this->middleware->alert("successful_class_subject_name_modif")
        : $this->middleware->alert("failed_class_subject_name_modif");
    }

    public function modifClassSubjStrand (): void {
        header("Content-Type: application/json");
        $classSubjectStrandId = $_POST["class-strand-id"] ?? null;
        $classSubjectId = $_POST["class-id"] ?? null;

        if (empty($classSubjectId) || empty($classSubjectStrandId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_subjects WHERE class_subject_id = :class_subject_id;";
        $fetch2 = $this->setBindedExecution($query1, ["class_subject_id" => $classSubjectId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_subject_noexist");
        }

        $query3 = "SELECT * FROM strand WHERE strand_id = :strand_id;";
        $fetch3 = $this->setBindedExecution($query3, ["strand_id" => $classSubjectStrandId])->fetchAll();

        if (empty($fetch3)) {
            $this->middleware->alert("strand_noexist");
        }

        $query3 = "UPDATE class_subjects SET class_subject_strand_id = :class_subject_strand_id WHERE class_subject_id = :class_subject_id;";
        $exec = $this->setBindedExecution($query3, ["class_subject_strand_id" => $classSubjectStrandId, "class_subject_id" => $classSubjectId]);

        $exec ? $this->middleware->alert("successful_class_subject_strand_modif")
        : $this->middleware->alert("failed_class_subject_strand_modif");
    }

    public function modifClassSubjType (): void {
        header("Content-Type: application/json");
        $classSubjectId = $_POST["class-subject-id"] ?? null;
        $classSubjectTypeId = $_POST["class-subject-type-id"] ?? null;

        if (empty($classSubjectId) || empty($classSubjectTypeId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_subjects WHERE class_subject_id = :class_subject_id;";
        $fetch2 = $this->setBindedExecution($query1, ["class_subject_id" => $classSubjectId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_subject_noexist");
        }

        $query2 = "SELECT * FROM class_subject_types WHERE subject_type_id = :subject_type_id;";
        $fetch2 = $this->setBindedExecution($query2, ["subject_type_id" => $classSubjectTypeId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("subject_type_noexist");
        }

        $query3 = "UPDATE class_subjects SET class_subject_type_id = :class_subject_type_id WHERE class_subject_id = :class_subject_id;";
        $exec = $this->setBindedExecution($query3, ["class_subject_type_id" => $classSubjectTypeId, "class_subject_id" => $classSubjectId]);

        $exec ? $this->middleware->alert("successful_class_subject_type_modif")
        : $this->middleware->alert("failed_class_subject_type_modif");
    }

    public function modifClassSubjDesc (): void {
        header("Content-Type: application/json");
        $classSubjectId = $_POST["class-subject-id"] ?? null;
        $classSubjectDescription = $_POST["class-subject-description"] ?? null;

        if (empty($classSubjectId) || empty($classSubjectDescription)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_subjects WHERE class_subject_id = :class_subject_id;";
        $fetch1 = $this->setBindedExecution($query1, ["class_subject_id" => $classSubjectId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_subject_noexist");
        }

        $query2 = "SELECT class_subject_description FROM class_subjects WHERE class_subject_id = :class_subject_id;";
        $fetch2 = $this->setBindedExecution($query2, ["class_subject_id" => $classSubjectId])->fetchAll();

        if (!empty($fetch1)) {
            $this->middleware->alert("class_subject_description_notset");
        }       

        $query3 = "UPDATE class_subjects SET class_subject_description = :class_subject_description WHERE class_subject_id = :class_subject_id;";
        $exec = $this->setBindedExecution($query3, ["class_subject_description" => $classSubjectDescription, "class_subject_id" => $classSubjectId]);

        $exec ? $this->middleware->alert("successful_class_subject_desc_modif")
        : $this->middleware->alert("failed_class_subject_desc_modif");
    }

    ## CLASS SUBJECT SUBTOPICS
    public function addClassSubjSubtopic (): void {
        header("Content-Type: application/json");
        $subtopicId = $this->middleware->getUniqueId();
        $subtopicName = $_POST["subtopic-name"] ?? null;
        $subtopicSubjectId = $_POST["subtopic-subject-id"] ?? null;
        $subtopicDescription = $_POST["subtopic-description"] ?? null;

        if (empty($subtopicId) || empty($subtopicName) || empty($subtopicSubjectId) || empty($subtopicDescription)){
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isSubtopicNameAlreadyUsed($subtopicName);
        $this->administratorClassModel->isClassSubjectExist($subtopicSubjectId); 
        $this->administratorClassModel->createNewSubjectSubtopic($subtopicId, $subtopicName, $subtopicSubjectId, $subtopicDescription);
    }

    public function modifClassSubjSubtopicName (): void {
        header("Content-Type: application/json");
        $subtopicId = $_POST["subtopic-id"] ?? null;
        $subtopicName = $_POST["subtopic-name"] ?? null;

        if (empty($subtopicId) || empty($subtopicName)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_subject_subtopic WHERE subtopic_id = :subtopic_id;";
        $fetch1 = $this->setBindedExecution($query1, ["subtopic_id" => $subtopicId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_subject_subtopic_noexist");
        }

        $query2 = "SELECT * FROM class_subject_subtopic WHERE subtopic_name = :subtopic_name;";
        $fetch2 = $this->setBindedExecution($query2, ["subtopic_name" => $subtopicName])->fetchAll();

        if (!empty($fetch1)) {
            $this->middleware->alert("subtopic_name_already_used");
        }

        $query3 = "UPDATE class_subject_subtopic SET subtopic_name = :subtopic_name WHERE subtopic_id = :subtopic_id;";
        $exec = $this->setBindedExecution($query3, [
            "subtopic_id" => $subtopicId,
            "subtopic_name" => $this->middleware->filterString($subtopicName),
        ]);

        $exec ? $this->middleware->alert("sucessful_class_subject_subtopic_name_modif") 
        : $this->middleware->alert("failed_class_subject_subtopic_name_modif");
    }

    public function modifClassSubjSubtopicSubject (): void {
        header("Content-Type: application/json");
        $subtopicId = $_POST["subtopic-id"] ?? null;
        $subtopicSubjectId = $_POST["subtopic-subject-id"] ?? null;

        if (empty($subtopicId) || empty($subtopicSubjectId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_subject_subtopic WHERE subtopic_id = :subtopic_id;";
        $fetch1 = $this->setBindedExecution($query1, ["subtopic_id" => $subtopicId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_subject_subtopic_noexist");
        }

        $query2 = "SELECT * FROM class_subjects WHERE class_subject_id = :class_subject_id;";
        $fetch2 = $this->setBindedExecution($query1, ["class_subject_id" => $subtopicSubjectId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_subject_noexist");
        }

        $query3 = "UPDATE class_subject_subtopic SET subtopic_subject_id = :subtopic_subject_id WHERE subtopic_id = :subtopic_id;";
        $exec = $this->setBindedExecution($query3, [
            "subtopic_id" => $subtopicId,
            "subtopic_subject_id" => $subtopicId,
        ]);

        $exec ? $this->middleware->alert("sucessful_class_subject_subtopic_subj_modif") 
        : $this->middleware->alert("failed_class_subject_subtopic_subj_modif");
    }

    public function modifClassSubjSubtopicDesc (): void {
        header("Content-Type: application/json");
        $subtopicId = $_POST["subtopic-id"] ?? null;
        $subtopicDescription = $_POST["subtopic-descrition"] ?? null;

        if (empty($subtopicId) || empty($subtopicDescription)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_subject_subtopic WHERE subtopic_id = :subtopic_id;";
        $fetch1 = $this->setBindedExecution($query1, ["subtopic_id" => $subtopicId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_subject_subtopic_noexist");
        }

        $query2 = "SELECT subtopic_description FROM class_subject_subtopic WHERE subtopic_id = :subtopic_id;";
        $fetch2 = $this->setBindedExecution($query2, ["subtopic_id" => $subtopicId])->fetchAll();

        if (empty($query2)) {
            $this->middleware->alert("class_subject_subtopic_desc_notset");
        }

        $query3 = "UPDATE class_subject_subtopic SET subtopic_description = :subtopic_description WHERE subtopic_id = :subtopic_id;";
        $exec = $this->setBindedExecution($query3, [
            "subtopic_id" => $subtopicId,
            "subtopic_subject_id" => $this->middleware->filterString($subtopicDescription),
        ]);

        $exec ? $this->middleware->alert("sucessful_class_subject_subtopic_desc_modif") 
        : $this->middleware->alert("failed_class_subject_subtopic_desc_modif");
    }

    ## CLASS SCHEDULES
    public function addClassSched (): void {
        header("Content-Type: application/json");
        $classSchedId = $this->middleware->getUniqueId() ?? null;
        $classSchedEnd = $_POST["class-sched-end"] ?? null;
        $classSchedStart = $_POST["class-sched-start"] ?? null;
        $classSchedClassId = $_POST["class-sched-class-id"] ?? null;
        $classSchedSubjectId = $_POST["class-sched-subject-id"] ?? null;
        $classSchedSubjectTeacherId = $_POST["class-sched-subject-teacher-id"] ?? null;
        $includedDays = $_POST["includedDays"] ?? null;


        if (empty($classSchedId) || empty($classSchedStart) || empty($classSchedEnd) || empty($classSchedClassId) 
        || empty($classSchedSubjectId) || empty($classSchedSubjectTeacherId) || empty($includedDays)) {
            $this->middleware->alert("incomplete_data");
        }
        
        $this->administratorClassModel->isDaysExist($includedDays);
        $this->administratorClassModel->isClassExist($classSchedClassId);
        $this->administratorClassModel->isClassSubjectExist($classSchedSubjectId);
        $this->administratorClassModel->isTeacherExist($classSchedSubjectTeacherId);
        $this->administratorClassModel->createNewSubjectSched($classSchedStart, $classSchedEnd, $classSchedClassId, $classSchedSubjectId, $classSchedSubjectTeacherId, $includedDays, $classSchedId);
    }

    public function modifClassSchedStart (): void {
        header("Content-Type: application/json");
        $classSchedStart = $_POST["class-sched-start"] ?? null;
        $classSchedId = $_POST["class-sched-id"] ?? null;

        if (empty($classSchedStart) || empty($classSchedId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_schedules WHERE class_sched_id = :class_sched_id;";
        $fetch1 = $this->setBindedExecution($query1, ["class_sched_id" => $classSchedId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_sched_noexist");
        }

        $query2 = "UPDATE class_schedules SET class_sched_start = :class_sched_start WHERE class_sched_id = :class_sched_id;";
        $exec = $this->setBindedExecution($query2, ["class_sched_id" => $classSchedId, "class_sched_start" => $classSchedStart]);

        $exec ? $this->middleware->alert("successful_class_sched_start_modif")
        : $this->middleware->alert("failed_class_sched_start_modif");
    }

    public function modifClassSchedEnd (): void {
        header("Content-Type: application/json");
        $classSchedEnd = $_POST["class-sched-end"] ?? null;
        $classSchedId = $_POST["class-sched-id"] ?? null;

        if (empty($classSchedEnd) || empty($classSchedId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_schedules WHERE class_sched_id = :class_sched_id;";
        $fetch1 = $this->setBindedExecution($query1, ["class_sched_id" => $classSchedId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_sched_noexist");
        }

        $query2 = "UPDATE class_schedules SET class_sched_end = :class_sched_end WHERE class_sched_id = :class_sched_id;";
        $exec = $this->setBindedExecution($query2, ["class_sched_id" => $classSchedId, "class_sched_start" => $classSchedEnd]);

        $exec ? $this->middleware->alert("successful_class_sched_end_modif")
        : $this->middleware->alert("failed_class_sched_end_modif");
    }

    public function modifClassSchedClass (): void {
        header("Content-Type: application/json");
        $classSchedClassId = $_POST["class-sched-class-id"] ?? null;
        $classSchedId = $_POST["class-sched-id"] ?? null;

        if (empty($classSchedClassId) || empty($classSchedId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_schedules WHERE class_sched_id = :class_sched_id;";
        $fetch1 = $this->setBindedExecution($query1, ["class_sched_id" => $classSchedId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_sched_noexist");
        }

        $query2 = "SELECT * FROM class WHERE class_id = :class_id;"; 
        $fetch2 = $this->setBindedExecution($query2, ["class_id" => $classSchedClassId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_noexist");
        }

        $query3 = "UPDATE class_schedules SET class_sched_class_id = :class_sched_class_id WHERE class_sched_id = class_sched_id;";
        $exec = $this->setBindedExecution($query3, ["class_sched_class_id" => $classSchedClassId, "class_sched_id" => $classSchedId]);

        $exec ? $this->middleware->alert("failed_class_sched_class_modif")
        : $this->middleware->alert("failed_class_sched_class_modif");
    }

    public function modifClassSchedSubj (): void {
        header("Content-Type: application/json");
        $classSchedSubjectId = $_POST["class-sched-subject-id"] ?? null;
        $classSchedId = $_POST["class-sched-id"] ?? null;

        if (empty($classSchedClassId) || empty($classSchedId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_schedules WHERE class_sched_id = :class_sched_id;";
        $fetch1 = $this->setBindedExecution($query1, ["class_sched_id" => $classSchedId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_sched_noexist");
        }

        $query2 = "SELECT * FROM class_subjects WHERE class_subject_id = :class_subject_id;";
        $fetch2 = $this->setBindedExecution($query2, ["class_subject_id" => $classSchedSubjectId])->fetchAll();

        if (empty($fetch2)) {
            $this->middleware->alert("class_subject_noexist");
        }

        $query3 = "UPDATE class_schedules SET class_sched_subject_teacher_id = :class_sched_subject_teacher_id WHERE class_sched_id = class_sched_id;";
        $exec = $this->setBindedExecution($query3, ["class_sched_subject_teacher_id" => $classSchedSubjectId, "class_sched_id" => $classSchedId]);

        $exec ? $this->middleware->alert("failed_class_sched_subj_modif")
        : $this->middleware->alert("failed_class_sched_subj_modif");
    }

    public function modifClassSchedSubjTeacher (): void {
        header("Content-Type: application/json");
        $classSchedSubjectTeacherId = $_POST["class-sched-subject-teacher-id"] ?? null;
        $classSchedId = $_POST["class-sched-id"] ?? null;

        if (empty($classSchedSubjectTeacherId) || empty($classSchedId)) {
            $this->middleware->alert("incomplete_data");
        }

        $query1 = "SELECT * FROM class_schedules WHERE class_sched_id = :class_sched_id;";
        $fetch1 = $this->setBindedExecution($query1, ["class_sched_id" => $classSchedId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("class_sched_noexist");
        }

        $query2 = "SELECT * FROM registered_teachers 
        WHERE teacher_professional_id = :teacher_professional_id;";
        $fetch1 = $this->setBindedExecution($query1, [ "teacher_professional_id" => $classSchedSubjectTeacherId])->fetchAll();

        if (empty($fetch2)) {
            $this->middleware->alert("teacher_noexist");
        }

        $query3 = "UPDATE class_schedules SET class_sched_subject_teacher_id = :class_sched_subject_taacher_id WHERE class_sched_id = class_sched_id;";
        $exec = $this->setBindedExecution($query3, ["class_sched_subject_teacher_id" => $classSchedSubjectTeacherId, "class_sched_id" => $classSchedId]);

        $exec ? $this->middleware->alert("failed_class_sched_teacher_modif")
        : $this->middleware->alert("failed_class_sched_teacher_modif");
    }

    ############################################################################
    ####                                                                    ####
    ####                FUTURE IMPLEMENTATION OBJECTIVES                    ####
    ####                                                                    ####
    ############################################################################

    public function getAllRegistrations (): array {
        $data = [
            "pending_student_enr" => $this->administratorClassModel->getAllStudentEnr("PND") ?? null,
            "rejected_student_enr" => $this->administratorClassModel->getAllStudentEnr("REJ") ?? null,
            "approved_student_enr" => $this->administratorClassModel->getAllStudentEnr("REG") ?? null,
            "rejected_teacher_reg" => $this->administratorClassModel->getAllTeacherEnr("REJ") ?? null,
            "pending_teacher_reg" => $this->administratorClassModel->getAllTeacherEnr("PND") ?? null,
            "approved_teacher_reg" => $this->administratorClassModel->getAllTeacherEnr("REG") ?? null,
        ];
        return $data;
    }

    public function onUpdateAccount (): void {
        # TODO
    }

    public function setStudentAsEnrolled (): void {
        $studentId = $_POST["student-id"] ?? null;

        $query1 = "SELECT * FROM registered_students WHERE student_learning_ref_number = :student_learning_ref_number;";
        $fetch1 = $this->setBindedExecution($query1, ["student_learning_ref_number" => $studentId])->fetchAll();

        if (empty($fetch1)) {
            $this->middleware->alert("student_noexist");
        }
    }

    public function aproveTeacherReg (): void {
        $JSON = $this->middleware->spillJSON();
        $teacherId = $JSON["data"] ?? null;
        if (empty($teacherId)) {$this->middleware->alert("incomlete_data");}
        $this->administratorClassModel->approveTeacher($teacherId);
    }

    public function rejectTeacherReg (): void {
        $JSON = $this->middleware->spillJSON();
        $teacherId = $JSON["data"] ?? null;
        if (empty($teacherId)) {$this->middleware->alert("incomlete_data");}
        $this->administratorClassModel->rejectTeacher($teacherId);
    }

    public function approveStudentReg (): void {
        $JSON = $this->middleware->spillJSON();
        $studentId = $JSON["data"] ?? null;
        if (empty($studentId)) {$this->middleware->alert("incomlete_data");}
        $this->administratorClassModel->approveStudent($studentId);
    }

    public function rejectStudentReg (): void {
        $JSON = $this->middleware->spillJSON();
        $studentId = $JSON["data"] ?? null;
        if (empty($studentId)) {$this->middleware->alert("incomlete_data");}
        $this->administratorClassModel->rejectStudent($studentId);
    }

    public function addNewStudent (): void {
        $studentLrn = $_POST["student-lrn"] ?? null;
        $classId = $_POST["class-id"] ?? null;

        if (empty($classId) || empty($studentLrn)) {
            $this->middleware->alert("incomplete_data");
        }

        $this->administratorClassModel->isStudentVerified($studentLrn);
        $this->administratorClassModel->isClassExist($classId);
        $this->administratorClassModel->isStudentAlreadyInClass($studentLrn);
        $this->administratorClassModel->addNewStudentToClass($studentLrn, $classId);
    }

    // public function getAllDays (): array {
    //     return $this->getAllTheDays();
    // }
    */
