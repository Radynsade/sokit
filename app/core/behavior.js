window.onload = () => {
    Object.values(document.forms).map(form => {
        form.addEventListener('submit', event => {
            event.preventDefault();
            const data = new FormData(form);
            console.log(data.getAll('username'));
        });
    });
}
