/**
 * SPMB Admin User Management Interactions
 */

function openAddUserModal() {
    const modal = document.getElementById('add-user-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeAddUserModal() {
    const modal = document.getElementById('add-user-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Close modal on clicking outside or escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddUserModal();
    }
});
