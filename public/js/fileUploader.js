"use strict";

const fileUploaderForm = document.getElementById("file-uploader-form");

fileUploaderForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const target = e.target;
    const formData = new FormData(target);
    const _res = await fetch("/request/upload", {
        method: "POST",
        body: formData
    });
    const _json = await _res.json();
    console.log(_json);
});