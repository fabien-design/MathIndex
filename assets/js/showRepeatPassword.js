
(document.querySelector(".user_password_first") &&  document.querySelector(".user_password_second")) && (() => {
    let firstPassword = document.querySelector(".user_password_first");
    let secondPassword = document.querySelector(".user_password_second");
    secondPassword.parentElement.style.height = "0px";
    secondPassword.parentElement.style.opacity = "0";
    secondPassword.parentElement.style.marginBottom = "0px";
    secondPassword.parentElement.style.pointerEvents = "none";
    firstPassword.addEventListener("input", function(e){
        if(firstPassword.value != ""){
            secondPassword.parentElement.style.height = "auto";
            secondPassword.parentElement.style.opacity = "1";
            secondPassword.parentElement.style.marginBottom = "1.5rem";
            secondPassword.parentElement.style.pointerEvents = "auto";
        }else{
            secondPassword.parentElement.style.height = "0px";
            secondPassword.parentElement.style.opacity = "0";
            secondPassword.parentElement.style.marginBottom = "0px";
            secondPassword.parentElement.style.pointerEvents = "none";
        }
    });
})();
