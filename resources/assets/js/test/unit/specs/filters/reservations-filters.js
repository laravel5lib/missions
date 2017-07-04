/**
 * Created by jerezb on 2017-06-26.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><reservations-filters v-ref:test-component :filters="$root.filtersVars.filters" :pagination="$root.filtersVars.pagination" :callback="$root.filtersMethodsCallback" :reset-callback="$root.filtersMethodsReset"></reservations-filters></div>';
RootInstance.components = {'reservations-filters': require('../../../../components/filters/reservations-filters.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');
let ReservationsFilters = vm.$refs.testComponent;
test('property exists', t => {
    t.true(ReservationsFilters.propertyExists('type'));
});

test.before('set filter values', () => {
    ReservationsFilters.shirtSizeArr = ReservationsFilters.shirtSizeOptions[0];
    ReservationsFilters.campaignObj = ReservationsFilters.campaignOptions[0];
});

test('check filter values', async (t) => {
    await nextTick();
    t.is(ReservationsFilters.filters.campaign, ReservationsFilters.campaignOptions[0].id);
});

test('reset filters', async t => {
    await nextTick();
    vm.filtersMethodsReset();
    await nextTick();
    t.is(ReservationsFilters.filters.campaign, null);
});
