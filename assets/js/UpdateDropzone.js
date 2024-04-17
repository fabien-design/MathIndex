function UpdateDropzone(value){
    let dropzones = document.querySelectorAll(".dropzoneContainerBlock");
    dropzones.forEach(element => {
        element.querySelector(".dropzoneInput").addEventListener('dragover', function (e) {
            element.querySelector(".dropzone-container").classList.add("isHovered");
        });
        element.querySelector(".dropzoneInput").addEventListener('dragleave', function (e) {
            element.querySelector(".dropzone-container").classList.remove("isHovered");
        });
        element.querySelector(".dropzoneInput").addEventListener('drop', function (e) {
            element.querySelector(".dropzone-container").classList.remove("isHovered");
        });
    });
}

(document.getElementById("navigation")) && UpdateDropzone();



