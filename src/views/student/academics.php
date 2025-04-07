<?php 
$assignments = $studentControllers->getAllAssignments() ?? null;
$countCompleteAssig = $studentControllers->countCompleteAssig(false);
$countNewAssignment = $studentControllers->countNewAssig(false);
$countPendingAssignments = $studentControllers->countPendingAssig(false);
$countTodaySubmittedAssig = $studentControllers->countSubmittedAssigToday(false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/student/ui.css
    ">
    <link rel="stylesheet" href="/css/variable.css">
    <link rel="stylesheet" href="/css/student/academics.css">
    <title>Student Panel</title>
</head>
<body class="">
    <div class="ui_dashboard">
        <div class="modal" id="modal">
            <div class="inner-modal">
                <p id="modal-message"></p>
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#FFFFFF"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>      
                </div>
            </div>
        </div>
        <div class="err-modal" id="err-modal">
            <div class="inner-err-modal">
                <p id="err-modal-message"></p>
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m40-120 440-760 440 760H40Zm138-80h604L480-720 178-200Zm302-40q17 0 28.5-11.5T520-280q0-17-11.5-28.5T480-320q-17 0-28.5 11.5T440-280q0 17 11.5 28.5T480-240Zm-40-120h80v-200h-80v200Zm40-100Z"/></svg>
                </div>
            </div>
        </div>
        <div class="ui_sidebar">
            <div class="ui_sidebar_innerwrapper has-scroll">
                <div class="upper-controlls">
                    <div class="icon" id="main-mini-icon-sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </div>
                    <p class="ui_name">
                        Student Dashboard
                    </p>
                </div>
                <ul class="ui_sidebar_links has-scroll">
                    <li class="sidebar_links">
                        <a href="/views/student/dashboard">
                            <i class="fa-solid fa-house"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/student/studentInformations">
                            <i class="fa-solid fa-user"></i>
                            Informations
                        </a>
                    </li>
                    <li class="sidebar_links active">
                        <a href="/views/student/academics">
                            <i class="fa-solid fa-book"></i>
                            Academics
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/student/tasks">
                            <i class="fa-solid fa-users"></i>
                            Tasks
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/student/grades">
                            <i class="fa-solid fa-graduation-cap"></i>
                            Grades
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/student/leaveApplications">
                            <i class="fa-solid fa-calendar"></i> 
                            Leave Application
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/student/fileManagement">
                            <i class="fa-solid fa-file"></i>
                            File Manager
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/student/attendance">
                            <i class="fa-regular fa-calendar-check"></i>
                            Attendance
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/student/settings">
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
            <div class="main-content-container closeModalSubmitAssig has-scroll">
                <div class="notifs">
                    <div class="box-notif" style="background-color: teal;color: #FFF;">
                        <p class="bx-notif-value">
                            <?= $countNewAssignment ?>
                        </p>
                        <p class="bx-notif-label">New Assignments</p>
                    </div>
                    <div class="box-notif" style="background-color: orange; color: #FFF;">
                        <p class="bx-notif-value">
                            <?= $countPendingAssignments ?>
                        </p>
                        <p class="bx-notif-label">Pending Assignments</p>
                    </div>
                    <div class="box-notif" style="background-color: orangered; color: #FFF;">
                        <p class="bx-notif-value">
                            <?= $countTodaySubmittedAssig ?>
                        </p>
                        <p class="bx-notif-label">Submit Today</p>
                    </div>
                    <div class="box-notif" style="background-color: var(--success-1);color: #FFF;">
                        <p class="bx-notif-value">
                            <?= $countCompleteAssig ?>
                        </p>
                        <p class="bx-notif-label">Completed</p>
                    </div>
                </div>

                <div class="submit-assig-modal">
                    <div class="opaque"></div>
                    <form id="submit-assig-form">
                        <button type="button" class="close-btn-assig">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                            </div>
                        </button>
                        <input type="hidden" name="assignment-id" id="assignment-id">
                        <div class="input-wrapper">
                            <input type="file" name="assignment-file" id="assignment-file">
                        </div>
                        <div class="input-wrapper">
                            <textarea name="assignment-note" id="assignment-note" placeholder="Notes..."></textarea>
                        </div> 
                        <button type="submit">
                            Submit Assignment
                        </button>
                    </form>
                </div>

                <div class="assignments">
                    <?php if(empty($assignments)):?>
                        <p>No assignments</p>
                    <?php else: ?>
                        <?php foreach($assignments as $assig): ?>
                            <div class="assig-box">
                                <div class="upper-controll">
                                    <p class="subject"><?= $assig["SubjectName"] ?></p>
                                    <div class="upper">
                                        <p class="assig-title">
                                            <?= $assig["AssigTitle"] ?>
                                        </p>
                                        <div class="controlls">
                                            <div class="icon assig-submit-btn">
                                                <a href="/download/file?path=<?= $assig["StoredName"] ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/></svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mid">
                                        <span class="time">
                                            <span>
                                                <i class="fa-solid fa-clock"></i>
                                            </span>
                                            <span class="time-value">
                                                <?= $middleware->getDateTimeFormat($assig["AssigCreatedDate"]) ?>
                                                - 
                                                <?= $middleware->getDateTimeFormat($assig["AssigDueDate"]) ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="lower-controll">
                                    <button class="submit-assig-btn" data-assig-id='<?= $assig["AssigId"] ?>'>
                                        Submit Assignment
                                    </button>
                                </div>
                            </div>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="/js/student/academic.js"></script>
<script src="/js/student/ui.js"></script>
</html>
