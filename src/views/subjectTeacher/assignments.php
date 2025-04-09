<?php 
$subjects = $subjectTeacherController->getAllSubjects() ?? null;
$classes = $subjectTeacherController->getAllClass() ?? null;
$assignments = $subjectTeacherController->getAllAssignments() ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/subjectTeacher/ui.css">
    <link rel="stylesheet" href="/css/subjectTeacher/assignments.css">
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
                        Subject Teacher
                    </p>
                </div>
                <ul class="ui_sidebar_links has-scroll">
                    <li class="sidebar_links">
                        <a href="/views/subjectTeacher/dashboard">
                            <i class="fa-solid fa-house"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar_links active">
                        <a href="/views/subjectTeacher/assignments">
                            <i class="fa-solid fa-user"></i>
                            Assignments
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/subjectTeacher/settings">
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


            <div class="subject-teacher-assig has-scroll">
                <div class="controller">
                    <button type="button" class="create-new-assig">
                        Create New Assignment
                    </button>
                </div>
                <div class="assignments closeModalAddAssig closeSubmittedassigModal">
                    <div class="view-assignment-modal">
                        <div class="opaque"></div>
                        <form class="info" id="form-add-assig">
                            <button type="button" class="close-assig-btn">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                                </div>
                            </button>
                            <div class="input-wrapper">
                                <input type="text" name="assig-title" id="assig-title" placeholder="Assignment title..." title="Title of the assignment...">
                            </div>
                            <div class="input-wrapper">
                                <input type="datetime-local" name="assig-deadline" title="Due date of the assignment...">
                            </div>
                            <div class="input-wrapper">
                                <textarea name="assig-notes" id="assig-notes" placeholder="Assignment notes..."></textarea>
                            </div>
                            <div class="input-wrapper">
                                <select name="assig-class" id="assig-class">
                                    <?php if(empty($classes)): ?>
                                        <option value="" selected hidden>No class available</option>
                                    <?php else: ?>
                                        <?php foreach($classes as $class): ?>
                                            <option value="<?= $class["class_id"] ?>"><?= $class["class_section_name"] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif;?>
                                </select>
                                <div class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <select name="assig-subject" id="assig-subject">
                                    <option value="" hidden selected>Select a class (section) first...</option>
                                </select>
                                <div class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"/></svg>
                                </div>
                            </div>
                            <div class="input-wrapper-file">
                                <input type="file" name="assig-fileAttachement" id="assig-fileAttachement">
                            </div>
                            <button type="submit">
                                Add Assignment
                            </button>
                        </form>
                    </div>

                    <div class="view-submitted-assig-modal">
                        <div class="opaque"></div>
                        <div class="submitted-assig-container">
                            <button type="button" class="close-submit-assig-modal">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                                </div>
                            </button>
                            <div class="containers has-scroll">
                                <!-- <div class="assig-box">
                                    <div class="upper-controll">
                                        <p class="student-name">
                                            John Wick
                                        </p>
                                        <a href="/download/file?path=">
                                            <i class="fa-solid fa-link"></i>
                                        </a>
                                    </div>
                                    <div class="mid-controll">
                                        <p class="date-submitted">
                                            <span>
                                                <i class="fa-solid fa-clock"></i>
                                            </span>
                                            <span class="time">
                                                8AM
                                            </span>
                                        </p>
                                        <span class="dot">
                                            ⦁
                                        </span>
                                        <p class="submitted-section">
                                            EPYC
                                        </p>
                                    </div>
                                    <div class="lower-controll">
                                        <div class="note">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia voluptatibus alias dolor. Aspernatur est, nesciunt possimus minima repudiandae voluptas commodi aliquam tempore. Fugit unde quo fuga tempore error tenetur provident.
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <?php if (empty($assignments)): ?>
                        <p>No assignments</p>
                    <?php else: ?>
                    <?php foreach($assignments as $assig): ?>
                        <div class="assignment-boxes">
                            <div class="upper-controll">
                                <span class="assignment-name">
                                    <?= $assig["AssigTitle"] ?>
                                </span>
                                <div class="controllers">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z"/></svg>
                                    </div>
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="lower-controll">
                                <div class="lower-upper-controll">
                                    <p class="time-stamp">
                                        <i class="fa-solid fa-clock"></i>
                                        <span class="time">
                                            <?php  
                                                $date1 = $assig["AssigCreatedDate"];
                                                $date2 = $assig["AssigDueDate"];
                                                $time1 = $middleware->getDateTimeFormat($date1);
                                                $time2 = $middleware->getDateTimeFormat($date2);
                                            ?>
                                            <?= $time1 . " - " . $time2 ?>
                                        </span>
                                    </p>
                                    <div class="lower-upper-controll-mini">
                                        <div class="priority-level">
                                            <span class="critical">
                                                Critical
                                            </span>
                                        </div>
                                        <div class="icon-part">
                                            <p class="icon number-of-submissions">
                                                <span class="value">
                                                    40
                                                </span>
                                                <i class="fa-solid fa-user-check"></i>
                                            </p>
                                            <p class="icon file-attachment">
                                                <a href="/download/file?path=<?=$assig["StoredName"]?>">
                                                    <i class="fa-solid fa-paperclip"></i>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="description">
                                    <?= $assig["AssigDesc"]; ?>
                                </div>
                                <button type="button" class="view-submission-btn" data-assigId='<?= $assig["AssigId"] ?>'>
                                    View Submissions
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="/js/subjectTeacher/ui.js"></script>
<script src="/js/subjectTeacher/assignment.js"></script>
</html>
