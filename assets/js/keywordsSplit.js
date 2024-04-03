
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
    console.log(keywordsContainer);
    textarea.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            keywordsContainer.style.paddingTop = "10px";
            e.preventDefault();
            let keyword = textarea.value;
            let span = document.createElement('span');
            span.classList.add('keyword');
            span.textContent = keyword;
            keywordsContainer.appendChild(span);
            textarea.value = "";
            let spans = keywordsContainer.querySelectorAll('span');
            spans.forEach((span) => {
                span.addEventListener('click', function (e) {
                    e.target.parentElement.removeChild(e.target);
                });
            });
        }
    });
}


document.querySelector(".exerciseKeywords") && keywordsToTags();