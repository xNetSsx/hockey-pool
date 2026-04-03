import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static values = { data: String }

    connect() {
        import('qrcode').then(({ default: QRCode }) => {
            QRCode.toCanvas(this.element, this.dataValue, {
                width: 180,
                margin: 1,
                color: { dark: '#0f172a', light: '#ffffff' },
            })
        })
    }
}
