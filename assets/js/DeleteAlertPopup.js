function alertsEvent(){
    const alerts = document.querySelectorAll('[role="alert"]');

    alerts.forEach((alert) => {
        alert.addEventListener('click', () => {
            alert.style.animation = 'slideUp 0.5s forwards';
            setTimeout(() => alert.remove(), 510);
        });
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('[role="alert"]')) {
            alerts.forEach((alert) => {
                alert.style.animation = 'slideUp 0.5s forwards';
                setTimeout(() => alert.remove(), 510);
            });
        }
    });


}

document.querySelectorAll('[role="alert"]') && alertsEvent();
