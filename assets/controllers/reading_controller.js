import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['section', 'link', 'progress'];

    connect() {
        this.update = this.update.bind(this);
        window.addEventListener('scroll', this.update, { passive: true });
        window.addEventListener('resize', this.update);
        this.update();
    }

    disconnect() {
        window.removeEventListener('scroll', this.update);
        window.removeEventListener('resize', this.update);
    }

    update() {
        const first = this.sectionTargets[0];
        const last = this.sectionTargets.at(-1);
        const start = first.getBoundingClientRect().top + window.scrollY - 150;
        const end = last.getBoundingClientRect().bottom + window.scrollY - window.innerHeight;
        this.progressTarget.style.transform = `scaleX(${Math.min(1, Math.max(0, (window.scrollY - start) / Math.max(1, end - start)))})`;
        let active = first.id;
        for (const section of this.sectionTargets) {
            if (section.getBoundingClientRect().top <= 180) active = section.id;
        }
        for (const link of this.linkTargets) {
            if (link.hash === `#${active}`) link.setAttribute('aria-current', 'location');
            else link.removeAttribute('aria-current');
        }
    }
}
