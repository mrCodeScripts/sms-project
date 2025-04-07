"use strict";
const passwordHolders = document.querySelectorAll(".password-holder");
if (passwordHolders) {
    passwordHolders.forEach(e => {
        const inputPassword = e.querySelector(".pwd-input");
        e.querySelector(".icon").addEventListener("click", (f) => {
            const target = f.target;
            if (target.classList.contains("fa-eye")) {
                target.classList.remove("fa-eye");
                target.classList.add("fa-eye-slash");
                if (inputPassword) {
                    inputPassword.type = "text";
                }
            } else {
                target.classList.add("fa-eye");
                target.classList.remove("fa-eye-slash");
                if (inputPassword) {
                    inputPassword.type = "password";
                }
            }
        });
    });
}