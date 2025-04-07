"use strict";
const classManagerDashboard = document.querySelector(".class-manager-dashboard");
const closeBtn = document.querySelector(".add-class-form-modal .close-btn");
const addClassBtn = document.getElementById("add-class-btn");
const editClassBtn = document.querySelectorAll(".controller-edit-class");
const closeEditClassBtn = document.querySelector(".class-form-close-btn");
const addStudentCloseBtn = document.querySelector(".student-list-close-btn");
const addStudentBtn = document.querySelectorAll(".controller-add-student");
const addSelectedStudentBtn = document.querySelector(".add-select-student-btn");
const addToClassForm = document.getElementById("add-student-toclass");
const editTeacherClass = document.querySelectorAll(".controller-edit-teacher-class");
const closeEditTeacherClass = document.querySelector(".class-sched-close-btn");
const addScheduleForm = document.getElementById("add-sched-form");
if (closeBtn) {
    closeBtn.addEventListener("click", () => {
        if (!classManagerDashboard.classList.contains("closemodal")) {
            classManagerDashboard.classList.add("closemodal");
        }
    })
};
if (addClassBtn) {
    addClassBtn.addEventListener("click", () => {
        console.log(1);
        if (classManagerDashboard.classList.contains("closemodal")) {
            classManagerDashboard.classList.remove("closemodal");
        }
    });
}
if (editClassBtn) {
    const _editClassSectionName = document.getElementById("edit-class-section-name");
    const _editClassAdviser = document.getElementById("edit-class-adviser");
    const _editClassStudentLimit = document.getElementById("edit-class-student-limit");
    const _editClassStrand = document.getElementById("edit-class-strand");
    const _editClassRoom = document.getElementById("edit-class-room");
    const _editClassGradeLevel = document.getElementById("edit-class-grade-level");
    editClassBtn.forEach((e, i) => {
        e.addEventListener("click", async (e) => {
            console.log("sthi");
            console.log("shit");
            const target = editClassBtn[i];
            if (classManagerDashboard.classList.contains("closemodalEdit")) {
                classManagerDashboard.classList.remove("closemodalEdit");
            }
            const data = {"classId": target.dataset.classId};
            const _res = await fetch("/request/superAdmin/viewClassData", {
                method: "POST", 
                headers: {"Content-Type":"application/json"}, 
                body: JSON.stringify(data)
            });
            const _json = await _res.json();
            console.log(_json);
            const _classData = _json.basicClassData[0] ?? null;
            const _classStudents = _json.classStudents ?? null;
            _editClassSectionName.value = _classData.SectionName;
            _editClassAdviser.value = _classData.AdviserProfessionalId;
            _editClassStudentLimit.value = _classData.ClassStudentLimit;
            _editClassStrand.value = _classData.StrandId;
            _editClassRoom.value = _classData.RoomId;
            _editClassGradeLevel.value = _classData.GradeLevel;
        });
    });
}
if (closeEditClassBtn) {
    closeEditClassBtn.addEventListener("click", () => {
        if (!classManagerDashboard.classList.contains("closemodalEdit")) {
            classManagerDashboard.classList.add("closemodalEdit");
        }
    });
}
if (addStudentBtn) {
    addStudentBtn.forEach((e, i) => {
        e.addEventListener("click", () => {
        console.log("shit");
            if (classManagerDashboard.classList.contains("closemodalAddStudModal")) {
                classManagerDashboard.classList.remove("closemodalAddStudModal");
            }
            const classIdSelected = addStudentBtn[i].dataset.classId;
            const selectedClassId = document.getElementById("selected-class-id");
            selectedClassId.value = classIdSelected;
        }); 
    });
}
if (addStudentCloseBtn) {
    addStudentCloseBtn.addEventListener("click", () => {
        console.log("aded");
        if (!classManagerDashboard.classList.contains("closemodalAddStudModal")) {
            classManagerDashboard.classList.add("closemodalAddStudModal");
        }
    });
}
function renderSchedules(schedules) {
    const schedListContainer = document.querySelector(".sched-list");
    schedListContainer.innerHTML = "";

    schedules.forEach(schedule => {
        const schedBox = document.createElement("div");
        schedBox.classList.add("sched-box");
        const upperBox = document.createElement("div");
        upperBox.classList.add("upper-box");
        const timeDiv = document.createElement("div");
        timeDiv.classList.add("time");
        timeDiv.textContent = schedule.time_range;
        const iconDiv = document.createElement("div");
        iconDiv.classList.add("icon");
        iconDiv.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>';
        upperBox.appendChild(timeDiv);
        upperBox.appendChild(iconDiv);
        const lowerBox = document.createElement("div");
        lowerBox.classList.add("lower-box");
        const daysDiv = document.createElement("div");
        daysDiv.classList.add("days");
        schedule.days.forEach(day => {
            const daySpan = document.createElement("span");
            daySpan.classList.add(day.toLowerCase());
            daySpan.textContent = day.slice(0, 3);
            daysDiv.appendChild(daySpan);
        });
        const subjectName = document.createElement("p");
        subjectName.classList.add("subject-name");
        subjectName.textContent = schedule.subject_name;
        const subjectTeacher = document.createElement("p");
        subjectTeacher.classList.add("subject-teacher");
        subjectTeacher.textContent = `${schedule.Lastname}, ${schedule.Middlename} ${schedule.Firstname}`;
        lowerBox.appendChild(daysDiv);
        lowerBox.appendChild(subjectName);
        lowerBox.appendChild(subjectTeacher);
        schedBox.appendChild(upperBox);
        schedBox.appendChild(lowerBox);
        schedListContainer.appendChild(schedBox);
    });
}
async function requestRendering(val) {
    const _res = await fetch("/request/superAdmin/viewClassSchedule", {
        method: "POST",
        body: JSON.stringify({classId: val}),
    });
    const _json = await _res.json();
    console.log(_json);
    renderSchedules(_json);
};
if (editTeacherClass) {
    editTeacherClass.forEach((e, i) => {
        e.addEventListener("click", async () => {
            const _hiddenClassId = document.getElementById("hidden-class-id");
            const val = editTeacherClass[i].dataset.classId;
            _hiddenClassId.value = val;
            requestRendering(val);
            if (classManagerDashboard.classList.contains("closemodalSchedEdit")) {
                classManagerDashboard.classList.remove("closemodalSchedEdit");
            }
        });
    })
}
if (closeEditTeacherClass) {
    closeEditTeacherClass.addEventListener("click", () => {
        if (!classManagerDashboard.classList.contains("closemodalSchedEdit")) {
            classManagerDashboard.classList.add("closemodalSchedEdit");
        }
    });
}
if (addScheduleForm) {
    addScheduleForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const target = e.target;
        const formData = new FormData(target);
        const _res = await fetch("/request/superAdmin/addClassSched", {
            method: "POST",
            body: formData
        });
        const _json = await _res.json();
        console.log(_json);
    });
}
addToClassForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const target = e.target;
    const formData = new FormData(target);
    const _res = await fetch("/request/superAdmin/addStudentToClass", {
        method: "POST",
        body: formData
    });
    const _json = await _res.json();
    console.log(_json);
    requestRendering();
});
