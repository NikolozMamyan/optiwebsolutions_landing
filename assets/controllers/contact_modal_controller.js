import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['form', 'firstName', 'lastName', 'success', 'successTitle', 'error', 'submitButton'];
    static values = { urls: Array, nameError: String, errorMessage: String, rateLimitMessage: String, csrfMessage: String };

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
        for (const field of this.formTarget.elements) field.setCustomValidity?.('');
        this.errorTarget.hidden = true;
        this.submitButtonTarget.disabled = false;
        this.formTarget.hidden = false;
        this.successTarget.hidden = true;
        this.element.setAttribute('aria-labelledby', 'contact-modal-title');
        this.element.setAttribute('aria-describedby', 'contact-modal-intro');
        document.documentElement.classList.add('contact-modal-open');
        this.element.showModal();
        this.firstNameTarget.focus({ preventScroll: true });
    }

    async submit(event) {
        event.preventDefault();
        if (this.request) return;
        for (const field of [this.firstNameTarget, this.lastNameTarget]) {
            field.setCustomValidity(field.value.trim() ? '' : this.nameErrorValue);
        }
        if (!this.formTarget.reportValidity()) return;

        const request = new AbortController();
        this.request = request;
        this.submitButtonTarget.disabled = true;
        this.formTarget.setAttribute('aria-busy', 'true');
        this.errorTarget.hidden = true;
        try {
            const response = await fetch(this.formTarget.action, {
                method: 'POST', body: new FormData(this.formTarget), credentials: 'same-origin',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, signal: request.signal,
            });
            const data = await response.json();
            if (!response.ok || !data.success) {
                for (const [name, message] of Object.entries(data.errors ?? {})) {
                    this.formTarget.elements.namedItem(name)?.setCustomValidity(message);
                }
                this.formTarget.reportValidity();
                this.errorTarget.textContent = response.status === 429 ? this.rateLimitMessageValue : response.status === 403 ? this.csrfMessageValue : this.errorMessageValue;
                this.errorTarget.hidden = false;
                return;
            }
            this.formTarget.hidden = true;
            this.successTarget.hidden = false;
            this.element.setAttribute('aria-labelledby', 'contact-modal-success-title');
            this.element.setAttribute('aria-describedby', 'contact-modal-success-description');
            this.successTitleTarget.focus({ preventScroll: true });
        } catch (error) {
            if (error.name !== 'AbortError') {
                this.errorTarget.textContent = this.errorMessageValue;
                this.errorTarget.hidden = false;
            }
        } finally {
            if (this.request === request) {
                this.request = null;
                this.submitButtonTarget.disabled = false;
                this.formTarget.removeAttribute('aria-busy');
            }
        }
    }

    clearValidation(event) {
        event.target.setCustomValidity?.('');
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
        this.request?.abort();
        this.request = null;
        this.formTarget.removeAttribute('aria-busy');
        document.documentElement.classList.remove('contact-modal-open');
        if (this.trigger?.isConnected) this.trigger.focus({ preventScroll: true });
    }

    disconnect() {
        this.request?.abort();
        if (this.element.open) this.element.close();
        document.documentElement.classList.remove('contact-modal-open');
    }
}
