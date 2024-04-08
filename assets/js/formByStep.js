function formByStep() {
    let step = window.location.search

    if (!step){
        document.getElementById('bloc1').classList.remove('hidden')
    }

    if (!step.startsWith("?page=")){
        document.getElementById('bloc1').classList.remove('hidden')
    }

    let $step = parseInt(step.substring(6));
    switch ($step){
        case 1:
            document.querySelectorAll('.form-block').forEach((block) => {
                block.classList.add('hidden')
            });
            document.getElementById('bloc1').classList.remove('hidden')
            break;
        case 2:
            document.querySelectorAll('.form-block').forEach((block) => {
                block.classList.add('hidden')
            });
            document.getElementById('bloc2').classList.remove('hidden')
            break;
        case 3:
            document.querySelectorAll('.form-block').forEach((block) => {
                block.classList.add('hidden')
            });
            document.getElementById('bloc3').classList.remove('hidden')
            break;
        default:
            document.querySelectorAll('.form-block').forEach((block) => {
                block.classList.add('hidden')
            });
            document.getElementById('bloc1').classList.remove('hidden')
            break;
    }
}

document.getElementById('form-new-exercise') && formByStep();
