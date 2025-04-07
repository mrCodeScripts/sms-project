"use strict";


const loginForm = document.getElementById("login-form");
const body = document.body;
const modal = document.querySelector(".modal > #modal-message");
const errModal = document.querySelector(".err-modal > #err-modal-message");
const inputElements = document.querySelectorAll("input");
const selectElements = document.querySelectorAll("select");

if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const target = e.target;
        const formData = new FormData(target);
        const _res = await fetch("/request/login", {
            method: "POST",
            body: formData
        });
        const _json = await _res.json();
        if (_json.status == "success") {
            const done = await successModal(_json.message, 1000);
            if (done && _json.refresh) location.reload();
        }
        console.log(_json); 
        if (_json.status == "failed") {
            const type = _json.key;
            if (loginForm) loginForm.classList.add("active");
            if (type == "INVALID_PWD") {
                errorAlert(_json.message, "password");
            }
            if (type == "EMAIL_INUSE") {
                errorAlert(_json.message, "email");
            }
            if (type == "USER_NOEXIST") {
                errorAlert(_json.message, "role");
            }
            if (
                type == "INCOMPLETE_DATA" || 
                type == "ALREADY_LOGGED" ||
                type == "USER_NOEXIST"
            ) {
                errorModal(_json.message, 3200);
            }
        }
    });
}


async function successModal (message, removeTime) {
    body.classList.add("success");
    if (modal) {
        modal.innerText = message;
    }
    setTimeout(() => {
       body.classList.remove("success"); 
    }, removeTime);

    return true;
}

function errorModal (message, removeTime) {
    body.classList.add("err");
    errModal.innerText = message;
    inputElements.forEach(i => i.disabled = true);
    selectElements.forEach(i => i.disabled = true);
    if (errModal) {
        errModal.innerText = message;
    }
    setTimeout(() => {
        body.classList.remove("err"); 
        inputElements.forEach(i => i.disabled = false);
        selectElements.forEach(i => i.disabled = false);
    }, removeTime);

    return true;
}

function errorAlert (message, type) {
    if (type == "password") {
        document.querySelector("#pwd-holder label").innerText = message;
    }

    if (type == "email") {
        document.querySelector("#email-holder label").innerText = message;
    }

    if (type == "role") {
        document.querySelector("#role-holder label").innerText = message;
    }
}

function removeAlert (type) {
    if (type == "password") {
        document.querySelector("#pwd-holder .error").innerText = "";
    }

    if (type == "email") {
        document.querySelector("#email-holder .error").innerText = "";
    }

    if (type == "role") {
        document.querySelector("#role-holder .error").innerText = "";
    }
}