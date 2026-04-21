import { Controller } from '@hotwired/stimulus'

/**
 * Fires a confetti burst from the click origin on form submit.
 *
 * Usage:
 *   <form data-controller="confetti" data-action="submit->confetti#fire">
 */
export default class extends Controller {
    fire(event) {
        const rect = event.submitter
            ? event.submitter.getBoundingClientRect()
            : event.target.getBoundingClientRect()
        const x = rect.left + rect.width / 2
        const y = rect.top + rect.height / 2

        const colors = [
            'var(--accent)', 'var(--pos)', 'var(--warn)', 'var(--neg)',
            '#fff', 'var(--accent-2)',
        ]
        // Resolve CSS vars to actual colors for animation
        const root = getComputedStyle(document.documentElement)
        const resolved = colors.map(c =>
            c.startsWith('var(')
                ? root.getPropertyValue(c.slice(4, -1)).trim() || '#38bdf8'
                : c
        )

        for (let i = 0; i < 60; i++) {
            const el = document.createElement('div')
            el.className = 'hp-confetti-piece'
            el.style.left = x + 'px'
            el.style.top = y + 'px'
            el.style.background = resolved[i % resolved.length]
            el.style.transform = `rotate(${Math.random() * 360}deg)`
            document.body.appendChild(el)

            const dx = (Math.random() - 0.5) * 500
            const dy = -150 - Math.random() * 250
            const rot = Math.random() * 720

            el.animate([
                { transform: 'translate(0,0) rotate(0deg)', opacity: 1 },
                { transform: `translate(${dx}px, ${dy}px) rotate(${rot}deg)`, opacity: 1, offset: 0.6 },
                { transform: `translate(${dx * 1.2}px, ${dy + 600}px) rotate(${rot * 1.4}deg)`, opacity: 0 },
            ], {
                duration: 1800 + Math.random() * 600,
                easing: 'cubic-bezier(.2,.7,.3,1)',
            })

            setTimeout(() => el.remove(), 2500)
        }
    }
}
