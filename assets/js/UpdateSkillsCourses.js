function updateSkillsCourses() {
    container = document.getElementById("exercise_skills");
    inputs = container.querySelectorAll("input");
    let checkedInputsValue = [];
    inputs.forEach(input => {
        if(input.checked){
            checkedInputsValue.push(input.value);
        }
    });
    var courses = document.getElementById("exercise_course");
    var courseId = courses.value;

    courses.addEventListener("change", function() {
        startProcess(courses, checkedInputsValue)
    });
    startProcess(courses, checkedInputsValue);
}

function startProcess(courses, checkedInputsValue){
    courseId = courses.value;
    var container = document.getElementById("exercise_skills");

        fetch("/getSkillsCourse/" + courseId, {
            method: "GET",
        })
        .then(response => {
            if (response.ok) {
                return response.json(); // Parse response as JSON
            }
            throw new Error("Network response was not ok.");
        })
        .then(data => {
            updateCheckboxes(data, checkedInputsValue);
            container.classList.remove("doNotShow");
        })
        .catch(error => {
            // Handle error here
        });
}

function updateCheckboxes(data, checkedInputsValue) {
    var container = document.getElementById("exercise_skills");
    container.innerHTML = ""; // Clear existing checkboxes

    if (data.length === 0) {
        var skillsNotFoundDiv = document.createElement("div");
        skillsNotFoundDiv.setAttribute("class", "skillsNotFound");
        var p = document.createElement("p");
        p.textContent = "Aucunes compétences trouvées.";
        
        skillsNotFoundDiv.appendChild(p);
        container.appendChild(skillsNotFoundDiv);
        container.classList.add("!block");
    } else {
        data.forEach(item => {
            var checkbox = document.createElement("input");
            checkbox.setAttribute("type", "checkbox");
            checkbox.setAttribute("id", "exercise_skills_" + item.id);
            checkbox.setAttribute("name", "exercise[skills][]");
            checkbox.setAttribute("class", "mr-2");
            checkbox.setAttribute("value", item.id);

            var label = document.createElement("label");
            label.setAttribute("for", "exercise_skills_" + item.id);
            label.setAttribute("class", "block text-gray-800");
            label.textContent = item.name;

            var div = document.createElement("div");
            div.setAttribute("class", "flex items-center");
            div.appendChild(checkbox);
            div.appendChild(label);

            container.appendChild(div);

            // Check the checkbox if its value exists in checkedInputsValue
            if (checkedInputsValue.includes(item.id.toString())) {
                checkbox.checked = true;
            }
        });
        container.classList.remove("!block");
    }
}

document.getElementById("exercise_course") && updateSkillsCourses();
