<?php 
    $subjectTypes = $userAccountController->getAllSubjectTypes(false) ?? null;
    $strands = $userAccountController->getAllStrand(false) ?? null;
    $subjects = $superAdministratorController->getAllSubjects(false) ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/superAdmin/ui.css">
    <link rel="stylesheet" href="/css/superAdmin/addSubject.css">
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
                    <li class="sidebar_links active">
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

            <div class="superAdmin-addSubject has-scroll">
                <div class="superAdmin-addSubject-form">
                    <form class="form" id="add-subject">
                        <div class="input-wrapper">
                            <input type="text" name="subject-name" id="class-subject-name" placeholder="Subject name">
                        </div>
                        <div class="input-wrapper">
                            <select name="subject-type" id="subject-type">
                                <?php 
                                    if (empty($subjectTypes)) { 
                                        echo "<option value='' selected>";
                                        echo "No subject types available";
                                        echo "</option>";
                                    } else {
                                        foreach ($subjectTypes as $types) {
                                            echo "<option value='{$types["subject_type_id"]}' title='{$types["subject_type_desc"]}'>";
                                            echo $types["subject_type_name"];
                                            echo "</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <i class="fa-solid fa-caret-right"></i>
                        </div>
                        <div class="input-wrapper">
                            <select name="subject-strand" id="class-subject-strand-id" style="padding-right: 50px;">
                                <?php 
                                    if (empty($strands)) { 
                                        echo "<option value='' selected>";
                                        echo "No subject types available";
                                        echo "</option>";
                                    } else {
                                        foreach ($strands as $strand) {
                                            echo "<option value='{$strand["strand_id"]}' title='{$strand["strand_description"]}'>";
                                            echo $strand["strand_name"];
                                            echo "</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <i class="fa-solid fa-caret-right"></i>
                        </div>
                        <div class="input-wrapper">
                            <textarea name="subject-description" id="class-subject-description" placeholder="Subject description"></textarea>
                        </div>
                        <button type="submit">
                            <p>
                                Add Subject
                            </p>
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
                            </div>
                        </button>
                    </form>
                </div>

                <div class="subjects">
                    <?php 
                        if (empty($subjects)) {
                            echo ("
                                <div class='error-subjects'>
                                    <div class='not-found-icon'>
                                        <img src='/assets/img/no_data.png' alt='No data found'>
                                    </div>
                                    <p>
                                        No Subjects!
                                    </p>
                                </div>
                            ");
                        } else {
                            foreach ($subjects as $subject) {
                                echo ("<div class='subject-box'>
                                        <div class='subject-upper-controlls'>
                                            <h1 class='subject-name'>
                                                {$subject["SubjectName"]}
                                            </h1>
                                            <input type='hidden' value='{$subject["SubjectId"]}' class='subj-val-id'>
                                            <div class='icon rem-subj-btn'>
                                                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><path d='M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                                            </div>
                                        </div>
                                        <div class='subject-mid-info'>
                                            <p class='subject-type' title='Core subject'>
                                                {$subject["SubjectType"]}
                                            </p>
                                            <p class='subject-strand'>
                                                {$subject["SubjectStrand"]}
                                            </p>
                                        </div>
                                        <div class='subject-description'>
                                            <p class='text-description'>
                                                {$subject["SubjectDesc"]}
                                            </p>
                                        </div>
                                        <button type='button' class='view-subject-details' value='{$subject["SubjectId"]}'>
                                            Subject Details
                                        </button>
                                    </div>
                                "); 
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="/js/superAdmin/ui.js"></script>
<script src="/js/superAdmin/addSubject.js"></script>
</html>
