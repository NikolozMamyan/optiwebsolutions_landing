import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['toggle', 'nav'];
    static values = { openLabel: String, closeLabel: String };

    connect() {
        this.desktop = window.matchMedia('(min-width: 1201px)');
        this.onResize = () => this.close();
        this.desktop.addEventListener('change', this.onResize);
        const links = [...this.navTarget.querySelectorAll('a')].filter(link => link.pathname === location.pathname && link.hash);
        if ('IntersectionObserver' in window) {
            this.observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    links.forEach(link => {
                        if (link.hash === `#${entry.target.id}`) link.setAttribute('aria-current', 'location');
                        else link.removeAttribute('aria-current');
                    });
                });
            }, { rootMargin: '-15% 0px -55% 0px' });
            links.forEach(link => {
                const section = document.getElementById(link.hash.slice(1));
                if (section) this.observer.observe(section);
            });
        }
    }

    disconnect() {
        this.desktop.removeEventListener('change', this.onResize);
        this.observer?.disconnect();
    }

    toggle() {
        const isOpen = this.toggleTarget.getAttribute('aria-expanded') !== 'true';
        this.toggleTarget.setAttribute('aria-expanded', String(isOpen));
        this.toggleTarget.setAttribute('aria-label', isOpen ? this.closeLabelValue : this.openLabelValue);
        this.navTarget.classList.toggle('is-open', isOpen);
    }

    close() {
        this.toggleTarget.setAttribute('aria-expanded', 'false');
        this.toggleTarget.setAttribute('aria-label', this.openLabelValue);
        this.navTarget.classList.remove('is-open');
    }

    closeOutside(event) {
        if (!this.element.contains(event.target)) this.close();
    }

    escape(event) {
        if (event.key === 'Escape' && this.toggleTarget.getAttribute('aria-expanded') === 'true') {
            this.close();
            this.toggleTarget.focus();
        }
    }
}
