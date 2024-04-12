
document.getElementById("default-tab") && (() => {
    tabs = document.getElementById('default-tab').querySelectorAll("button");
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
        });
    });
})();