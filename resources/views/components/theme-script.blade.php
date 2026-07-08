<script>
    function setTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        localStorage.setItem('theme', theme);
        
        // Update icons if they exist
        const iconSun = document.getElementById('theme-icon-sun');
        const iconMoon = document.getElementById('theme-icon-moon');
        if (iconSun && iconMoon) {
            if (theme === 'dark') {
                iconSun.classList.remove('d-none');
                iconMoon.classList.add('d-none');
            } else {
                iconSun.classList.add('d-none');
                iconMoon.classList.remove('d-none');
            }
        }
    }

    function toggleTheme() {
        const currentTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
    }

    // Init theme
    let initTheme = localStorage.getItem('theme');
    if (!initTheme) {
        initTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    setTheme(initTheme);

    document.addEventListener('livewire:navigated', () => {
        const currentTheme = localStorage.getItem('theme') || 'light';
        setTheme(currentTheme);
    });
</script>
