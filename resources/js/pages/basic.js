const appName = 'vue-basic'

if (jQuery('#' + appName).length > 0) {
    new Vue({
        el: '#' + appName,
    })
}
