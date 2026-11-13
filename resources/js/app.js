import './bootstrap';

const deleteDialog = document.querySelector('#delete-confirmation');

if (deleteDialog) {
    let pendingDeleteForm = null;

    document.addEventListener('submit', (event) => {
        if (event.target.matches('[data-confirm-delete]')) {
            event.preventDefault();
            pendingDeleteForm = event.target;
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
