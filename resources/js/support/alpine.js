// import Inputmask from 'inputmask'
// window.Inputmask = Inputmask

import VMasker from 'vanilla-masker'
window.VMasker = VMasker

window.inputHandler = (masks, max, event) => {
    var c = event.target
    var v = c.value.replace(/\D/g, '')
    var m = c.value.length > max ? 1 : 0
    VMasker(c).unMask()
    VMasker(c).maskPattern(masks[m])
    c.value = VMasker.toPattern(v, masks[m])
}

import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

window.livewire.on('userStore', () => {
    $('#createPeriod').modal('hide')
})
