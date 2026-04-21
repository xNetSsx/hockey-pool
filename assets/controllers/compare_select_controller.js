import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['counter', 'submit']

    connect() {
        this.update()
    }

    selectAll() {
        this.element.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.checked = true
        })
        this.update()
    }

    compareAll() {
        this.selectAll()
        this.element.closest('form').submit()
    }

    update() {
        const checkboxes = this.element.querySelectorAll('input[type="checkbox"]')
        let checked = 0

        checkboxes.forEach(cb => {
            const label = cb.closest('label')

            if (cb.checked) {
                checked++
                if (label) {
                    label.style.background = 'color-mix(in oklab, var(--accent) 18%, var(--bg-2))'
                    label.style.borderColor = 'var(--accent)'
                    label.style.color = 'var(--fg)'
                }
            } else {
                if (label) {
                    label.style.background = 'var(--bg-2)'
                    label.style.borderColor = 'var(--line)'
                    label.style.color = 'var(--fg-2)'
                }
            }
        })

        this.counterTarget.textContent = `Vybráno: ${checked}`

        const btn = this.submitTarget
        btn.disabled = checked < 2
        btn.style.opacity = checked < 2 ? '0.4' : '1'
        btn.style.cursor = checked < 2 ? 'not-allowed' : 'pointer'
    }
}
