import { initFlowbite } from "flowbite";
import { alertsEvent } from "./DeleteAlertPopup";
import { addElementToDeleteModal } from "./AddElementIdToDeleteModal";

async function deleteExercice(button) {
    var currentUrl = window.location.pathname;
    if(currentUrl.slice(-1)== "/") {
        currentUrl = currentUrl.slice(0, -1);
    }
    var elementId = button.target.dataset.elementId;
    var currentEntity = null;
    try {
        if(elementId && elementId != "undefined") {
            const url = '/api'+currentUrl+"/"+elementId+'/delete'
            console.log(url);
            const response = await fetch(url , {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Ajoutez d'autres en-têtes si nécessaire
                },
            });
            if (response.ok) {
                document.querySelector('tr[data-element-id="'+elementId+'"]').remove();  // remove element from table
                const responseData = await response.json();
                const decodedHTML = decodeURIComponent(responseData.html); // decode twig component to html 
                document.querySelector("body").innerHTML += decodedHTML;
                alertsEvent();
                // Call initFlowbite() when the XHR request is successful
                initFlowbite();
                document.getElementById("submit-delete-btn").addEventListener("click", (e) => deleteExercice(e));
                addElementToDeleteModal();

            } else if (response.status === 403){
                
                const responseData = await response.json();
                const decodedHTML = decodeURIComponent(responseData.html);
                document.querySelector("body").innerHTML += decodedHTML;
                alertsEvent();

            } else {
                console.error("Une erreur s'est produite lors de la suppression de l'exercice :", response.status);
            }
        }
    } catch (error) {
        console.error("Une erreur s'est produite lors de la suppression de l'exercice :", error);
    }
}

document.getElementById("submit-delete-btn") && 
document.getElementById("submit-delete-btn").addEventListener("click", (e) => deleteExercice(e));
