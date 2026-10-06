import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['tab', 'panel', 'count', 'announcement', 'carousel'];
    static values = { announcementLabel: String };

    connect() {
        this.activeProject = 0;
        this.animations = [];
        this.reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        if ('IntersectionObserver' in window) {
            this.previewObserver = new IntersectionObserver(entries => {
                if (!entries.some(entry => entry.isIntersecting)) return;
                this.carouselTarget.querySelectorAll('img').forEach(img => { img.loading = 'eager'; });
                this.previewObserver.disconnect();
            }, { rootMargin: '400px' });
            this.previewObserver.observe(this.carouselTarget);
        }
    }

    disconnect() {
        this.previewObserver?.disconnect();
        this.animations.forEach(animation => animation.cancel());
    }

    select(event) {
        const index = event.params.index;
        this.selectProject(index, index > this.activeProject ? 1 : -1);
    }

    previous() {
        this.selectProject((this.activeProject - 1 + this.tabTargets.length) % this.tabTargets.length, -1);
    }

    next() {
        this.selectProject((this.activeProject + 1) % this.tabTargets.length, 1);
    }

    keydown(event) {
        const index = this.tabTargets.indexOf(event.currentTarget);
        let nextIndex;
        if (event.key === 'ArrowRight' || event.key === 'ArrowDown') nextIndex = (index + 1) % this.tabTargets.length;
        if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') nextIndex = (index - 1 + this.tabTargets.length) % this.tabTargets.length;
        if (event.key === 'Home') nextIndex = 0;
        if (event.key === 'End') nextIndex = this.tabTargets.length - 1;
        if (nextIndex === undefined) return;
        event.preventDefault();
        this.selectProject(nextIndex, event.key === 'ArrowLeft' || event.key === 'ArrowUp' ? -1 : 1);
        this.tabTargets[nextIndex].focus();
    }

    selectProject(index, direction = 1) {
        if (index === this.activeProject) return;
        this.animations.forEach(animation => animation.cancel());
        this.animations = [];
        this.panelTargets.forEach((panel, panelIndex) => {
            panel.hidden = panelIndex !== this.activeProject;
            panel.classList.remove('is-leaving');
            panel.inert = false;
            panel.removeAttribute('aria-hidden');
        });
        const previous = this.panelTargets[this.activeProject];
        const next = this.panelTargets[index];
        this.activeProject = index;
        this.tabTargets.forEach((tab, tabIndex) => {
            const selected = tabIndex === index;
            tab.setAttribute('aria-selected', String(selected));
            tab.tabIndex = selected ? 0 : -1;
        });
        next.hidden = false;
        this.countTarget.textContent = String(index + 1).padStart(2, '0');
        this.announcementTarget.textContent = this.announcementLabelValue
            .replace('%index%', index + 1)
            .replace('%total%', this.tabTargets.length)
            .replace('%name%', this.tabTargets[index].textContent.replace(/^0\d\s*/, '').trim());
        if (this.reducedMotion.matches) {
            previous.hidden = true;
            return;
        }
        previous.inert = true;
        previous.setAttribute('aria-hidden', 'true');
        previous.classList.add('is-leaving');
        const leaving = previous.animate([
            { opacity: 1, transform: 'translateY(0)' },
            { opacity: 0, transform: `translateY(${-direction * 35}px)` }
        ], { duration: 260, easing: 'ease-out', fill: 'forwards' });
        leaving.finished.then(() => {
            previous.hidden = true;
            previous.classList.remove('is-leaving');
        }).catch(() => {});
        this.animations.push(leaving);
        this.animations.push(next.querySelector('.project-visual').animate([
            { opacity: 0, transform: `translateY(${direction * 70}px) scale(.96)` },
            { opacity: 1, transform: 'translateY(0) scale(1)' }
        ], { duration: 650, easing: 'cubic-bezier(.22, 1, .36, 1)' }));
        this.animations.push(next.querySelector('.project-copy').animate([
            { opacity: 0, transform: `translateY(${direction * 28}px)` },
            { opacity: 1, transform: 'translateY(0)' }
        ], { duration: 520, delay: 80, easing: 'cubic-bezier(.22, 1, .36, 1)', fill: 'backwards' }));
    }
}
