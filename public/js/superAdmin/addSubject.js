"use strict";
const addSubject = document.getElementById("add-subject");
const subjvalId = document.querySelectorAll(".subj-val-id");
const removeBtnSubj = document.querySelectorAll(".rem-subj-btn");
const subjectBoxes = document.querySelectorAll(".subject-box");
if (addSubject) {
addSubject.addEventListener("submit", async (e) => {
    e.preventDefault();
    const target = e.target;
    const formData = new FormData(target);
    const _res = await fetch("/request/addSubject", {
        method: "POST",
        body: formData
    });
    const _json = await _res.json();
    if (_json.status && _json.refresh) {location.reload()}
})};
if (subjvalId && removeBtnSubj) {
    removeBtnSubj.forEach((e, i) => {
        e.addEventListener("click", async (e) => {
            const data = {"subject-id": subjvalId[i].value};
            const _res = await fetch("/request/removeSubject", 
            {method: "POST", body: JSON.stringify(data)});
            const _json = await _res.json();
            console.log(_json);
            if (_json.status == "success" && _json.removeElement) {
                subjectBoxes[i].style.display = "none";
            }
        });
    })
};