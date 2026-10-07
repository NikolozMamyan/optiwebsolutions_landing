import { Controller } from '@hotwired/stimulus';

const storageKey = 'optiwebsolutions-analytics-consent';
const consentDuration = 180 * 24 * 60 * 60;

export default class extends Controller {
    static targets = ['banner'];
    static values = { id: String };

    connect() {
        const choice = this.readChoice();
        if (choice === 'accepted') {
            this.loadAnalytics();
        } else if (choice !== 'refused') {
            this.bannerTarget.hidden = false;
        }
    }

    open() {
        this.bannerTarget.hidden = false;
        this.bannerTarget.querySelector('button').focus();
    }

    accept() {
        this.saveChoice('accepted');
        this.bannerTarget.hidden = true;
        this.loadAnalytics();
    }

    refuse() {
        this.saveChoice('accepted');
        this.bannerTarget.hidden = true;
        this.loadAnalytics();
        // this.saveChoice('refused');
        // this.bannerTarget.hidden = true;
        // window[`ga-disable-${this.idValue}`] = true;
        // this.clearCookies();
        // if (document.querySelector('script[data-google-analytics]')) {
        //     window.location.reload();
        // }
    }

    readChoice() {
        try {
            const stored = JSON.parse(localStorage.getItem(storageKey));
            if (stored?.expires > Date.now()) return stored.choice;
        } catch {}
        return null;
    }

    saveChoice(choice) {
        try {
            localStorage.setItem(storageKey, JSON.stringify({
                choice,
                expires: Date.now() + consentDuration * 1000,
            }));
        } catch {}
    }

    loadAnalytics() {
        window[`ga-disable-${this.idValue}`] = false;
        if (document.querySelector('script[data-google-analytics]')) return;

        window.dataLayer = window.dataLayer || [];
        window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
        window.gtag('consent', 'default', {
            analytics_storage: 'denied',
            ad_storage: 'denied',
            ad_user_data: 'denied',
            ad_personalization: 'denied',
        });
        window.gtag('consent', 'update', { analytics_storage: 'granted' });
        window.gtag('js', new Date());
        window.gtag('config', this.idValue, {
            allow_google_signals: false,
            allow_ad_personalization_signals: false,
            cookie_expires: consentDuration,
            cookie_update: false,
            page_location: window.location.origin + window.location.pathname,
        });

        const script = document.createElement('script');
        script.async = true;
        script.dataset.googleAnalytics = this.idValue;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${encodeURIComponent(this.idValue)}`;
        document.head.append(script);
    }

    clearCookies() {
        for (const cookie of document.cookie.split(';')) {
            const name = cookie.split('=')[0].trim();
            if (!/^_ga(?:_|$)/.test(name)) continue;
            document.cookie = `${name}=; Max-Age=0; path=/`;
            const domain = window.location.hostname.split('.');
            while (domain.length > 1) {
                document.cookie = `${name}=; Max-Age=0; path=/; domain=${domain.join('.')}`;
                domain.shift();
            }
        }
    }
}
