<?php 
    $userRoles = $userAccountController->getUserRoles() ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/variable.css">
    <link rel="stylesheet" href="/css/auth.page/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="/assets/img/ZSNHS.icon.png" type="image/x-icon">
    <title>ZSNHS Login Portal</title>
</head>
<body class="active">
    <div class="ui has-scroll">
        <div class="modal" id="modal">
            <p id="modal-message"></p>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#FFFFFF"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>      
            </div>
        </div>
        <div class="err-modal" id="err-modal">
            <p id="err-modal-message"></p>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m40-120 440-760 440 760H40Zm138-80h604L480-720 178-200Zm302-40q17 0 28.5-11.5T520-280q0-17-11.5-28.5T480-320q-17 0-28.5 11.5T440-280q0 17 11.5 28.5T480-240Zm-40-120h80v-200h-80v200Zm40-100Z"/></svg>
            </div>
        </div>
        <div class="school-logo">
            <img src="/assets/img/ZSNHS.icon.png" alt="The Zamboanga del Sur National High School's logo.">
        </div>
        <h1 class="greetings">
            Login and lets start your day!
        </h1>
        <div class="form-wrapper">
            <form id="login-form" class="form">
                <div class="input-wrapper" id="email-holder">
                    <input type="text" name="login-email" placeholder="Email Address" oninput="removeAlert('email')">
                    <div class="icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <label for="login-email" class="error">
                    </label>
                </div>
                <div class="input-wrapper password-holder" id="pwd-holder">
                    <input type="password" name="login-password" placeholder="Password" class="pwd-input" oninput="removeAlert('password')">
                    <div class="icon">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <label for="login-pwd" class="error">
                    </label>
                </div>
                <div class="input-wrapper" id="role-holder">
                    <select name="login-role" id="login-role" oninput="removeAlert('role')">
                        <?php 
                            if (empty($userRoles)) {
                                echo "<option value='' selected hidden>";
                                echo "No Roles Available";
                                echo "</option>";
                            } else {
                                foreach ($userRoles as $role) {
                                    echo "<option value='{$role["role_id"]}' 
                                    title='{$role["role_description"]}'>";
                                    echo $role["role_name"]; 
                                    echo "</option>";
                                }
                            }
                        ?>
                    </select>
                    <div class="icon-absolute">
                        <i class="fa-solid fa-caret-right"></i>
                    </div>
                    <label for="login-role" class="error">
                    </label>
                </div>
                <p class="link">Forgot your password? <a href="/view/forgotpassword">Click Here</a></p>
                <button type="submit">
                    Login
                </button>
                <p class="link centered">
                    Dont have an account yet? 
                    <a href="/views/signup">
                        Create an account
                    </a>
                </p>
            </form>
        </div>
    </div>
</body>
<script src="/js/auth.page/login.js"></script>
<script src="/js/main.js"></script>
</html>