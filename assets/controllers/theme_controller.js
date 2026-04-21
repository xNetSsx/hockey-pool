import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['lightIcon', 'darkIcon']

    connect() {
        this.applyTheme()
    }

    toggle() {
        const isDark = document.documentElement.dataset.theme === 'dark'
        const next = isDark ? 'light' : 'dark'
        localStorage.setItem('hp:theme', next)
        // Keep legacy key for backward compat during transition
        localStorage.setItem('theme', next)
        this.applyTheme()
    }

    applyTheme() {
        const stored = localStorage.getItem('hp:theme') || localStorage.getItem('theme')
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
        const isDark = stored === 'dark' || (!stored && prefersDark)

        // New token system
        document.documentElement.dataset.theme = isDark ? 'dark' : 'light'
        document.documentElement.dataset.variant = document.documentElement.dataset.variant || 'rink'

        // Legacy Tailwind dark: variant
        document.documentElement.classList.toggle('dark', isDark)

        if (this.hasLightIconTarget && this.hasDarkIconTarget) {
            this.lightIconTarget.classList.toggle('hidden', !isDark)
            this.darkIconTarget.classList.toggle('hidden', isDark)
        }
    }
}
