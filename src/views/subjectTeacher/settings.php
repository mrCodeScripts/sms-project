<?php 
$userAccountController->updateLogRecord();
$accData = $_SESSION["CURR_SESSION"]["ACC_DATA"] ?? null;
$logRecords = $_SESSION["CURR_SESSION"]["ACC_DATA"]["ACC_LOG_DATA"]["LOGS"] ?? null;
$firstname = $accData["Firstname"] ?? null;
$lastname = $accData["Latname"] ?? null;
$fullname = $firstname . " " . $lastname;
$emailAddress = $accData["Email"] ?? "No email address";
$accountCreatedOn = $middleware->getModifiedTime($accData["AccountCreatedOn"]) ?? null;
$gender = $accData["Gender"] ?? "No gender";
$genderDescription  = $accData["GenderDesc"] ?? "No gender description";
$userRole = $accData["UserRole"] ?? "No role";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/subjectTeacher/ui.css">
    <link rel="stylesheet" href="/css/subjectTeacher/settings.css">
    <link rel="stylesheet" href="/css/variable.css">
    <title>SuperAdministrator Panel</title>
</head>
<body>
    <div class="ui_dashboard">
        <div class="ui_sidebar">
            <div class="ui_sidebar_innerwrapper">
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
                        <a href="/views/subjectTeacher/dashboard">
                            <i class="fa-solid fa-house"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar_links">
                        <a href="/views/subjectTeacher/assignments">
                            <i class="fa-solid fa-user"></i>
                            Assignments
                        </a>
                    </li>
                    <li class="sidebar_links active">
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

            <div class="settings-container has-scroll">
                <div class="account-settings">
                    <div class="setting-box">
                        <p class="setting-label">
                            Fullname
                        </p>
                        <div class="setting-value">
                            <?= $fullname ?>
                        </div>
                    </div>
                    <div class="setting-box">
                        <p class="setting-label">
                            Email Address
                        </p>
                        <div class="setting-value">
                            <?= $emailAddress ?>
                        </div>
                    </div>
                    <div class="setting-box">
                        <p class="setting-label">
                            Gender
                        </p>
                        <div class="setting-value" title='{$genderDescription}'>
                            <?= $gender ?>
                        </div>
                    </div>
                    <div class="setting-box">
                        <p class="setting-label">
                            Role
                        </p>
                        <div class="setting-value">
                            <?= $userRole ?>
                        </div>
                    </div>
                    <div class="setting-box">
                        <p class="setting-label">
                            Account Created On
                        </p>
                        <div class="setting-value">
                            <?= $accountCreatedOn ?>
                        </div>
                    </div>
                </div>
                <div class="change-password">
                    <form>
                        <div class="input-wrapper">
                            <input type="text" name="current-password" id="current-password" placeholder="Current password">
                        </div>
                        <div class="input-wrapper">
                            <input type="text" name="create-password" id="create-password" placeholder="Create new password">
                        </div>
                        <div class="input-wrapper">
                            <input type="text" name="confirm-password" id="confirm-password" placeholder="Confirm new password">
                        </div>
                        <button type="submit">
                            Change Password
                        </button>
                    </form>
                </div>
                <div class="log-records has-scroll">
                    <h1>User Log Records</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    <div>
                                        Time
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        Type
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        Status
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                                <td>
                                    <div class="log-time">
                                        23hrs ago...
                                    </div>
                                </td>
                                <td>
                                    <div class="log-type">
                                        Logged In
                                    </div>
                                </td>
                                <td>
                                    <div class="log-status">
                                        <p class="stat success">
                                            Success
                                        </p>
                                    </div>
                                </td>
                            </tr> -->
                            <?php 
                            if (empty($logRecords)) {
                                echo "<tr>";
                                echo "<td colspan='5'>";
                                echo "<div class='err-log-rec' style='text-align: center; font-family: var(--font-roboto); font-weight: 500; font-size: 1.6em; font-color: var(--dark-2);'>";
                                echo "No log records";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            } else {
                                foreach ($logRecords as $log) {
                                    $stat = strtolower($log["LogStatus"]) ?? null;
                                    echo "<tr>";
                                    echo "<td>";
                                    echo "<div class='log-time'>";
                                    echo implode(", ", $middleware->getTimeDiff($log["LogTime"]));
                                    echo "</div>";
                                    echo "</td>";
                                    echo "<td>";
                                    echo "<div class='log-type'>";
                                    echo $log["LogType"];
                                    echo "</div>";
                                    echo "</td>";
                                    echo "<td>";
                                    echo "<div class='log-status'>";
                                    echo "<p class='stat {$stat}'>";
                                    echo strtoupper($stat);
                                    echo "</p>";
                                    echo "</div>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="clean-log-history-btn">
                        Clean Log History
                </button>
            </div>

        </div>
    </div>
</body>
<script src="/js/subjectTeacher/ui.js"></script>
</html>
