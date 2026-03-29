import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['lightIcon', 'darkIcon']

    connect() {
        this.applyTheme()
    }

    toggle() {
        const isDark = document.documentElement.classList.contains('dark')
        localStorage.setItem('theme', isDark ? 'light' : 'dark')
        this.applyTheme()
    }

    applyTheme() {
        const stored = localStorage.getItem('theme')
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
        const isDark = stored === 'dark' || (!stored && prefersDark)

        document.documentElement.classList.toggle('dark', isDark)

        if (this.hasLightIconTarget && this.hasDarkIconTarget) {
            this.lightIconTarget.classList.toggle('hidden', !isDark)
            this.darkIconTarget.classList.toggle('hidden', isDark)
        }
    }
}
