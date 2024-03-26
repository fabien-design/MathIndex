import { initFlowbite } from 'flowbite'
import { keywordsSplit } from './keywordsSplit';


class AdminSearch {
    constructor() {
        this.init();
    }
    init(){
        const TableContent = document.querySelector("tbody").innerHTML;
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('researchForm');
    
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                let currentUrl = window.location.pathname;
                if(currentUrl.slice(-1)== "/") {
                    currentUrl = currentUrl.slice(0, -1);
                }
                const entity = currentUrl.replace('/administration/','');
                const input = document.getElementById('search');
                const query = input.value;
    
                // Make a fetch request to your Symfony controller endpoint
                fetch(`/administration/search?entity=${entity}&query=${query}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        // Add any additional headers if needed
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // You can update your HTML elements here with the fetched data
                    document.querySelector("tbody").innerHTML = data.values;

                    //AddElementIdToDeleteModal.js 
                    document.querySelectorAll(".open-delete-modal").forEach((element) => {
                        element.addEventListener("click", (e) => {
                            var id = e.target.dataset.modalElementId;
                            document.getElementById("submit-delete-btn").dataset.elementId = id;

                        });
                    });
                    initFlowbite(); // refresh data-modal attribute
                    document.querySelector(".keywordsContainer") && keywordsSplit(); // refresh keywords attribute
                })
                .catch(error => {
                    document.querySelector("tbody").innerHTML = TableContent;
                });
            });
        });
    }

}


(document.getElementById('researchForm') && window.location.pathname.startsWith('/administration')) && new AdminSearch();
