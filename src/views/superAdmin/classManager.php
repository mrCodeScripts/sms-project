<?php

    $strand = $userAccountController->getAllStrand(false);
    $unassignedTeachers = $superAdministratorController->getAllUnassignedTeachersClass(false);
    $unassignedStudents = $superAdministratorController->getAllStudentsNotInClass(false);
    $unassignedRoom = $superAdministratorController->getAllUnassignedRoomClass(false);
    $classes = $superAdministratorController->getAllClass(false);
    $rooms = $userAccountController->getAllRooms(false);
    $subjects = $superAdministratorController->getAllSubjects(false);
    $wholeTeacher = $superAdministratorController->getAllTeachers(false);
    $weekDays = $userAccountController->getDays(false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/superAdmin/ui.css">
    <link rel="stylesheet" href="/css/superAdmin/classManager.css">
    <link rel="stylesheet" href="/css/variable.css">
    <title>SuperAdministrator Panel</title>
</head>
<body>
    <div class="ui_dashboard">
        <div class="ui_sidebar">
            <div class="ui_sidebar_innerwrapper has-scroll">
                <div class="upper-controlls">
                    <div class="icon" id="main-mini-icon-sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </div>
                    <p class="ui_name">
                        SuperAdmin Dashboard
                    </p>
                </div>
                <ul class="ui_sidebar_links has-scroll">
                    <li class="sidebar_links">
                        <a href="/views/superAdmin/dashboard">
                            <i class="fa-solid fa-house"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/superAdmin/students">
                            <i class="fa-solid fa-user"></i>
                            Students
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/superAdmin/addSubject">
                            <i class="fa-solid fa-book"></i>
                            Add Subject
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/superAdmin/users">
                            <i class="fa-solid fa-users"></i>
                            Users
                        </a>
                    </li>
                    <li class="sidebar_links active">
                        <a href="/views/superAdmin/classManager">
                            <i class="fa-solid fa-people-group"></i>
                            Class Manager
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/superAdmin/grades">
                            <i class="fa-solid fa-graduation-cap"></i>
                            Grades
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/superAdmin/leaveApplications">
                            <i class="fa-solid fa-calendar"></i> 
                            Leave Application
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/superAdmin/studentApproval">
                            <i class="fa-solid fa-user-check"></i>
                            Enrollment Approval
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/superAdmin/fileManagement">
                            <i class="fa-solid fa-file"></i>
                            File Manager
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/superAdmin/attendanceReport">
                            <i class="fa-regular fa-calendar-check"></i>
                            Attendance Report
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/superAdmin/settings">
                            <i class="fa-solid fa-gear"></i>
                            Settings
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a id="logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content">
            <div class="upper-header">
                <div class="upper-header-innerwrapper">
                    <div class="main-icon" id="click-mini-bar">
                        <i class="fa-solid fa-bars"></i>
                    </div>
                    <div class="notif-icons">
                        <div class="main-icon">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <div class="main-icon">
                            <i class="fa-solid fa-message"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="class-manager-dashboard closemodal closemodalEdit closemodalAddStudModal closemodalSchedEdit has-scroll">
                <button type="button" id="add-class-btn" class="add-class-btn">
                    Add Class
                </button>
                <div class="add-class-form-modal">
                    <div class="extra-opaque"></div>
                    <div class="inner-form">
                        <button type="button" class="close-btn">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                            </div>
                        </button>
                        <form class="form">
                            <div class="input-wrapper">
                                <input type="text" name="class-section-name" id="class-section-name" placeholder="Class name">
                            </div>
                            <div class="input-wrapper">
                                <select name="class-adviser" id="class-adviser" title="Teacher advisers">
                                    <?php if (empty($unassignedTeachers)): ?>
                                        <option value="" selected hidden>No available teachers</option>
                                    <?php else: ?>
                                        <?php foreach ($teachers as $teacher):?>
                                            <option value="<?= $teacher["teacher_professional_id"] ?>"><?= $teacher["teacher_fname"] . $teacher["teacher_lname"]?></option>
                                        <?php endforeach?>
                                    <?php endif; ?>
                                </select>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <input type="number" name="class-student-limit" id="class-student-limit" placeholder="Number of students allowed">
                            </div>
                            <div class="input-wrapper">
                                <select name="class-strand" id="class-strand">
                                    <?php if (empty($strand)): ?>
                                    <?php else: ?>
                                        <?php foreach ($strand as $s): ?>
                                            <option value="<?= $s["strand_id"] ?>">
                                                <?= $s["strand_name"] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <select name="class-room" id="class-room">
                                    <?php if (empty($unassignedRoom)):?>
                                        <option value="" selected hidden>No available rooms</option>
                                    <?php endif;?>
                                </select>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <select name="class-grade-level" id="class-grade-level">
                                    <option value="11">Grade 11</option>
                                    <option value="12">Grade 12</option>
                                </select>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                </div>
                            </div>
                            <button type="submit">
                                Create Class
                            </button>
                        </form>
                    </div>
                </div>


                <div class="edit-class-form">
                    <div class="opaque"></div>
                    <div class="class-form has-scroll">
                        <button type="button" class="class-form-close-btn">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                            </div>
                        </button>
                        <form>
                            <div class="input-wrapper">
                                <input type="text" name="class-section-name" id="edit-class-section-name" placeholder="Class name">
                            </div>
                            <div class="input-wrapper">
                                <select name="class-adviser" id="edit-class-adviser">
                                    <?php if (empty($unassignedTeachers)): ?>
                                        <option value="" selected hidden>No available teachers</option>
                                    <?php else: ?>
                                        <?php foreach ($unassignedTeachers as $teachers):?>
                                            <option value="<?= $teachers["teacher_professional_id"] ?>"><?= $teachers["teacher_fname"] . $teachers["teacher_lname"] ?></option>
                                        <?php endforeach?>
                                    <?php endif; ?>
                                </select>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <input type="number" name="class-student-limit" id="edit-class-student-limit" placeholder="Number of students allowed">
                            </div>
                            <div class="input-wrapper">
                                <select name="class-strand" id="edit-class-strand">
                                    <?php if (empty($strand)): ?>
                                    <?php else: ?>
                                        <?php foreach ($strand as $s): ?>
                                            <option value="<?= $s["strand_id"] ?>">
                                                <?= $s["strand_name"] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <select name="class-room" id="edit-class-room">
                                    <?php if (empty($rooms)):?>
                                        <option value="" selected hidden>No available rooms</option>
                                    <?php else: ?>
                                    <?php foreach($rooms as $room): ?>
                                        <option value="<?= $room["room_id"] ?>" title='<?= $room["room_description"] ?>'><?= $room["room_id"] ?></option>
                                    <?php endforeach; ?>
                                    <?php endif;?>
                                </select>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <select name="class-grade-level" id="edit-class-grade-level">
                                    <option value="11">Grade 11</option>
                                    <option value="12">Grade 12</option>
                                </select>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                </div>
                            </div>
                            <button type="submit">
                                Save Changes
                            </button>
                        </form>
                    </div>
                </div>

                <div class="addStudent-modal">
                    <div class="opaque"></div>
                    <div class="student-list">
                        <button type="button" class="student-list-close-btn">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                            </div>
                        </button>
                        <form id="add-student-toclass">
                            <ul class="has-scroll">
                                <input type="hidden" name="class-id" id="selected-class-id">
                                <li>
                                    <input type="checkbox" name="stu-addClass[]">
                                    <span class="student-name">
                                        John Sinns
                                    </span>
                                    <span class="requested-strand">
                                        ICT
                                    </span>
                                </li>
                                <?php if (empty($unassignedStudents)):?>

                                <?php else: ?>
                                    <?php foreach($unassignedStudents as $stu): ?>
                                        <li>
                                            <input type="checkbox" name="student-lrns[]" value='<?= $stu["LRN"] ?>'>
                                            <span class="student-name">
                                                <?= $stu["Firstname"] . " " . $stu["Lastname"] ?>
                                            </span>
                                            <span class="requested-strand">
                                                <?= $stu["Strand"] ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                            <button type="submit">
                                Add Students
                            </button>
                        </form>
                        
                    </div>
                </div>

                <div class="manageTeacherSched-modal">
                    <div class="opaque"></div>
                    <div class="form-wrapper">
                        <button type="button" class="class-sched-close-btn">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                            </div>
                        </button>
                        <form id="add-sched-form">
                            <input type="hidden" name="class-id" id="hidden-class-id">
                            <div class="linear-input-wrapper">
                                <div class="input-wrapper">
                                    <select name="subject-id" id="class-subject">
                                        <?php if (empty($subjects)): ?>
                                            <option value='' selected hidden>No subjects available!</option>
                                        <?php else: ?>
                                            <?php foreach($subjects as $subject): ?>                                            
                                                <option value='<?= $subject["SubjectId"] ?>' title='<?= $subject["SubjectDesc"] ?>'>
                                                <?= $subject["SubjectName"] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                    </div>
                                </div>
                                <!-- Teacher -->
                                <div class="input-wrapper">
                                    <select name="teacher-id" id="class-teacher">
                                        <?php if(empty($wholeTeacher)): ?>
                                            <option value="" selected hidden>No teachers available</option>
                                        <?php else: ?>
                                            <?php foreach ($wholeTeacher as $t): ?>
                                                <option value="<?= $t["teacher_professional_id"] ?>"><?= $t["teacher_fname"] . $t['teacher_lname'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <p style="color: var(--light-4); margin: 0 10px;">Start and End</p>
                            <div class="linear-input-wrapper">
                                <!-- Start -->
                                <div class="input-wrapper">
                                    <input type="time" name="start-time" id="class-sched-start" placeholder="Start Time" title="Start">
                                </div>
                                <!-- End -->
                                <div class="input-wrapper">
                                    <input type="time" name="end-time" id="class-sched-end" placeholder="End Time" title="End">
                                </div>
                            </div>
                            <div class="multidimension-input-wrapper">
                                <ul>
                                    <?php foreach($weekDays as $days): ?>
                                    <li>
                                        <input type="checkbox" name="dayOfWeek[]" id="sched-days" value="<?= $days["day_id"] ?>">
                                        <label for="sched-days" class="<?= strtolower(substr($days["day_name"], 0, 3)) ?>"><?= substr($days["day_name"], 0, 3) ?></label>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <button type="submit">
                                Add Schedule
                            </button>
                        </form>
                    </div>
                    <div class="sched-list has-scroll">
                        <div class="sched-box">
                            <div class="upper-box">
                                <div class="time">
                                    8:00AM - 9:00AM
                                </div>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                </div>
                            </div>
                            <div class="lower-box">
                                <div class="days">
                                    <span class="mon">
                                        Mon
                                    </span>
                                    <span class="tue">
                                        Tue
                                    </span>
                                    <span class="thu">
                                        Thu
                                    </span>
                                </div>
                                <p class="subject-name">
                                    Science
                                </p>
                                <p class="subject-teacher">
                                    John Wick
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="classes">
                    <?php if (empty($classes)): ?>
                        <p>No class available</p>
                    <?php else: ?>
                    <?php foreach($classes as $class): ?>
                    <div class="class-box">
                        <div class="upper-controller">
                            <p class="class-name">
                                <?= $class["Section"] ?>
                            </p>
                            <div class="controlers">
                                <div class="icon controller-edit-teacher-class" data-class-id='<?= $class["ClassId"] ?>'>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M96 128a128 128 0 1 0 256 0A128 128 0 1 0 96 128zm94.5 200.2l18.6 31L175.8 483.1l-36-146.9c-2-8.1-9.8-13.4-17.9-11.3C51.9 342.4 0 405.8 0 481.3c0 17 13.8 30.7 30.7 30.7l131.7 0c0 0 0 0 .1 0l5.5 0 112 0 5.5 0c0 0 0 0 .1 0l131.7 0c17 0 30.7-13.8 30.7-30.7c0-75.5-51.9-138.9-121.9-156.4c-8.1-2-15.9 3.3-17.9 11.3l-36 146.9L238.9 359.2l18.6-31c6.4-10.7-1.3-24.2-13.7-24.2L224 304l-19.7 0c-12.4 0-20.1 13.6-13.7 24.2z"/></svg>
                                </div>
                                <div class="icon controller-edit-class" data-class-id='<?= $class["ClassId"] ?>'>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/></svg>
                                </div>
                                <div class="icon controller-add-student" data-class-id='<?= $class["ClassId"] ?>'>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3zM504 312l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg>
                                </div>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="lower-controll">
                            <div class="current-students info" title="10 total pupils">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3zM609.3 512l-137.8 0c5.4-9.4 8.6-20.3 8.6-32l0-8c0-60.7-27.1-115.2-69.8-151.8c2.4-.1 4.7-.2 7.1-.2l61.4 0C567.8 320 640 392.2 640 481.3c0 17-13.8 30.7-30.7 30.7zM432 256c-31 0-59-12.6-79.3-32.9C372.4 196.5 384 163.6 384 128c0-26.8-6.6-52.1-18.3-74.3C384.3 40.1 407.2 32 432 32c61.9 0 112 50.1 112 112s-50.1 112-112 112z"/></svg>
                                </div>
                                <p class="current-students">
                                    <?= $class["StudentLimit"] ?>
                                </p>
                            </div>
                            <div class="current-present-students info">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3zM625 177L497 305c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L591 143c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                </div>
                                <div class="current-present-students">
                                    20
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="/js/superAdmin/ui.js"></script>
<script src="/js/superAdmin/classManager.js"></script>
</html>

<!-- Auf der Heide bluht ein kleines Blumelein -->