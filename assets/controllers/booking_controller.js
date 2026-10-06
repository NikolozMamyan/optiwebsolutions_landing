import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = { link: String, locale: String, unavailable: String };

    connect() {
        if ('IntersectionObserver' in window) {
            this.observer = new IntersectionObserver(entries => {
                if (!entries.some(entry => entry.isIntersecting)) return;
                this.observer.disconnect();
                this.initialize();
            }, { rootMargin: '350px' });
            this.observer.observe(this.element);
        } else {
            this.initialize();
        }
    }

    disconnect() {
        this.observer?.disconnect();
    }

    initialize() {
        const cal = function (...args) { cal.q.push(args); };
        cal.q = [];
        cal.ns = {};
        cal.loaded = true;
        window.Cal = cal;
        cal('init', { origin: 'https://cal.com' });
        cal('inline', {
            elementOrSelector: `#${this.element.id}`,
            calLink: this.linkValue,
            config: { layout: 'month_view', theme: 'light', locale: this.localeValue }
        });
        cal('ui', {
            theme: 'light',
            styles: { branding: { brandColor: '#189f92' } },
            hideEventTypeDetails: false,
            layout: 'month_view'
        });
        const script = document.createElement('script');
        script.src = 'https://app.cal.com/embed/embed.js';
        script.async = true;
        script.addEventListener('error', () => { this.element.textContent = this.unavailableValue; }, { once: true });
        document.head.appendChild(script);
    }
}
