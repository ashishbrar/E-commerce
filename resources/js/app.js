document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const mobileToggle = document.querySelector('[data-sidebar-toggle]');

    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            body.classList.toggle('sidebar-open');
        });
    }

    document.querySelectorAll('[data-sidebar-close]').forEach((button) => {
        button.addEventListener('click', () => {
            body.classList.remove('sidebar-open');
        });
    });

    document.querySelectorAll('[data-navbar]').forEach((navbar) => {
        const syncNavbarState = () => {
            navbar.classList.toggle('is-scrolled', window.scrollY > 24);
        };

        syncNavbarState();
        window.addEventListener('scroll', syncNavbarState, { passive: true });
    });
});
