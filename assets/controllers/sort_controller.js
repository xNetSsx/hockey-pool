import { Controller } from '@hotwired/stimulus'

/**
 * Sortable table columns via header click.
 *
 * Usage:
 *   <table data-controller="sort">
 *     <thead>
 *       <tr>
 *         <th data-action="click->sort#toggle"
 *             data-sort-col-param="2"
 *             data-sort-key-param="points"
 *             style="cursor:pointer">Body</th>
 *       </tr>
 *     </thead>
 *     <tbody> ... </tbody>
 *   </table>
 */
export default class extends Controller {
    static values = {
        key: { type: String, default: '' },
        dir: { type: String, default: 'desc' },
    }

    connect() {
        this._activeHeader = null
    }

    toggle(event) {
        const key = event.params.key || ''
        const colIndex = parseInt(event.params.col, 10)
        const th = event.currentTarget

        if (isNaN(colIndex)) return

        // Determine sort direction
        if (this.keyValue === key) {
            this.dirValue = this.dirValue === 'desc' ? 'asc' : 'desc'
        } else {
            this.keyValue = key
            this.dirValue = 'desc'
        }

        const tbody = this.element.querySelector('tbody')
        if (!tbody) return

        const rows = Array.from(tbody.querySelectorAll('tr'))
        if (rows.length === 0) return

        // Record positions before sort (for FLIP animation)
        const rects = new Map()
        rows.forEach(function (row) {
            rects.set(row, row.getBoundingClientRect())
        })

        // Sort rows
        const dir = this.dirValue === 'asc' ? 1 : -1
        rows.sort(function (a, b) {
            const aCell = a.children[colIndex]
            const bCell = b.children[colIndex]
            if (!aCell || !bCell) return 0

            const aText = (aCell.textContent || '').replace(/[^0-9.\-]/g, '').trim()
            const bText = (bCell.textContent || '').replace(/[^0-9.\-]/g, '').trim()

            const aNum = parseFloat(aText)
            const bNum = parseFloat(bText)

            if (isNaN(aNum) && isNaN(bNum)) return 0
            if (isNaN(aNum)) return 1
            if (isNaN(bNum)) return -1

            return (aNum - bNum) * dir
        })

        // Reorder DOM
        rows.forEach(function (row) {
            tbody.appendChild(row)
        })

        // FLIP animation: compute inverse transforms and animate to identity
        rows.forEach(function (row) {
            const oldRect = rects.get(row)
            const newRect = row.getBoundingClientRect()
            const deltaY = oldRect.top - newRect.top

            if (Math.abs(deltaY) < 1) return

            row.style.transform = 'translateY(' + deltaY + 'px)'
            row.style.transition = 'none'

            requestAnimationFrame(function () {
                row.style.transition = 'transform 300ms ease'
                row.style.transform = 'translateY(0)'
            })
        })

        // Clean up transition styles after animation
        setTimeout(function () {
            rows.forEach(function (row) {
                row.style.transform = ''
                row.style.transition = ''
            })
        }, 320)

        // Update sort indicators on headers
        this._updateIndicators(th)
    }

    _updateIndicators(activeTh) {
        // Clear all indicators
        this.element.querySelectorAll('th [data-sort-indicator]').forEach(function (el) {
            el.remove()
        })

        // Add indicator to active header
        var indicator = document.createElement('span')
        indicator.setAttribute('data-sort-indicator', '')
        indicator.style.marginLeft = '4px'
        indicator.style.fontSize = '10px'
        indicator.style.opacity = '0.7'
        indicator.textContent = this.dirValue === 'desc' ? '\u25BC' : '\u25B2'
        activeTh.appendChild(indicator)
    }
}
