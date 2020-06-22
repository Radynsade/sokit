window.onload = () => {
    Object.values(document.forms).map(form => {
        form.addEventListener('submit', event => {
            event.preventDefault();
            form.method = 'POST';
            form.action = './handler.php';

            const viewField = document.createElement('input');

            viewField.type = 'hidden';
            viewField.name = 'view';
            viewField.value = document.querySelector('main').id;

            form.appendChild(viewField);
            form.submit();
        });
    });
}
