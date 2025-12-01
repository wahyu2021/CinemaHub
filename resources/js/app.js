import './bootstrap';

// Global Delete Modal Functions
window.openDeleteModal = function(actionUrl) {
    const modal = document.getElementById('delete-modal');
    const panel = document.getElementById('delete-modal-panel');
    const form = document.getElementById('delete-confirm-form');
    
    if (!modal || !panel || !form) return;

    form.action = actionUrl;
    modal.classList.remove('hidden');
    
    // Animation frame for smooth transition
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        panel.classList.remove('scale-95', 'opacity-0');
        panel.classList.add('scale-100', 'opacity-100');
    });
}

window.closeDeleteModal = function() {
    const modal = document.getElementById('delete-modal');
    const panel = document.getElementById('delete-modal-panel');
    
    if (!modal || !panel) return;

    modal.classList.add('opacity-0');
    panel.classList.remove('scale-100', 'opacity-100');
    panel.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Initialize event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const deleteModal = document.getElementById('delete-modal');
    
    if (deleteModal) {
        // Close on backdrop click
        deleteModal.addEventListener('click', (e) => {
            if (e.target === e.currentTarget) window.closeDeleteModal();
        });
        
        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                window.closeDeleteModal();
            }
        });
    }
});