
export function keywordsSplit(){
    document.querySelectorAll(".keywordsContainer").forEach((element) => {
        let keywords = element.innerText;
        let splittedKeywords = keywords.split("@");
        element.innerHTML = "";
        splittedKeywords.forEach((keyword) => {
            if (keyword != ""){
                element.innerHTML += `<span class="keyword">${keyword}</span>`;
            }
        });
    });
}

document.querySelector(".keywordsContainer") && keywordsSplit();


export function keywordsToTags(){
    let textarea = document.querySelector(".exerciseKeywords");
    let div = textarea.parentElement;
    let keywordsContainer = div.appendChild(document.createElement("div"));
    keywordsContainer.classList.add("keywordsContainer");

    textarea.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
          // 
          e.preventDefault();
          let keyword = textarea.value;
          keywordsContainer.appendChild(document.createElement('span'))
          textarea.value = "";
        }
    });
}

document.querySelector(".exerciseKeywords") && keywordsToTags();