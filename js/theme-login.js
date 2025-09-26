document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.getElementById('theme-toggle'); 
    
    const applyTheme = () => {
        const currentTheme = localStorage.getItem('theme') || 'light-mode';
        document.body.className = '';
        document.body.classList.add(currentTheme);

        if (themeToggleBtn) { 
            if (currentTheme === 'dark-mode') {
                themeToggleBtn.classList.add('active');
            } else {
                themeToggleBtn.classList.remove('active');
            }
        }
    };

    applyTheme();

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const isDarkMode = document.body.classList.contains('dark-mode');
            const newTheme = isDarkMode ? 'light-mode' : 'dark-mode';
            localStorage.setItem('theme', newTheme);
            applyTheme(); 
        });
    }
});