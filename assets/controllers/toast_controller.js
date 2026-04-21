import { Controller } from '@hotwired/stimulus'

/**
 * Auto-dismissing toast notification.
 *
 * Starts a 5-second countdown on connect, then fades out and removes the element.
 * A progress bar at the bottom shrinks over the duration.
 * A close button allows immediate dismissal.
 *
 * Usage:
 *   <div data-controller="toast">
 *     <button data-action="click->toast#dismiss">&times;</button>
 *     ...message...
 *   </div>
 */
export default class extends Controller {
    static values = {
        duration: { type: Number, default: 5000 },
    }

    connect() {
        // Inject a progress bar at the bottom of the toast
        this._progressBar = document.createElement('div')
        Object.assign(this._progressBar.style, {
            position: 'absolute',
            bottom: '0',
            left: '0',
            height: '3px',
            width: '100%',
            background: 'currentColor',
            opacity: '0.3',
            borderRadius: '0 0 var(--radius) var(--radius)',
            transition: `width ${this.durationValue}ms linear`,
        })
        this.element.style.position = 'relative'
        this.element.style.overflow = 'hidden'
        this.element.appendChild(this._progressBar)

        // Kick off the shrinking animation on next frame so the transition triggers
        requestAnimationFrame(() => {
            this._progressBar.style.width = '0%'
        })

        // Set up the auto-dismiss timer
        this._timeout = setTimeout(() => {
            this._fadeOut()
        }, this.durationValue)
    }

    disconnect() {
        clearTimeout(this._timeout)
        if (this._progressBar && this._progressBar.parentNode) {
            this._progressBar.remove()
        }
    }

    dismiss() {
        clearTimeout(this._timeout)
        this._fadeOut()
    }

    _fadeOut() {
        Object.assign(this.element.style, {
            transition: 'opacity 300ms ease, transform 300ms ease',
            opacity: '0',
            transform: 'translateY(-10px)',
        })

        this.element.addEventListener('transitionend', () => {
            this.element.remove()
        }, { once: true })

        // Safety net: remove after 400ms even if transitionend doesn't fire
        setTimeout(() => {
            if (this.element && this.element.parentNode) {
                this.element.remove()
            }
        }, 400)
    }
}
