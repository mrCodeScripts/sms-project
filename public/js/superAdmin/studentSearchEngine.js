"use strict";

const searchStudentWorker = new Worker("/../workers/studentSearch.js");
const input = document.getElementById("student-search");
const studentContainer = document.querySelector(".students-container");

input.addEventListener("input", function () {
    const query = input.value.trim();
    if (query.length === 0) {
        studentContainer.innerHTML = ""; // Clear on empty input
        return;
    }

    searchStudentWorker.postMessage(query);
});

searchStudentWorker.onmessage = function (e) {
    console.log(e);
    const data = e.data;
    console.log(data);

    // Clear existing students before rendering new results
    studentContainer.innerHTML = "";

    if (data.error) {
        studentContainer.innerHTML = `<div class='alert-no-students'>
            <p class='alert-msg'>${data.error}</p>
            <div class='img-icon'>
                <img src='/assets/img/UnknownUser.icon.png' alt='Unknown user...'>
            </div>
        </div>`;
        return;
    }

    if (data.length === 0) {
        studentContainer.innerHTML = `<div class='alert-no-students'>
            <p class='alert-msg'>No Student found...</p>
            <div class='img-icon'>
                <img src='/assets/img/UnknownUser.icon.png' alt='Unknown user...'>
            </div>
        </div>`;
        return;
    }

    data.forEach(student => {
        const profile = student.ProfilePic || "/assets/img/unknownStudent.icon.png";

        const studentHTML = `
            <div class='containers'>
                <div class='profile'>
                    <img src='${profile}' alt=''>
                </div>
                <p class='inf-title-wrapper'>
                    <span class='inf-title'>LRN: </span>
                    <span class='inf-title-value'>${student.LRN}</span>
                </p>
                <p class='inf-name'>
                    ${student.Lastname}, ${student.Firstname} ${student.Middlename}
                </p>
                <button type='button' class='view-inf-btn' value='${student.LRN}'>
                    View Student Data
                </button>
            </div>
        `;

        studentContainer.innerHTML += studentHTML;
    });
};
