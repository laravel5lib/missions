/**
 * Created by jerezb on 2017-06-14.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><regions-accommodations v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28"></regions-accommodations></div>';
RootInstance.components = {'regions-accommodations': require('../../../../components/regions/regions-accommodations.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');
let RegionsAccommodations = vm.$refs.testComponent;

test('regions populated', t => {
    t.truthy(RegionsAccommodations.regions.length);
});

test('room types populated', t => {
    t.truthy(RegionsAccommodations.roomTypes.length);
});

test.before('select region', () => {
    RegionsAccommodations.currentRegion = RegionsAccommodations.regions[0];
});

test('accommodations populated', async t => {
    await nextTick();
    t.truthy(RegionsAccommodations.accommodations.length);
});

test.before('select accommodation', () => {
    RegionsAccommodations.currentAccommodation = RegionsAccommodations.accommodations[0];
});

test.todo('create accommodation');
test.todo('update accommodation');
test.todo('delete accommodation');
