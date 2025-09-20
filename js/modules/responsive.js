export function setupResponsiveSidebar() {
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebar = document.querySelector('.sidebar');

        if (!toggleBtn || !sidebar) return; // Só executa se os elementos existirem

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-visible');
        });
    }