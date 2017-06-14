/**
 * Created by jerezb on 2017-02-23.
 */
// Global Components
import VueStrap from 'vue-strap/dist/vue-strap.min';
// Vue.component('tour-guide', tourGuide);
Vue.component('pagination', pagination);
Vue.component('modal', VueStrap.modal);
Vue.component('accordion', VueStrap.accordion);
Vue.component('alert', VueStrap.alert);
Vue.component('aside', VueStrap.aside);
Vue.component('panel', VueStrap.panel);
Vue.component('checkbox', VueStrap.checkbox);
Vue.component('progressbar', VueStrap.progressbar);
Vue.component('dropdown', VueStrap.dropdown);
Vue.component('spinner', VueStrap.spinner);
Vue.component('popover', VueStrap.popover);
Vue.component('tabs', VueStrap.tabset);
Vue.component('tab', VueStrap.tab);
Vue.component('tooltip', VueStrap.tooltip);
// Vue.component('vSelect', require('vue-select'));
// import myDatepicker from 'vue-datepicker/vue-datepicker-1.vue'
import myDatepicker from '../components/date-picker.vue'
Vue.component('date-picker', myDatepicker);

// Vue Validator
Vue.use(require('vue-validator'));
