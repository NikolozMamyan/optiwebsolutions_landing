import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['toggle'];
    static values = { lightLabel: String, darkLabel: String };

    connect() {
        this.update();
    }

    toggle() {
        const root = document.documentElement;
        root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        try {
            localStorage.setItem('optiwebsolutions-theme', root.dataset.theme);
        } catch {}
        this.update();
    }

    update() {
        const isDark = document.documentElement.dataset.theme === 'dark';
        this.toggleTarget.setAttribute('aria-pressed', String(isDark));
        this.toggleTarget.setAttribute('aria-label', isDark ? this.lightLabelValue : this.darkLabelValue);
        document.querySelector('meta[name="theme-color"]').content = isDark ? '#141617' : '#ffffff';
    }
}
