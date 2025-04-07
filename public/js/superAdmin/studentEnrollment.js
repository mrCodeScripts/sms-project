const approveBtns = document.querySelectorAll(".approve-btns");
const rejectBtns = document.querySelectorAll(".reject-btns");

approveBtns.forEach(btn => {
    btn.addEventListener("click", async function (e) {
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
});

rejectBtns.forEach(btn => {
    btn.addEventListener("click", async function (e) {
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
});