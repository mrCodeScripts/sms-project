<?php 
$pendingStudentApplicant = $superAdministratorController->getAllPendingStudentApplicant();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/superAdmin/ui.css
    ">
    <link rel="stylesheet" href="/css/superAdmin/studentApproval.css">
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
                    <li class="sidebar_links active">
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
        <div class="main-content closemodal">
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

            <div class="view-details-modal has-scroll">
                <div class="opaque"></div>
                <div class="info has-scroll">
                    <button type="button" class="close-btn">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                        </div>
                    </button>
                    <div class="inf">
                        <p class="label">
                            LRN
                        </p>
                        <p class="data" id="lrn-data"></p>
                    </div>
                    <div class="linear-inf">
                        <div class="inf">
                            <p class="label">
                                Lastname
                            </p>
                            <p class="data" id="lname-data"></p>
                        </div>
                        <div class="inf">
                            <p class="label">
                                Middlename
                            </p>
                            <p class="data" id="mname-data"></p>
                        </div>
                        <div class="inf">
                            <p class="label">
                                Firstname
                            </p>
                            <p class="data" id="fname-data"></p>
                        </div>
                   </div>
                   <div class="linear-inf">
                        <div class="inf">
                            <p class="label">
                                Age
                            </p>
                            <p class="data" id="age-data"></p>
                        </div>
                        <div class="inf">
                            <p class="label">
                                Gender
                            </p>
                            <p class="data" id="gender-data"></p>
                        </div>
                    </div>
                    <div class="inf">
                        <p class="label">
                            Birthdate
                        </p>
                        <p class="data" id="bdate-data"></p>
                    </div>
                    <div class="inf">
                        <p class="label">
                            Registered On
                        </p>
                        <p class="data" id="regdate-data"></p>
                    </div>
                    <div class="files">
                        <ul class="download-enr-files">
                            <li>
                                <a href="" id="birth-cert" style="background-color: var(--aqua-2);">
                                    Birth Certificate
                                </a>
                            </li>
                            <li>
                                <a href="" id="enr-form" style="background-color: var(--focus-blue);">
                                    Enrollment Form
                                </a>
                            </li>
                            <li>
                                <a href="" id="form-137" style="background-color: var(--muted-peach);">
                                    Form 137
                                </a>
                            </li>
                            <li>
                                <a href="" id="good-moral-cert" style="background-color: var(--sunset-4);">
                                    Good Moral Certificate
                                </a>
                            </li>
                            <li>
                                <a href="" id="student-repo-card" style="background-color: var(--muted-sage);">
                                    Student Report Card
                                </a>
                            </li>
                            <li>
                                <a href="" id="student-photo" style="background-color: var(--info-light);">
                                    Student Photo
                                </a>
                            </li>
                        </ul>
                    </div>
                    <button type="button" class="approve-btn" id="approve-btn-stu">
                        Approve
                    </button>
                    <button type="button" class="reject-btn" id="reject-btn-stu">
                        Reject
                    </button>
                </div>
            </div>
            <div class="student-list-container" id="studentListContainer">
                <div class="controller-search">
                    <div class="input-wrapper">
                        <input type="text" name="search-student-enrollee" id="search-student-enrollee" placeholder="Search student...">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="student-enrollees-container has-scroll">
                    <?php if (empty($pendingStudentApplicant)): ?>
                        <p>No student applicants</p>
                    <?php else: ?>
                        <?php foreach ($pendingStudentApplicant as $applicant):?>
                        <div class="student-enrollee-bx">
                            <div class="student-picture">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>   
                            </div>
                            <div class="student-personal-info">
                                <p class="student-lrn">
                                    <?= $applicant["LRN"] ?>
                                </p>
                                <p class="student-fullname">
                                    <?= $applicant["Firstname"] . " " . $applicant["Lastname"] ?>
                                </p>
                            </div>
                            <button type="button" class='view-student-details' value='<?= $applicant["LRN"] ?>'>
                                Student Details 
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
</script>
<script src="/js/superAdmin/ui.js"></script>
<script src="/js/superAdmin/studentApproval.js"></script>
</html>
