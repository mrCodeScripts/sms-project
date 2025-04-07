<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/student/ui.css
    ">
    <link rel="stylesheet" href="/css/variable.css">
    <title>Student Panel</title>
</head>
<body>
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
                    <li class="sidebar_links active">
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
                    <li class="sidebar_links">
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
            <div class="student-list-container" id="studentListContainer"></div>
        </div>
    </div>
</body>
<script src="/js/student/ui.js"></script>
</html>
