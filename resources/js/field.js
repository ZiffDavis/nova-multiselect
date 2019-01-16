Nova.booting((Vue, router, store) => {
    Vue.component('index-multi-select', require('./components/IndexField'))
    Vue.component('detail-multi-select', require('./components/DetailField'))
    Vue.component('form-multi-select', require('./components/FormField'))
})
