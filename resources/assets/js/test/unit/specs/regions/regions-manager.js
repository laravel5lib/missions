/**
 * Created by jerezb on 2017-06-14.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

// set some variables
RootInstance.someObject = {
    name: 'United States',
    code: 'us'
};
//load the component with a vue instance
RootInstance.template = '<div><regions-manager v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28" :campaign-country="$root.someObject"></regions-manager></div>';
RootInstance.components = {'regions-manager': require('../../../../components/regions/regions-manager.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');
let RegionsManager = vm.$refs.testComponent;

test('countries populated', t => {
    t.truthy(RegionsManager.UTILITIES.countries.length);
});

test('team types populated', t => {
    t.truthy(RegionsManager.squadTypes.length);
});

test('regions populated', t => {
    t.truthy(RegionsManager.regions.length);
});

test('squads populated', t => {
    // t.truthy(RegionsManager.squads.length);
});

test('groups populated', t => {
    // t.truthy(RegionsManager.groups.length);
});

/*test.before('set campaignCountry', () => {
    RegionsManager.campaignCountry = { name: 'United States', code: 'us' };
});*/

test('create new region', async t => {
    //let obj = _.extend({}, RegionsManager.regionFactory());
    await nextTick();
    t.is(_.extend({}, RegionsManager.regionFactory()), {
        name: '',
        country: RegionsManager.campaignCountry,
        country_code: RegionsManager.campaignCountry.code,
        callsign: '',
    });
});

test('select region', t => {
    RegionsManager.makeCurrentRegion(RegionsManager.regions[0]);
    t.is(RegionsManager.currentRegion, RegionsManager.regions[0]);
});
