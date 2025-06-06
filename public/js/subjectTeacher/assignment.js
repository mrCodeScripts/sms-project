"use strict";

const createNewAssig = document.querySelector(".create-new-assig");
const assignments = document.querySelector(".assignments");
const closeAssigBtn = document.querySelector(".close-assig-btn");
const assigClass = document.getElementById("assig-class");
const assigSubject = document.getElementById("assig-subject");
const addAssigForm = document.getElementById("form-add-assig");
const viewSubmissionsBtn = document.querySelectorAll(".view-submission-btn");
const closeViewSubmissionbtn = document.querySelector(".close-submit-assig-modal");
const submitAssigContainer = document.querySelector(".view-submitted-assig-modal .submitted-assig-container .containers");
const scoreSubmittedAssig = document.querySelectorAll(".add-assignment-score .add-assig-score-form");
if (createNewAssig) {
    createNewAssig.addEventListener("click", () => {
        if (assignments.classList.contains("closeModalAddAssig")) {
            assignments.classList.remove("closeModalAddAssig");
        }
    });
}
if (closeAssigBtn) {
    closeAssigBtn.addEventListener("click", () => {
        if (!assignments.classList.contains("closeModalAddAssig")) {
            assignments.classList.add("closeModalAddAssig");
        }
    });
}

function renderAssigBoxTo(targetSelector, { assigId, name, filePath, time, section, note, LRN}) {
    targetSelector.insertAdjacentHTML("beforeend", `
        <div class="assig-box">
            <div class="upper-controll">
                <p class="student-name">${name}</p>
                <a href="/download/file?path=${filePath}">
                    <i class="fa-solid fa-link"></i>
                </a>
            </div>
            <div class="mid-controll">
                <p class="date-submitted">
                    <span><i class="fa-solid fa-clock"></i></span>
                    <span class="time">${time}</span>
                </p>
                <span class="dot">⦁</span>
                <p class="submitted-section">${section}</p>
            </div>
            <div class="lower-controll">
                <div class="note">${note}</div>
            </div>
            <div class='add-assignment-score'>
                <form class='add-assig-score-form'>
                    <input type='hidden' name='student-lrn-score' id='student-lrn-score' value='${LRN}'>
                    <input type='hidden' name='submitted-assig-id' id='submitted-assig-id' value='${assigId}'>
                    <input type='number' name='add-assignment-score' id='add-assignment-score' placeholder='Add assignment score...'>
                    <button type='submit'>Add Score</button>
                </form>
            </div>
        </div>
    `);
}

submitAssigContainer.addEventListener("submit", async (e) => {
    e.preventDefault();
    const has = e.target.classList.contains("add-assig-score-form") ?? false;
    if (has) {
        const target = e.target;
        const formData = new FormData(target);
        const _res = await fetch("/request/subjectTeacher/addScore", {
            method: "POST",
            body: formData
        });
        const _json = await _res.json();
        console.log(_json);
    }
});

async function initiateSubjectSelector () {
    const data = assigClass.value;
    const _res = await fetch("/request/subjectTeacher/getClassSubject", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({"classId": data}),
    });
    const _json = await _res.json();
    assigSubject.innerHTML = "";
    if (_json) {
        _json.forEach((e, i) => {
            assigSubject.innerHTML += `<option value='${e.SubjectId}'>${e.SubjectName}</option>`;
        });
    }
}
initiateSubjectSelector();
assigClass.addEventListener("change", async (e) => {initiateSubjectSelector()});
addAssigForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const target = e.target;
    const formData = new FormData(target);
    const _res = await fetch("/request/subjectTeacher/addAssignment", {
        method: "POST",
        body: formData,
    }); 
    const _json = await _res.json();
    console.log(_json);
    if (_json.status == "success") {
        window.alert(_json.message);
    }
});
if (viewSubmissionsBtn) {
    viewSubmissionsBtn.forEach((e, i) => {
        e.addEventListener("click", async () => {
            if (assignments.classList.contains("closeSubmittedassigModal")) {
                assignments.classList.remove("closeSubmittedassigModal");
            }
            submitAssigContainer.innerHTML = "";
            const data = {assignment_id: viewSubmissionsBtn[i].dataset.assigid};
            const _res = await fetch("/request/subjectTeacher/getAssignmentSubmissions", {
                method: "POST",
                headers: {"Content-Type":"application/json"},
                body: JSON.stringify(data)
            });
            const _json = await _res.json();
            console.log(_json);
            if (_json.status == "success") {
                _json.renderData.forEach((e, i) => {
                    renderAssigBoxTo(submitAssigContainer, {
                        assigId: e.SubmissionId,
                        name: `${e.StudentLastname}, ${e.StudentMiddlename} ${e.StudentFirstname}`,
                        filePath: e.FileStoredName,
                        time: e.SubmittedOn,
                        section: e.SectionName,
                        note: e.SubmissionNote,
                        LRN: e.LRN
                    });
                });
            } 
        })
    })
}
if (closeViewSubmissionbtn) {
    closeViewSubmissionbtn.addEventListener("click", () => {
        if (!assignments.classList.contains("closeSubmittedassigModal")) {
            assignments.classList.add("closeSubmittedassigModal");
        }
    });
}