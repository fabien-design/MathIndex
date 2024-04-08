
export function keywordsSplit(){
    document.querySelectorAll(".keywordsContainer").forEach((element) => {
        let keywords = element.innerText;
        let splittedKeywords = keywords.split("@");
        console.log(splittedKeywords);
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
    let form = document.getElementById("exerciceForm");
    let textarea = document.querySelector(".exerciseKeywords");
    let realTextarea = document.querySelector(".realExerciseKeywords");
    let div = textarea.parentElement;
    let keywordsContainer = div.appendChild(document.createElement("div"));
    keywordsContainer.classList.add("keywordsContainer", "cross");

    form.addEventListener('submit', function (e) {
        if (textarea.value !== "") {
            textarea.dispatchEvent(new Event('focus'));
            textarea.dispatchEvent(new KeyboardEvent('keypress',{'key':'Enter'}));
        }
    });
    


    if(realTextarea.getAttribute("value") && realTextarea.getAttribute("value") != "" && realTextarea.getAttribute("value") != "@"){
        textarea.setAttribute("value", realTextarea.getAttribute("value"));
        let keywords = textarea.getAttribute("value");
        let splittedKeywords = keywords.split("@");
        splittedKeywords.forEach((keyword) => {
            if (keyword != ""){
                let span = document.createElement('span');
                span.classList.add('keyword');
                span.textContent = keyword;
                keywordsContainer.appendChild(span);
                keywordsContainer.style.paddingTop = "10px";
                textarea.value = "";
                let spans = keywordsContainer.querySelectorAll('span');
            spans.forEach((span) => {
                span.addEventListener('click', function (e) {
                    e.target.parentElement.removeChild(e.target);
                    if(realTextarea.getAttribute("value").search('@'+e.target.textContent)){
                        realTextarea.setAttribute("value", realTextarea.getAttribute("value").replace('@' + e.target.textContent, ""))
                    }else{
                        realTextarea.setAttribute("value", realTextarea.getAttribute("value").replace(e.target.textContent, ""))
                    }
                });
            });
            }
        });
    }

    textarea.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            keywordsContainer.style.paddingTop = "10px";
            e.preventDefault();
            let keyword = textarea.value;
            let span = document.createElement('span');
            span.classList.add('keyword');
            span.textContent = keyword;
            keywordsContainer.appendChild(span);
            if(!realTextarea.getAttribute("value")){
                realTextarea.setAttribute('value','@' + keyword);
            }else{
                realTextarea.setAttribute('value', realTextarea.getAttribute('value') + '@' + keyword);
            }
            textarea.value = "";
            let spans = keywordsContainer.querySelectorAll('span');
            spans.forEach((span) => {
                span.addEventListener('click', function (e) {
                    e.target.parentElement.removeChild(e.target);
                    if(realTextarea.getAttribute("value").search('@'+e.target.textContent)){
                        realTextarea.setAttribute("value", realTextarea.getAttribute("value").replace('@' + e.target.textContent, ""))
                    }else{
                        realTextarea.setAttribute("value", realTextarea.getAttribute("value").replace(e.target.textContent, ""))
                    }
                });
            });
        }
    });
}


document.querySelector(".exerciseKeywords") && keywordsToTags();