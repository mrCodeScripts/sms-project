"use strict";

const mainContainer = document.querySelector(".main-content-container");
const closeBtnAssig = document.querySelector(".close-btn-assig");
const assignmentInput = document.getElementById("assignment-id");
const submitAssigBtn = document.querySelectorAll(".submit-assig-btn");
const submitAssigForm = document.getElementById("submit-assig-form");
if (closeBtnAssig) {
    closeBtnAssig.addEventListener("click", () => {
        if (!mainContainer.classList.contains("closeModalSubmitAssig")) {
            mainContainer.classList.add("closeModalSubmitAssig");
        }
    });
}
if (submitAssigBtn) {
    submitAssigBtn.forEach((e, i) => {
        e.addEventListener("click", () => {
            if (mainContainer.classList.contains("closeModalSubmitAssig")) {
                mainContainer.classList.remove("closeModalSubmitAssig");
            }
            const data = submitAssigBtn[i].dataset;
            assignmentInput.value = data.assigId;
        });
    });
}
submitAssigForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const target = e.target;
    const formData = new FormData(target);
    const _res = await fetch("/request/student/submitAssignment", {
        method: "POST",
        body: formData 
    });
    const _json  = await _res.json();
    console.log(_json);
    if (_json.status == "success") {
        window.alert(_json.message);
        if (_json.refresh) {
            location.reaload();
        }
    }
});