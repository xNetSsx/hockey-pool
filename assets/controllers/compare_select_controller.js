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

    update() {
        const checkboxes = this.element.querySelectorAll('input[type="checkbox"]')
        let checked = 0

        checkboxes.forEach(cb => {
            const label = cb.closest('label')
            const badge = label?.querySelector('[data-badge]')

            if (cb.checked) {
                checked++
                label?.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20')
                label?.classList.remove('border-gray-200', 'dark:border-gray-700')
            } else {
                label?.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/20')
                label?.classList.add('border-gray-200', 'dark:border-gray-700')
            }
        })

        this.counterTarget.textContent = `Vybráno: ${checked}`
        this.submitTarget.disabled = checked < 2
    }
}
