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

window.Swal = require('sweetalert2')

window.addEventListener('swal', function (e) {
    const options = e.detail

    Swal.fire({
        title: options.title,
        text: options.text,
        icon: 'warning',
        showConfirmButton: true,
        cancelButtonColor: '#E3352E',
        confirmButtonColor: '#38c172',
        confirmButtonText: 'confirmar',
        cancelButtonText: 'cancelar',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.emit(options.confirmEvent)

            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                showCancelButton: false,
                timer: 2000,
                icon: 'success',
                title: (options.action === 'delete' ? 'Apagado' : 'Salvo') + ' com sucesso',
            })
        }
    })
})

window.addEventListener('show-modal', function (e) {
    $('#' + e.detail.target).modal('show')
})

window.addEventListener('hide-modal', (e) => {
    $('#' + e.detail.target).modal('hide')
})
