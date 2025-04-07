"use strict";

const miniBarIcon = document.getElementById("click-mini-bar");
const mainBarIcon = document.getElementById("main-mini-icon-sidebar");
const dashboard = document.querySelector(".ui_dashboard");
const logoutBtn = document.getElementById("logout");

miniBarIcon.addEventListener("click", () => {
    if (!dashboard.classList.contains("click-sidebar")) {
        dashboard.classList.add("click-sidebar");
        miniBarIcon.classList.add("hidden");
    } 
    return;
});

mainBarIcon.addEventListener("click", () => {
    if (dashboard.classList.contains("click-sidebar")) {
        dashboard.classList.remove("click-sidebar");
        miniBarIcon.classList.remove("hidden");
    } 
    return;
});

logoutBtn.addEventListener("click", async () => {
    const _res = await fetch("/request/logout", {method: "POST"});
    const _json = await _res.json();
    console.log(_json);
    if (_json.status == "success") {
        alert(_json.message);
        if (_json.refresh) location.reload();
    }
    if (_json.status == "failed") {
        alert(_json.message);
    }
});