"use strict";


// async function fetchStudentData() {
//     try {
//         const response = await fetch('/request/superAdmin/getAllPendingStudents', {method: "POST"});
//         if (!response.ok) {
//             throw new Error('Failed to fetch data');
//         }
//         const students = await response.json();
//         console.log(students);
//         renderStudentData(students);
//     } catch (error) {
//         console.error('Error fetching student data:', error);
//     }
// }

// function renderStudentData(students) {
//     const container = document.getElementById('studentListContainer');
//     container.innerHTML = ''; // Clear previous content

//     students.forEach(student => {
//         const studentBox = document.createElement('div');
//         studentBox.classList.add('student-box');

//         studentBox.innerHTML = `
//             <h3>${student.Firstname} ${student.Lastname}</h3>
//             <p><strong>Age:</strong> ${student.Age}</p>
//             <p><strong>Gender:</strong> ${student.Gender}</p>
//             <p><strong>Birthdate:</strong> ${student.Birthdate}</p>
//             <p><strong>Registration Status:</strong> ${student.Stat}</p>

//             <div class="download-buttons">
//                 <button onclick="downloadFile('${student.birth_certificate_path}')">Download Birth Certificate</button>
//                 <button onclick="downloadFile('${student.enrollment_form_path}')">Download Enrollment Form</button>
//                 <button onclick="downloadFile('${student.form_127_path}')">Download Form 127</button>
//                 <button onclick="downloadFile('${student.good_moral_cert_path}')">Download Good Moral Certificate</button>
//                 <button onclick="downloadFile('${student.report_card_path}')">Download Report Card</button>
//                 <button onclick="downloadFile('${student.student_photo_path}')">Download Photo</button>
//             </div>
//         `;

//         container.appendChild(studentBox);
//     });
// }

// function downloadFile(filePath) {
//     if (!filePath) {
//         alert('No file available to download');
//         return;
//     }
//     window.location.href = filePath; // Trigger file download by redirecting to the file path
// }

// window.onload = fetchStudentData;




const closeBtn = document.querySelector(".close-btn");
const mainContent = document.querySelector(".main-content");
const viewStudentDetails = document.querySelectorAll(".view-student-details");
closeBtn.addEventListener("click", () => {
    if (!mainContent.classList.contains("closemodal")) mainContent.classList.add("closemodal");
});
if (viewStudentDetails) {
    viewStudentDetails.forEach((e, i) => {
        e.addEventListener("click", async () => {
            const _lrn = document.getElementById("lrn-data");
            const _lname = document.getElementById("lname-data");
            const _mname = document.getElementById("mname-data");
            const _fname = document.getElementById("fname-data");
            const _age = document.getElementById("age-data");
            const _gender = document.getElementById("gender-data");
            const _bdate = document.getElementById("bdate-data");
            const _regdate_data = document.getElementById("regdate-data");
            const _birthCert = document.getElementById("birth-cert");
            const _enrForm = document.getElementById("enr-form");
            const _form137 = document.getElementById("form-137");
            const _goodMoralCert = document.getElementById("good-moral-cert");
            const _studentRepoCard = document.getElementById("student-repo-card");
            const _studentPhoto = document.getElementById("student-photo");
            const _approveBTN = document.getElementById("approve-btn-stu");
            const _rejectBTN = document.getElementById("reject-btn-stu");
            const lrn = e.value;
            const _res = await fetch("/request/viewStudentData", {
                method: "POST",
                body: JSON.stringify({stu_lrn: lrn})
            });
            const _json = await _res.json();
            console.log(_json);
            if (_json.status == "success") {
                const sd = _json.serverData;
                if (sd) {
                    _lrn.innerText = sd.LRN;
                    _lname.innerText = sd.Lastname;
                    _mname.innerText = sd.Middlename;
                    _fname.innerText = sd.Firstname;
                    _age.innerText = sd.Age;
                    _gender.innerText = sd.Gender;
                    _bdate.innerText = sd.Birthdate;
                    _regdate_data.innerText = sd.RegisteredOn;
                    _birthCert.href = `/download/file?path=${sd.BirthCert}`;
                    _enrForm.href = `/download/file?path=${sd.EnrollmentForm}`;
                    _form137.href = `/download/file?path=${sd.File137}`;
                    _goodMoralCert.href = `/download/file?path=${sd.GoodMoralCert}`;
                    _studentRepoCard.href = `/download/file?path=${sd.StudentReportCard}`;
                    _studentPhoto.href = `/download/file?path=${sd.StudentPhoto}`;
                    _approveBTN.value = sd.LRN;
                    _rejectBTN.value = sd.LRN;
                } else {return}
            }
            if (mainContent.classList.contains("closemodal") && _json.initiate) mainContent.classList.remove("closemodal");
        }) 
    });
}

const _approveBTN = document.getElementById("approve-btn-stu");
const _rejectBTN = document.getElementById("reject-btn-stu");
_approveBTN.addEventListener("click", async function (e) {
    const value = this.value;
    const data = JSON.stringify({LRN: value});
    const _res = await fetch("/request/superAdmin/approveStudent", {
        method: "POST",
        body: data
    });
    const _json = await _res.json();
    console.log(_json);
    if (_json.status == "success") {
        window.alert(_json.message);
        location.reload();
    } else if (_json.status == "failed") {
        window.alert(_json.message);
    }
})

_rejectBTN.addEventListener("click", async function (e) {
    const value = this.value;
    const data = JSON.stringify({LRN: value});
    const _res = await fetch("/request/superAdmin/rejectStudent", {
        method: "POST",
        body: data
    });
    const _json = await _res.json();
    console.log(_json);
    if (_json.status == "success") {
        window.alert(_json.message);
        location.reload();
    } else if (_json.status == "failed") {
        window.alert(_json.message);
    }
})