<?php 

$enrolledStudents = $superAdministratorController->getAllEnrolledStudents(false) ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/superAdmin/ui.css" >
    <link rel="stylesheet" href="/css/superAdmin/students.css">
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
                    <li class="sidebar_links active">
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
                    <li class="sidebar_links">
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

            <div class="search-bar-wrapper">
                <div class="search-bar-innerwrapper">
                    <div class="input-wrapper">
                        <input type="text" name="search" id="student-search" placeholder="Search student LRN..." autocomplete="off" spellcheck="false">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="students-container has-scroll">
                <?php
                    if (empty($enrolledStudents)) {
                        echo "<div class='alert-no-students'>";
                        echo "<p class='alert-msg'>";
                        echo "No Student found...";
                        echo "</p>";
                        echo "<div class='img-icon'>";
                        echo "<img src='/assets/img/UnknownUser.icon.png' alt='Uknown user...'>";
                        echo "</div>";
                        echo "</div>";
                    } else {
                        foreach ($enrolledStudents as $enrStu) {
                            $profile = $enrStu["ProfilePic"] ?? "/assets/img/unknownStudent.icon.png";
                            echo "
                                <div class='containers'>
                                    <div class='profile'>
                                        <img src='{$profile}' alt=''>
                                    </div>
                                    <p class='inf-title-wrapper'>
                                        <span class='inf-title'>
                                            LRN: 
                                        </span>
                                        <span class='inf-title-value'>
                                            {$enrStu["LRN"]}
                                        </span>
                                    </p>
                                    <p class='inf-name'>
                                        {$enrStu["Lastname"]}, {$enrStu["Firstname"]} {$enrStu["Middlename"]}
                                    </p>
                                    <button type='button' class='view-inf-btn' value='{$enrStu["LRN"]}'>
                                        View Student Data
                                    </button>
                                </div>
                            ";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</body>
<script src="/js/superAdmin/ui.js"></script>
<script src="/js/workers/studentSearch.js"></script>
<script src="/js/superAdmin/studentSearchEngine.js"></script>
</html>
