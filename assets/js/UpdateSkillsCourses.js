function updateSkillsCourses() {
    var courses = document.getElementById("exercise_course");
    var courseId = courses.value;

    console.log(courseId);

    courses.addEventListener("change", function() {
        courseId = courses.value;
        console.log(courseId);
    });

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
        console.log(data);
    })
    .catch(error => {
        console.error("There was a problem with your fetch operation:", error);
        // Handle error here
    });
}

document.getElementById("exercise_course") && updateSkillsCourses();
