
document.querySelector(".keywordsContainer") && (() => {
    document.querySelectorAll(".keywordsContainer").forEach((element) => {
        let keywords = element.innerText;
        let splittedKeywords = keywords.split("@");
        element.innerHTML = "";
        splittedKeywords.forEach((keyword) => {
            element.innerHTML += `<span class="keyword">${keyword}</span>`;
        });
    });
})();

