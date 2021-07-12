const appName = 'vue-basic'

if (jQuery('#' + appName).length > 0) {
    console.log('vue-basic')
    new Vue({
        el: '#' + appName,
    })
}
