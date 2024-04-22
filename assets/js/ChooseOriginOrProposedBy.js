export function hideOriginOrProposedBy(){
    let origin = document.getElementById("exercise_origin");
    let proposedBy = document.getElementById("exercise_proposedByType");

    let originBlock = document.querySelector(".originBlock");
    let proposedByBlock = document.querySelector(".proposedByBlock");

    origin.addEventListener('change', function (e) {
        hideProposedBy()
    });

    function hideProposedBy(){
        if (origin.value != ""){
            proposedBy.value = "";
            originBlock.classList.remove("disabledBlock");
            proposedByBlock.classList.add("disabledBlock");
            document.getElementById("exercise_proposedByLasName").setAttribute("disabled", "disabled");
            document.getElementById("exercise_proposedByFirstName").setAttribute("disabled", "disabled");

            
            document.getElementById("exercise_originName").removeAttribute("disabled");
            document.getElementById("exercise_originInformation").removeAttribute("disabled");

        }
    }
    hideProposedBy()

    proposedBy.addEventListener('change', function (e) {
        hideOrigin()
    });

    function hideOrigin(){
        if (proposedBy.value != ""){
            origin.value = "";
            proposedByBlock.classList.remove("disabledBlock");
            originBlock.classList.add("disabledBlock");
            document.getElementById("exercise_originName").setAttribute("disabled", "disabled");
            document.getElementById("exercise_originInformation").setAttribute("disabled", "disabled");

            document.getElementById("exercise_proposedByLasName").removeAttribute("disabled");
            document.getElementById("exercise_proposedByFirstName").removeAttribute("disabled");
        }
    }
    hideOrigin()
}

(document.getElementById("navigation")) && hideOriginOrProposedBy();
