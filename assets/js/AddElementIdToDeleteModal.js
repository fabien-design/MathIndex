
(document.querySelectorAll(".open-delete-modal") &&  document.getElementById("submit-delete-btn")) &&
document.querySelectorAll(".open-delete-modal").forEach((element) => {
    element.addEventListener("click", (e) => {
        var id = e.target.dataset.modalElementId;
        document.getElementById("submit-delete-btn").dataset.elementId = id;
    });
});
