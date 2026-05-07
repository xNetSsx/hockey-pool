import { Controller } from '@hotwired/stimulus'

const TOURS = {
    prediction_list: {
        title: 'Průvodce tipováním',
        steps: [
            {
                element: '#predictions-header',
                popover: {
                    title: '🎯 Moje tipy',
                    description: 'Hlavní stránka aplikace. Vidíš zde všechny zápasy aktuálního turnaje a stav svých tipů.',
                },
            },
            {
                element: '#special-bets-btn',
                popover: {
                    title: '⭐ Speciální tipy',
                    description: 'Bonusové otázky na celý turnaj — kdo vyhraje zlatou, kdo dá nejvíc gólů apod. Přinášejí extra body nad rámec tipů na zápasy.',
                },
            },
            {
                element: '#others-bets-btn',
                popover: {
                    title: '👥 Tipy ostatních',
                    description: 'Přehled speciálních tipů všech hráčů. Srovnej si své tipy s ostatními.',
                },
            },
            {
                element: '.tour-phase',
                popover: {
                    title: '🏆 Fáze turnaje',
                    description: 'Zápasy jsou rozděleny do skupinové fáze, čtvrtfinále, semifinále a finále.',
                },
            },
            {
                element: '.tour-game-row',
                popover: {
                    title: '🏒 Jeden zápas',
                    description: 'Vidíš čas, vlajky a kódy týmů, skóre (po odehrání) a svůj tip. Barevný odznak po zápase: zelená = přesné skóre, modrá = správný vítěz, červená = špatně.',
                    side: 'bottom',
                    align: 'start',
                },
            },
            {
                element: '.tour-game-action',
                popover: {
                    title: '✏️ Jak tipovat',
                    description: 'Klikni na „Tipovat" pro zadání výsledku zápasu. Tip lze kdykoli změnit až do začátku zápasu — pak se automaticky zamkne.',
                },
            },
        ],
    },
    homepage: {
        title: 'Průvodce žebříčkem',
        steps: [
            {
                element: '.tour-leaderboard',
                popover: {
                    title: '🏆 Žebříček',
                    description: 'Aktuální pořadí všech hráčů s bodovým skóre. Kliknutím na jméno hráče zobrazíš jeho profil a historii tipů.',
                },
            },
            {
                element: '.tour-chart',
                popover: {
                    title: '📈 Vývoj bodů',
                    description: 'Graf ukazuje, jak se skóre hráčů měnilo v průběhu turnaje. Ideální pro sledování vzestupů a pádů.',
                },
            },
            {
                element: '.tour-stats',
                popover: {
                    title: '📊 Statistiky turnaje',
                    description: 'Přehled zajímavostí — kolik zápasů je odehráno, kdo má nejvyšší bodový zisk za jeden zápas a kdo tipuje nejpřesněji.',
                    side: 'left',
                },
            },
            {
                element: '.tour-upcoming',
                popover: {
                    title: '🕐 Nadcházející zápasy',
                    description: 'Nejbližší zápasy, které je potřeba otipovat. Nezapomeň zadat tipy včas — po začátku zápasu se zamknou!',
                    side: 'left',
                },
            },
        ],
    },
    hall_of_fame: {
        title: 'Průvodce Síní slávy',
        steps: [
            {
                element: '.tour-hof-alltime',
                popover: {
                    title: '🏅 Celkové pořadí hráčů',
                    description: 'Souhrnné výsledky přes všechny odehrané turnaje — průměrné umístění, počet výher (🥇🥈🥉) a celková přesnost tipů.',
                },
            },
            {
                element: '.tour-hof-history',
                popover: {
                    title: '📅 Historie turnajů',
                    description: 'Přehled výsledků jednotlivých turnajů — kdo vyhrál a s jakými body. Srovnej různé ročníky mezi sebou.',
                },
            },
        ],
    },
}

export default class extends Controller {
    static targets = ['panel', 'backdrop', 'rulesContent', 'rulesArrow', 'manualContent', 'manualArrow']
    static values = { route: String }

    connect() {
        this._restoreWidth()
        this._beforeCache = () => this.close()
        document.addEventListener('turbo:before-cache', this._beforeCache)
        const url = new URL(window.location.href)
        if (url.searchParams.has('tour')) {
            url.searchParams.delete('tour')
            history.replaceState({}, '', url)
            setTimeout(() => this.startTour(), 400)
        }
    }

    open() {
        this.panelTarget.classList.remove('translate-x-full')
        this.backdropTarget.classList.remove('hidden')
        document.body.classList.add('overflow-hidden')
    }

    close() {
        this.panelTarget.classList.add('translate-x-full')
        this.backdropTarget.classList.add('hidden')
        document.body.classList.remove('overflow-hidden')
    }

    toggle() {
        this.panelTarget.classList.contains('translate-x-full') ? this.open() : this.close()
    }

    toggleRules() {
        const hidden = this.rulesContentTarget.classList.toggle('hidden')
        this.rulesArrowTarget.textContent = hidden ? '▼' : '▲'
    }

    toggleManual() {
        const hidden = this.manualContentTarget.classList.toggle('hidden')
        this.manualArrowTarget.textContent = hidden ? '▼' : '▲'
    }

    startTour() {
        if (this.element.isConnected) {
            this.close()
        }
        const tour = TOURS[this.routeValue]
        if (!tour?.steps?.length) return

        const steps = tour.steps.filter(s => !s.element || document.querySelector(s.element))
        if (!steps.length) return

        if (!window.driver?.js?.driver) {
            setTimeout(() => this.startTour(), 500)
            return
        }

        const { driver } = window.driver.js
        driver({
            showProgress: true,
            progressText: '{{current}} / {{total}}',
            nextBtnText: 'Další →',
            prevBtnText: '← Zpět',
            doneBtnText: 'Hotovo ✓',
            steps,
        }).drive()
    }

    startResize(event) {
        event.preventDefault()
        this._startX = event.clientX
        this._startWidth = this.panelTarget.offsetWidth
        this._onMove = this._doResize.bind(this)
        this._onUp = this._stopResize.bind(this)
        document.addEventListener('mousemove', this._onMove)
        document.addEventListener('mouseup', this._onUp)
        document.body.style.cursor = 'col-resize'
        document.body.style.userSelect = 'none'
    }

    startResizeTouch(event) {
        if (event.touches.length !== 1) return
        this._startX = event.touches[0].clientX
        this._startWidth = this.panelTarget.offsetWidth
        this._onMove = (e) => { e.preventDefault(); this._doResize({ clientX: e.touches[0].clientX }) }
        this._onUp = this._stopResize.bind(this)
        document.addEventListener('touchmove', this._onMove, { passive: false })
        document.addEventListener('touchend', this._onUp)
    }

    _doResize(event) {
        const delta = this._startX - event.clientX
        const newWidth = Math.min(Math.max(this._startWidth + delta, 280), window.innerWidth - 60)
        this.panelTarget.style.width = newWidth + 'px'
    }

    _stopResize() {
        document.removeEventListener('mousemove', this._onMove)
        document.removeEventListener('mouseup', this._onUp)
        document.removeEventListener('touchmove', this._onMove)
        document.removeEventListener('touchend', this._onUp)
        document.body.style.cursor = ''
        document.body.style.userSelect = ''
        localStorage.setItem('helpPanelWidth', this.panelTarget.offsetWidth)
    }

    _restoreWidth() {
        const saved = localStorage.getItem('helpPanelWidth')
        if (!saved) return
        const w = Math.min(parseInt(saved, 10), window.innerWidth - 60)
        if (w >= 280) this.panelTarget.style.width = w + 'px'
    }

    disconnect() {
        document.body.classList.remove('overflow-hidden')
        document.removeEventListener('turbo:before-cache', this._beforeCache)
    }
}
