import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        this.observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                this.observer.unobserve(entry.target);
            });
        }, { threshold: 0.08 });
        this.element.querySelectorAll('.reveal').forEach(element => {
            element.classList.add('will-reveal');
            this.observer.observe(element);
        });
    }

    disconnect() {
        this.observer?.disconnect();
    }
}
