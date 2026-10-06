import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['toggle'];
    static values = { pauseLabel: String, playLabel: String };

    toggle() {
        const paused = this.element.classList.toggle('is-paused');
        this.toggleTarget.setAttribute('aria-pressed', String(paused));
        this.toggleTarget.setAttribute('aria-label', paused ? this.playLabelValue : this.pauseLabelValue);
        this.toggleTarget.querySelector('span').textContent = paused ? '▷' : 'Ⅱ';
    }
}
