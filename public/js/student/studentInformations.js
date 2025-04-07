"use strict";

const enrollmentForm = document.getElementById("enrollment-form");
const cloudIcon = document.querySelectorAll(".cloudIcon");
const fileInput = document.querySelectorAll(".file-input");

enrollmentForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const target = e.target;
    const formData = new FormData(target);
    const _res  = await fetch("/request/student/enroll", {
        method: "POST",
        body: formData
    });
    const _json = await _res.json();
    console.log(_json);

    if (_json.status == "success") {
        alert(_json.message);
        if (_json.refresh) {
            location.reload();
        }
    }

    if (_json.status == "failed") {
        alert(_json.message);
    }
});

fileInput.forEach((e, i) => {
    e.addEventListener("change", () => {
        cloudIcon[i].style.color = "var(--success-1)";
    });
});