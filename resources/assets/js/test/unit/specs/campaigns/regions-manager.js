/**
 * Created by jerezb on 2017-05-08.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><regions-manager v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28" :campaign-country="{ name: \'United States\', code: \'us\' }" ></regions-manager></div>';
RootInstance.components = {'regions-manager': require('../../../../components/regions/regions-manager.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let RegionsManager = vm.$refs.testComponent;

test('countries loaded', t => {
    t.truthy(RegionsManager.UTILITIES.countries.length);
});

// test('regions loaded', t => {
//     t.truthy(RegionsManager.regions.length);
// });

test('create region w/ modal', async t => {
    t.false(RegionsManager.showRegionModal);
    RegionsManager.openRegionModal();
    t.true(RegionsManager.showRegionModal);
    // set region name
    RegionsManager.selectedRegion.name = 'Matagalpa';
    await t.notThrows(RegionsManager.createRegion());
});