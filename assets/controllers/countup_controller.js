import { Controller } from '@hotwired/stimulus'

/**
 * Animates a number from 0 to its final value on first appearance.
 *
 * Usage:
 *   <span data-controller="countup" data-countup-value-value="42.50" data-countup-decimals-value="2">42.50</span>
 */
export default class extends Controller {
    static values = {
        value: Number,
        decimals: { type: Number, default: 2 },
        duration: { type: Number, default: 800 },
    }

    connect() {
        // Only animate if element is visible (IntersectionObserver)
        if ('IntersectionObserver' in window) {
            this._observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this._animate()
                        this._observer.disconnect()
                    }
                })
            }, { threshold: 0.1 })
            this._observer.observe(this.element)
        } else {
            this._animate()
        }
    }

    disconnect() {
        if (this._observer) this._observer.disconnect()
        if (this._raf) cancelAnimationFrame(this._raf)
    }

    _animate() {
        const target = this.valueValue
        const decimals = this.decimalsValue
        const duration = this.durationValue
        const start = performance.now()

        const step = (now) => {
            const progress = Math.min(1, (now - start) / duration)
            // Ease-out cubic
            const eased = 1 - Math.pow(1 - progress, 3)
            const current = target * eased
            this.element.textContent = current.toFixed(decimals)
            if (progress < 1) {
                this._raf = requestAnimationFrame(step)
            }
        }

        this.element.textContent = (0).toFixed(decimals)
        this._raf = requestAnimationFrame(step)
    }
}
