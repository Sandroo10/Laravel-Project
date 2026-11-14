import './bootstrap';

const validationSummary = document.querySelector('[data-validation-summary]');

if (validationSummary) {
    validationSummary.focus();
}

document.querySelectorAll('[data-auto-submit]').forEach((select) => {
    select.addEventListener('change', () => {
        select.form.requestSubmit();
    });
});

const deleteDialog = document.querySelector('#delete-confirmation');

if (deleteDialog) {
    let pendingDeleteForm = null;

    document.addEventListener('submit', (event) => {
        if (event.target.matches('[data-confirm-delete]')) {
            event.preventDefault();
            pendingDeleteForm = event.target;
            deleteDialog.querySelector('#delete-vacancy-name').textContent = pendingDeleteForm.dataset.vacancyLabel;
            deleteDialog.returnValue = '';
            deleteDialog.showModal();
        }
    });

    deleteDialog.addEventListener('close', () => {
        if (deleteDialog.returnValue === 'delete' && pendingDeleteForm) {
            pendingDeleteForm.submit();
        }

        pendingDeleteForm = null;
    });

    document.querySelectorAll('[data-confirm-delete] button').forEach((button) => {
        button.disabled = false;
    });
}
