// noinspection BadExpressionStatementJS JSLint
/**
 * Select2
 */
require('select2/dist/js/select2.min.js')

getSelect2Id = (e) => {
    var c = e.target.parentElement.children
    for (var i = 0; i < c.length; i++) {
        if (c[i].tagName === 'SPAN') {
            c = c[i].children
            break
        }
    }
    for (var i = 0; i < c.length; i++) {
        if (c[i].tagName === 'SPAN' && c[i].className === 'selection') {
            c = c[i].children
            break
        }
    }
    for (var i = 0; i < c.length; i++) {
        c = c[i]
    }

    return c.getAttribute('aria-owns').replace('select2-', '').replace('-results', '')
}

$(() => {
    // jshint ignore:line
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap4',
            tags: false,
            width: '100%',
        })

        $('.select2').on('change', function (e) {
            // e.target.find(':selected')

            window.livewire.emit('update-field', {
                field: e.target.getAttribute('name'),
                value: $('#' + e.target.getAttribute('name'))
                    .find(':selected')
                    .val(),
            })
        })

        $('.select2-tag').select2({
            theme: 'bootstrap4',
            tags: true,
            width: '100%',
        })
    })
})

$(document).on('select2:open', (e) => {
    $(".select2-search__field[aria-controls='select2-" + getSelect2Id(e) + "-results']").each(
        function (key, value) {
            value.focus()
        },
    )
})

//
// $('.select2').on('change', function (e) {
//     var data = $('.select2').select2("val");
// @this.set('userProp', data);
// });
//
// window.addEventListener('contentChanged', event => {
//     $('#users-dropdown').on('change', function (e) {
//         var data = $('#users-dropdown').select2("val");
//     @this.set('userProp', data);
//     });
// });
