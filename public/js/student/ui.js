"use strict";

const miniBarIcon = document.getElementById("click-mini-bar");
const mainBarIcon = document.getElementById("main-mini-icon-sidebar");
const dashboard = document.querySelector(".ui_dashboard");
const logoutBtn = document.getElementById("logout");
const modal = document.querySelector(".modal > .inner-modal #modal-message");
const errModal = document.querySelector(".err-modal > .inner-err-modal #err-modal-message");
const body = document.body;

if (miniBarIcon) {
    miniBarIcon.addEventListener("click", () => {
        if (!dashboard.classList.contains("click-sidebar")) {
            dashboard.classList.add("click-sidebar");
            miniBarIcon.classList.add("hidden");
        } 
        return;
    });
}

if (mainBarIcon) {
    mainBarIcon.addEventListener("click", () => {
        if (dashboard.classList.contains("click-sidebar")) {
            dashboard.classList.remove("click-sidebar");
            miniBarIcon.classList.remove("hidden");
        } 
        return;
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

async function errorModal (message, removeTime) {
    body.classList.add("err");
    errModal.innerText = message;
    if (errModal) {
        errModal.innerText = message;
    }
    setTimeout(() => {
        body.classList.remove("err"); 
    }, removeTime);
    return true;
}

if (logoutBtn) {
    logoutBtn.addEventListener("click", async () => {
        const _res = await fetch("/request/logout", {method: "POST"});
        const _json = await _res.json();
        if (_json.status == "success") {
            const done = await successModal(_json.message, 1000);
            console.log(done);
            if (_json.refresh && done) location.reload();
        }
        if (_json.status == "failed") {
            errorModal(_json.message, 1000);
        }
    });
}