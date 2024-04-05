
export function keywordsSplit(){
    document.querySelectorAll(".keywordsContainer").forEach((element) => {
        let keywords = element.innerText;
        let splittedKeywords = keywords.split("@");
        console.log(splittedKeywords);
        element.innerHTML = "";
        splittedKeywords.forEach((keyword) => {
            element.innerHTML += `<span class="keyword">${keyword}</span>`;
        });
    });
}

document.querySelector(".keywordsContainer") && keywordsSplit();

