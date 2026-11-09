import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['form', 'fullName', 'success', 'successTitle'];
    static values = { urls: Array, nameError: String };

    open(event) {
        const link = event.target.closest('a[href]');
        if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        if (link.target === '_blank' || link.hasAttribute('hreflang') || link.hasAttribute('data-contact-page')) return;

        const url = new URL(link.href);
        if (url.origin !== window.location.origin || !this.urlsValue.includes(url.pathname)) return;

        event.preventDefault();
        if (this.element.open) return;

        this.trigger = link;
        this.formTarget.reset();
        this.fullNameTarget.setCustomValidity('');
        this.formTarget.hidden = false;
        this.successTarget.hidden = true;
        this.element.setAttribute('aria-labelledby', 'contact-modal-title');
        this.element.setAttribute('aria-describedby', 'contact-modal-intro');
        document.documentElement.classList.add('contact-modal-open');
        this.element.showModal();
        this.fullNameTarget.focus({ preventScroll: true });
    }

    submit(event) {
        event.preventDefault();
        this.fullNameTarget.setCustomValidity(this.fullNameTarget.value.trim() ? '' : this.nameErrorValue);
        if (!this.formTarget.reportValidity()) return;

        this.formTarget.hidden = true;
        this.successTarget.hidden = false;
        this.element.setAttribute('aria-labelledby', 'contact-modal-success-title');
        this.element.setAttribute('aria-describedby', 'contact-modal-success-description');
        this.successTitleTarget.focus({ preventScroll: true });
    }

    clearValidation() {
        this.fullNameTarget.setCustomValidity('');
    }

    keydown(event) {
        if (event.key !== 'Tab') return;

        const controls = [...this.element.querySelectorAll('a[href], button, input, textarea')]
            .filter(control => !control.disabled && control.getClientRects().length);
        const first = controls[0];
        const last = controls.at(-1);

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    }

    backdrop(event) {
        if (event.target === this.element) this.close();
    }

    close() {
        this.element.close();
    }

    closed() {
        document.documentElement.classList.remove('contact-modal-open');
        if (this.trigger?.isConnected) this.trigger.focus({ preventScroll: true });
    }

    disconnect() {
        if (this.element.open) this.element.close();
        document.documentElement.classList.remove('contact-modal-open');
    }
}
