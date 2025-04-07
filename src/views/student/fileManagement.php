<?php  
    $files = $userAccountController->getMyFiles(false) ?? "No files found..";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/student/ui.css">
    <link rel="stylesheet" href="/css/student/fileManager.css">
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
                    <li class="sidebar_links active">
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

            <div class="file-manager-container has-scroll">
                <div class="fileManager-controller">
                    <div class="input-wrapper">
                        <input type="text" name="search-file" id="search-file" placeholder="Search file by name...">
                    </div>
                </div>
                <div class="upload-file">
                    <form class="form" id="file-uploader-form" enctype="multipart/form-data">
                        <div class="file">
                            <input type="file" name="user-upload-file" id="user-upload-file">
                        </div>
                        <button type="submit">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>     
                            </div>
                        </button>
                    </form>
                </div>
                <div class="allFiles-info">
                    <div class="allFiles-info-innerwrapper">
                        <div class="label">
                            All Files
                        </div>
                        <div class="allFiles-info-storage">
                            <div class="allFiles-info-storage-innerwrapper">
                                <div class="storage-info">
                                    <p class="info">
                                        <span class="used-storage-info">
                                            57.7GB
                                        </span>
                                        <span class="total-storage-info">
                                            128GB
                                        </span>
                                    </p>
                                </div>
                                <p class="progress-bar">
                                    <span class="bar"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fileGroups">
                    <div class="fileGroups-innerwrapper">
                        <div class="file-group-box file-group-gallery">
                            <div class="icon">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#2eb1b7;}.cls-2{fill:#56b54e;}.cls-3{fill:#5dc6d1;}.cls-4{fill:#60cc5a;}.cls-5{fill:#ffce69;}.cls-6{fill:#6c2e7c;}</style></defs><g id="Icons"><rect class="cls-1" height="22" rx="4" width="22" x="1" y="1"/><path class="cls-2" d="M23,18v1a4,4,0,0,1-4,4H5a4,4,0,0,1-3.9-3.1l7.19-7.19a1.008,1.008,0,0,1,1.42,0l1.8,1.8a1,1,0,0,0,1.46-.05l2.33-2.65a1,1,0,0,1,1.46-.05Z"/><path class="cls-3" d="M23,5v9a4.025,4.025,0,0,1-1.17,2.83l-5.07-5.07a1,1,0,0,0-1.46.05l-2.33,2.65a1,1,0,0,1-1.46.05l-1.8-1.8a1.008,1.008,0,0,0-1.42,0L3.36,17.64A3.988,3.988,0,0,1,1,14V5A4,4,0,0,1,5,1H19A4,4,0,0,1,23,5Z"/><path class="cls-4" d="M21.83,16.83A4.025,4.025,0,0,1,19,18H5a3.931,3.931,0,0,1-1.64-.36l4.93-4.93a1.008,1.008,0,0,1,1.42,0l1.8,1.8a1,1,0,0,0,1.46-.05l2.33-2.65a1,1,0,0,1,1.46-.05Z"/><circle class="cls-5" cx="7" cy="7" r="2"/></g><g data-name="Layer 4" id="Layer_4"><path class="cls-6" d="M19,0H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H19a5.006,5.006,0,0,0,5-5V5A5.006,5.006,0,0,0,19,0Zm3,19a3,3,0,0,1-3,3H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2H19a3,3,0,0,1,3,3Z"/><path class="cls-6" d="M7,10A3,3,0,1,0,4,7,3,3,0,0,0,7,10ZM7,6A1,1,0,1,1,6,7,1,1,0,0,1,7,6Z"/><path class="cls-6" d="M16.707,10.293a.956.956,0,0,0-.74-.293,1.006,1.006,0,0,0-.72.341L12.217,13.8l-2.51-2.511a1,1,0,0,0-1.414,0l-4,4a1,1,0,1,0,1.414,1.414L9,13.414l1.9,1.9L8.247,18.341a1,1,0,0,0,1.506,1.318l3.218-3.678.006,0,.007-.011,3.065-3.5,2.244,2.244a1,1,0,0,0,1.414-1.414Z"/></g></svg>
                            </div>
                            <p class="file-group-name">
                                Images
                            </p>
                            <p class="file-group-info">
                                1000
                            </p>
                        </div>
                        <div class="file-group-box file-group-audio">
                            <div class="icon">
                                <svg viewBox="0 0 24 24"xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#f19b5f;}.cls-2{fill:#ffce69;}.cls-3{fill:#ffe5b6;}.cls-4{fill:#6c2e7c;}</style></defs><g id="Icons"><path class="cls-1" d="M13,18A5,5,0,0,1,3,18a4.919,4.919,0,0,1,.67-2.5,5,5,0,0,1,8.66,0A4.919,4.919,0,0,1,13,18Z"/><ellipse class="cls-2" cx="8" cy="15.5" rx="4.33" ry="2.5"/></g><g data-name="Layer 4" id="Layer_4"><path class="cls-4" d="M14,3.815A10.022,10.022,0,0,0,21,7a1,1,0,0,0,0-2C16.641,5,13.884.529,13.857.485A1,1,0,0,0,12,1V13.54A5.992,5.992,0,1,0,14,18ZM8,22a4,4,0,1,1,4-4A4,4,0,0,1,8,22Z"/></g></svg>
                            </div>
                            <div class="file-group-name">
                                Audio
                            </div>
                            <div class="file-group-info">
                                1000
                            </div>
                        </div>

                        <div class="file-group-box file-group-video">
                            <div class="icon">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#ccc;}.cls-2{fill:#eaeaea;}.cls-3{fill:#2eb1b7;}.cls-4{fill:#5dc6d1;}.cls-5{fill:#ce5959;}.cls-6{fill:#6c2e7c;}</style></defs><g id="Icons"><path class="cls-1" d="M23,14v3H21.64A3.984,3.984,0,0,0,23,14Z"/><path class="cls-1" d="M2.36,17H1V14A3.984,3.984,0,0,0,2.36,17Z"/><path class="cls-2" d="M23,5v9a3.984,3.984,0,0,1-1.36,3H23v2a4,4,0,0,1-4,4H5a4,4,0,0,1-4-4V17H2.36A3.984,3.984,0,0,1,1,14V5A4,4,0,0,1,5,1H19A4,4,0,0,1,23,5Z"/><path class="cls-3" d="M23,17v2a4,4,0,0,1-4,4H5a4,4,0,0,1-4-4V17Z"/><path class="cls-4" d="M21.64,17A4,4,0,0,1,19,18H5a4,4,0,0,1-2.64-1Z"/><path class="cls-5" d="M11.119,6.156l3.072,1.535a1.463,1.463,0,0,1,0,2.618l-3.072,1.535A1.464,1.464,0,0,1,9,10.535V7.465A1.464,1.464,0,0,1,11.119,6.156Z"/></g><g data-name="Layer 4" id="Layer_4"><path class="cls-6" d="M19,0H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H19a5.006,5.006,0,0,0,5-5V5A5.006,5.006,0,0,0,19,0Zm3,19a3,3,0,0,1-3,3H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2H19a3,3,0,0,1,3,3Z"/><path class="cls-6" d="M19,16H5a1,1,0,0,0,0,2H19a1,1,0,0,0,0-2Z"/><path class="cls-6" d="M9.168,12.63a2.452,2.452,0,0,0,2.4.108L14.638,11.2a2.464,2.464,0,0,0,0-4.408L11.566,5.262A2.464,2.464,0,0,0,8,7.465v3.07A2.448,2.448,0,0,0,9.168,12.63ZM10,7.465a.452.452,0,0,1,.22-.394A.474.474,0,0,1,10.464,7a.466.466,0,0,1,.208.051l3.071,1.534a.464.464,0,0,1,0,.83l-3.072,1.534a.458.458,0,0,1-.452-.02.452.452,0,0,1-.22-.394Z"/></g></svg>
                            </div>
                            <div class="file-group-name">
                                Videos
                            </div>
                            <div class="file-group-info">
                                1000
                            </div>
                        </div>
                        <div class="file-group-box file-group-document">
                            <div class="icon">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#f19b5f;}.cls-2{fill:#ffce69;}.cls-3{fill:#ffe5b6;}.cls-4{fill:#6c2e7c;}</style></defs><g id="Icons"><path class="cls-1" d="M21,10.66V20a3,3,0,0,1-3,3H6a3,3,0,0,1-3-3V4A3,3,0,0,1,6,1h5.34a4,4,0,0,1,2.83,1.17l5.66,5.66A4,4,0,0,1,21,10.66Z"/><path class="cls-2" d="M21,10.66V15a3,3,0,0,1-3,3H6a3,3,0,0,1-3-3V4A3,3,0,0,1,6,1h5.34a4,4,0,0,1,2.83,1.17l5.66,5.66A4,4,0,0,1,21,10.66Z"/><path class="cls-3" d="M20.64,9H16a3,3,0,0,1-3-3V1.36a4.089,4.089,0,0,1,1.17.81l5.66,5.66A4.089,4.089,0,0,1,20.64,9Z"/></g><g data-name="Layer 4" id="Layer_4"><path class="cls-4" d="M6,24H18a4,4,0,0,0,4-4V10.657a4.968,4.968,0,0,0-.433-2.016.85.85,0,0,0-.055-.1,4.976,4.976,0,0,0-.977-1.416L14.879,1.464A4.981,4.981,0,0,0,13.462.487c-.035-.018-.066-.04-.1-.055A4.984,4.984,0,0,0,11.343,0H6A4,4,0,0,0,2,4V20A4,4,0,0,0,6,24ZM18.586,8H16a2,2,0,0,1-2-2V3.414ZM4,4A2,2,0,0,1,6,2h5.343A3,3,0,0,1,12,2.078V6a4,4,0,0,0,4,4h3.92a2.953,2.953,0,0,1,.08.657V20a2,2,0,0,1-2,2H6a2,2,0,0,1-2-2Z"/><path class="cls-4" d="M7,20H17a1,1,0,0,0,0-2H7a1,1,0,0,0,0,2Z"/><path class="cls-4" d="M7,16h4a1,1,0,0,0,0-2H7a1,1,0,0,0,0,2Z"/></g></svg>
                            </div>
                            <div class="file-group-name">
                                Documents
                            </div>
                            <div class="file-group-info">
                                1000
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FILE GROUPS -->
                <div class="filemanager-files-container">
                    <?php 
                    if (empty($files)) {
                        echo ("
                            <div class='no-files-found'>
                                <div class='icon'>
                                    <img src='/assets/img/no_files.png' alt='No files found png.'>
                                </div>
                                <p class='no-files-found-label'>
                                    No Files Found
                                </p>
                            </div>
                        ");
                    } else {
                        foreach ($files as $file) {
                            $path = "/../download.php?path=" . str_replace("\\", "/", ($file['FileStoredName']));
                            $bytes = $fileManagerController->bytesToMB($file["FileSize"], 3);
                            echo ("
                                <div class='files'>
                                    <div class='file-innerwrapper'>
                                        <i class='fa-solid fa-file'></i>
                                        <p class='file-name'>
                                            <span>File: </span>
                                            <span class='file-name-value'>
                                                {$file["FileNames"]}
                                            </span>
                                        </p>
                                        <p class='file-size'>
                                            <span>Size: </span>
                                            <span class='file-size-value'>
                                                {$bytes}mb
                                            </span>
                                        </p>
                                        <a href='{$path}' class='download-btn-link'>
                                            Download File
                                        </a>
                                    </div>
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
<script src="/js/student/ui.js"></script>
<script src="/js/fileUploader.js"></script>
</html>
