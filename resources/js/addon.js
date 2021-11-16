import LemonSqueezy from './components/fieldtypes/LemonSqueezy.vue'

Statamic.booting(() => {
    Statamic.component('lemon_squeezy-fieldtype', LemonSqueezy)
})
