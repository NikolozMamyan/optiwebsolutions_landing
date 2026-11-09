import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['text', 'status'];

    async copy() {
        try {
            await navigator.clipboard.writeText(this.textTarget.textContent.trim());
            this.statusTarget.textContent = 'Copié !';
        } catch {
            this.statusTarget.textContent = 'Copie indisponible';
        }
    }
}
