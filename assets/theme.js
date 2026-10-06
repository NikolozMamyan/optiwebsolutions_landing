(() => {
    try {
        const savedTheme = localStorage.getItem('optiwebsolutions-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.dataset.theme = savedTheme === 'dark' || (savedTheme !== 'light' && prefersDark) ? 'dark' : 'light';
    } catch {
        document.documentElement.dataset.theme = 'light';
    }
})();
