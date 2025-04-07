<?php 
    $genders = $userAccountController->getGenders() ?? null;
    $userRoles = $userAccountController->getUserRoles() ?? null;
    $allUsers = $superAdministratorController->getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/superAdmin/ui.css">
    <link rel="stylesheet" href="/css/superAdmin/users.css">
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
                    <li class="sidebar_links activfe">
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
                    <li class="sidebar_links active">
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

            <div class="superAdmin-users-dashboard has-scroll">
                <div class="addUser-form"> 
                    <div class="added-bg-opac"></div>
                    <form class="form">
                        <div class="controll">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <input type="text" name="fname" id="fname" placeholder="Firstname">
                        </div>
                        <div class="input-wrapper">
                            <input type="text" name="lname" id="lname" placeholder="Lastname">
                        </div>
                        <div class="input-wrapper">
                            <input type="text" name="email-address" id="email-address" placeholder="Email address">
                        </div>
                        <div class="input-wrapper">
                            <select name="gender" id="gender">
                            <?php 
                                if (empty($genders)) {
                                    echo "<option value='' selected hidden>";
                                    echo "No Genders Available";
                                    echo "</option>";
                                } else {
                                    foreach ($genders as $gender) {
                                        echo "<option value='{$gender["gender_id"]}' 
                                        title='{$gender["gender_description"]}'>";
                                        echo $gender["gender_name"]; 
                                        echo "</option>";
                                    }
                                }
                            ?>
                            </select>
                        </div>
                        <div class="input-wrapper">
                            <select name="roles" id="roles">
                            <?php 
                            if (empty($genders)) {
                                echo "<option value='' selected hidden>";
                                echo "No Roles Available";
                                echo "</option>";
                            } else {
                                foreach ($userRoles as $role) {
                                    if ($role["role_id"] === "SUADM") continue;
                                    echo "<option value='{$role["role_id"]}' 
                                    title='{$role["role_description"]}'>";
                                    echo $role["role_name"]; 
                                    echo "</option>";
                                }
                            }
                            ?>
                            </select>
                        </div>
                        <div class="input-wrapper">
                            <input type="password" name="create-password" id="create-password" placeholder="Create password">
                        </div>
                        <div class="input-wrapper">
                            <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm password">
                        </div>
                        <button type="submit">
                            Add User
                        </button>
                    </form>
                </div>

                <button type="button" class="add-user-btn">
                    ADD USER
                </button>
                <div class="user-list">                       
                    <?php 
                    if (empty($allUsers)) {
                       echo ("
                            <h1 class='no-users-modal'>
                                No users found!
                            </h1>
                       ");
                    } else {
                        foreach ($allUsers as $user) {
                            echo ("
                                <div class='user'>
                                    <div class='profile-pic online-dot'>
                                        <div class='icon'>
                                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><path d='M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z'/></svg>
                                        </div>
                                    </div>
                                    <p class='user-name'>
                                        {$user["FirstName"]} {$user["LastName"]}
                                    </p>
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
</html>
