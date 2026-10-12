document.addEventListener('DOMContentLoaded', function() {
    const cleanButtons = document.querySelectorAll('.wp-db-opt-clean-btn');
    
    cleanButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if (!confirm(wpDbOptData.confirmMsg)) {
                e.preventDefault();
            }
        });
    });
});
