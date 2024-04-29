function NotifyErrorsSubmitExercise() {
    let tabs = document.querySelectorAll("#navigation > div");
    let tabHeaders = document.querySelectorAll("#default-tab button");
    let i=0;
    tabs.forEach(function (tab) {
        if (tab.querySelector(".text-red-700")){
            tabHeaders[i].classList.add("alert");
        }
        i++;
    });
}

document.getElementById("navigation") && NotifyErrorsSubmitExercise();
