window.onload = () => {
    Object.values(document.forms).map(form => {
        form.addEventListener('submit', event => {
            event.preventDefault();
            form.method = 'POST';
            form.action = '/handler.php';

            const viewField = document.createElement('input');

            viewField.type = 'hidden';
            viewField.name = 'viewName';
            viewField.value = document.querySelector('main').id;

            const actionField = document.createElement('input');

            actionField.type = 'hidden';
            actionField.name = 'actionName';
            actionField.value = form.getAttribute('data-action');

            form.prepend(viewField);
            form.prepend(actionField);
            form.submit();
        });
    });
}
